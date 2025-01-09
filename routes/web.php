<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login_index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'register_index'])->name('register_index');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/payment', [AuthController::class, 'paymentPage'])->name('payment_page');
    Route::post('/payment/process', [AuthController::class, 'payment'])->name('payment.process');
    Route::post('/payment/overpaid', [AuthController::class, 'handleOverpayment'])->name('payment.handleOverpayment');
    Route::post('/', [CoinController::class, 'addCoin'])->name('addCoin');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::post('/wishlist/add', [HomeController::class, 'add_wishlist'])->name('wishlist.add');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
    Route::get('/wishlist', [WishlistController::class, 'view_wishlist'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add_wishlist'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlist_id}', [WishlistController::class, 'remove_wishlist'])->name('wishlist.delete');
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');
    Route::post('/friends/send/{id}', [FriendController::class, 'sendRequest'])->name('friends.send');
    Route::get('/friends/requests', [FriendController::class, 'viewRequests'])->name('friends.requests');
    Route::post('/friends/accept/{id}', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/reject/{id}', [FriendController::class, 'rejectRequest'])->name('friends.reject');
    Route::delete('/friends/remove/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove');
    Route::get('/friends/add', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/notification', [NotificationController::class, 'index'])->name(name: 'notification');
    Route::get('/chat/{friend_id}', [ChatController::class, 'showChat'])->name('message');
    Route::post('/chat/{friend_id}', [ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::post('/profile/toggle-visibility', [ProfileController::class, 'toggleVisibility'])->name('profile.toggleVisibility');
    Route::post('buy-avatar/{avatarId}', [ShopController::class, 'buyAvatar'])->name('buyAvatar');
    Route::post('/profile/avatar/{avatarId}', [ProfileController::class, 'setAvatar'])->name('profile.setAvatar');
    Route::post('/avatar/send', [AvatarController::class, 'sendAvatar'])->name('avatar.send');
    Route::post('/profile/restore-photo', [ProfileController::class, 'restoreProfilePicture'])->name('profile.restorePhoto');
});

Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('set-locale');
