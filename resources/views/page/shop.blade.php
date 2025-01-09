<x-layout>
    <x-slot:active>shop</x-slot:active>
    <div class="container mt-4 d-flex flex-column align-items-center">
        <p class="mt-2">{{ __('shop.coin_balance', ['coin' => $coin]) }}</p>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

            <form action="{{ route('addCoin') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">{{ __('shop.top_up_coin') }}</button>
            </form>

            <h3 class="mt-4">{{ __('shop.available_avatars') }}</h3>
            <div class="row gap-2 justify-content-center">
                @foreach ($avatars as $avatar)
                    <div class="col-md-4 gap">
                        <div class="card">
                            <img src="data:image/png;base64,{{ base64_encode($avatar->image) }}" class="card-img-top"
                                alt="Avatar" style="width: 100%; height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ __('shop.price') }}: {{ $avatar->price }}
                                    {{ __('shop.coin_balance', ['coin' => '']) }}</h5>
                                <form action="{{ route('buyAvatar', $avatar->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">{{ __('shop.buy_avatar') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
    </div>
</x-layout>
