<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $price;

    public function __construct()
    {
        $this->price = rand(100000, 125000);
    }

    public function login_index()
    {
        $active = 'login';
        return view('page.auth.login', compact('active'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session()->flash('success', 'Login successful! Welcome back.');

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function register_index()
    {
        $active = 'register';
        $price = $this->price;
        return view('page.auth.register', compact('active', 'price'));
    }

    public function register(Request $request)
    {
        $price = $this->price;
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'gender' => ['required', 'in:Male,Female'],
            'hobby' => ['required', 'array', 'min:3'],
            'hobby.*' => ['string'],
            'instagram' => ['required', 'regex:/^https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9_.]+$/'],
            'phone_number' => ['required', 'digits_between:10,15'],
            'friendship_reason' => ['required', 'string', 'max:500'],
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'gender' => $validatedData['gender'],
            'hobby' => json_encode($validatedData['hobby']),
            'instagram' => $validatedData['instagram'],
            'phone_number' => $validatedData['phone_number'],
            'friendship_reason' => $validatedData['friendship_reason'],
            'regist_price' => $price,
        ]);

        Auth::login($user);

        return redirect('/payment');
    }

    public function paymentPage()
    {
        $price = Auth::user()->regist_price;
        return view('page.payment.payment', compact('price'));
    }
    // In your controller
    public function payment(Request $request)
    {
        $amount_price = (float) $request->input('payment_amount');
        $price = (float) Auth::user()->regist_price;

        $underpaid = $price - $amount_price;
        $overpaid = $amount_price - $price;

        if ($amount_price < $price) {
            return redirect()->route('payment_page')->withErrors([
                'payment_error' => 'You are still underpaid Rp ' . number_format(abs($underpaid)) . '. Please pay the remaining amount.'
            ]);
        }

        if ($amount_price > $price) {
            return view('page.payment.payment_overpaid', [
                'overpaidAmount' => $overpaid,
                'price' => $price
            ]);
        }
    }

    public function handleOverpayment(Request $request)
    {
        $userResponse = $request->input('balance_option');
        $overpaidAmount = $request->input('overpaid_amount');
        $user = Auth::user();
        $overpaidAmount = str_replace(',', '', $overpaidAmount);

        if ($userResponse == 'yes') {
            $coin_exchange = floor($overpaidAmount / 10000);
            $user->coin += $coin_exchange;
            $user->save();
            return redirect('/')->with('status', 'You overpaid, the balance has been added to your wallet!');
        }

        return redirect()->route('payment_page')->with('status', 'Please enter the correct payment amount.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'You have been logged out.');
    }
}
