<x-layout>
    <x-slot:active>payment</x-slot:active>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('payment.payment') }}</div>
                    <div class="card-body">
                        @if ($errors->has('payment_error'))
                            <div class="alert alert-danger">
                                {{ $errors->first('payment_error') }}
                            </div>
                        @endif
                        @if ($errors->has('status'))
                            <div class="alert alert-danger">
                                {{ $errors->first('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('payment.process') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_amount"
                                    class="form-label">{{ __('payment.enter_payment_amount') }}</label>
                                <input type="number" class="form-control" id="payment_amount" name="payment_amount"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    {!! __('payment.total_registration_price', ['price' => number_format($price)]) !!}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('payment.submit_payment') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
