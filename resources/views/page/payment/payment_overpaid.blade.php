<x-layout>
    <x-slot:active>payment</x-slot:active>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('payment.payment_overpaid') }}</div>
                    <div class="card-body">
                        <p>{{ __('payment.you_overpaid', ['amount' => 'Rp ' . number_format($overpaidAmount)]) }}</p>
                        <p>{{ __('payment.balance_option_question') }}</p>

                        <form method="POST" action="{{ route('payment.handleOverpayment') }}">
                            @csrf
                            <input type="hidden" name="overpaid_amount" value="{{ $overpaidAmount }}">
                            <button type="submit" name="balance_option" value="yes"
                                class="btn btn-success">{{ __('payment.yes') }}</button>
                            <button type="submit" name="balance_option" value="no"
                                class="btn btn-danger">{{ __('payment.no') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
