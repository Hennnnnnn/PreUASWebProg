<x-layout>
    <x-slot:active>{{ "$active" }}</x-slot:active>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (Auth::check())
        <div class="container mt-4">
            <div class="row gap-4">
                @foreach ($allUser as $u)
                    <x-card>
                        <x-slot:image>
                            {{ 'data:image/png;base64,' . base64_encode($u->image) }}
                        </x-slot:image>
                        <div class="card-body">
                            <h5 class="card-title">{{ $u->name }}</h5>
                            <small>{{ $u->instagram }}</small>
                            @foreach (json_decode($u->hobby, true) as $h)
                                <span class="badge bg-warning d-flex flex-col mt-1 w-50">{{ $h }}</span>
                            @endforeach
                        </div>
                        @if ($u->id === Auth::user()->id)
                            <div class="card-body">
                                <button class="btn btn-danger">{{ __('home.this_is_your_post') }}</button>
                            </div>
                        @else
                            <div class="card-body">
                                @php
                                    $isWishlisted = Auth::user()
                                        ->wishlists()
                                        ->where('friend_id', $u->id)
                                        ->exists();
                                @endphp

                                @if ($isWishlisted)
                                    <button class="btn btn-secondary"
                                        disabled>{{ __('home.already_wishlisted') }}</button>
                                @else
                                    <form action="{{ route('wishlist.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="target" value="{{ $u->id }}">
                                        <button type="submit"
                                            class="btn btn-primary">{{ __('home.add_to_wishlist') }}</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </x-card>
                @endforeach
            </div>
        </div>
    @else
        <div class="container mt-4 text-center">
            <h4>{{ __('home.please_login') }}</h4>
        </div>
    @endif
</x-layout>
