<x-layout>
    <x-slot:active>explore</x-slot:active>
    <form action="{{ route('explore.index') }}" method="GET"
        class="d-flex justify-content-center align-items-center flex-wrap mb-4 mt-2">
        <!-- Search Input -->
        <div class="mb-3 mx-2 w-100 w-md-auto" style="max-width: 300px;">
            <label for="hobby" class="form-label"><strong>{{ __('explore.filter_by_name') }}</strong></label>

            <input type="text" name="search" class="form-control" placeholder="{{ __('explore.filter_by_name') }}"
                value="{{ request('search') }}">
        </div>

        <!-- Hobby Dropdown -->
        <div class="mb-3 mx-2 w-100 w-md-auto" style="max-width: 300px;">
            <label for="hobby" class="form-label"><strong>{{ __('explore.filter_by_hobby') }}</strong></label>
            <select name="hobby" class="form-select">
                <option value="">{{ __('explore.all') }}</option>
                @foreach (__('explore.hobbies') as $key => $hobby)
                    <option value="{{ $key }}" {{ request('hobby') == $key ? 'selected' : '' }}>
                        {{ $hobby }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mx-2 w-100 w-md-auto" style="max-width: 150px;">
            {{ __('explore.apply_filters') }}
        </button>
    </form>

    <div class="container mt-4">
        <div class="row gap-4">
            @forelse  ($users as $u)
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
                            <button class="btn btn-danger">{{ __('explore.this_is_your_post') }}</button>
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
                                    disabled>{{ __('explore.already_wishlisted') }}</button>
                            @else
                                <form action="{{ route('wishlist.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="target" value="{{ $u->id }}">
                                    <button type="submit"
                                        class="btn btn-primary">{{ __('explore.add_to_wishlist') }}</button>
                                </form>
                            @endif

                        </div>
                    @endif
                </x-card>
            @empty
                <div class="col-12 text-center">
                    <p>{{ __('explore.no_users_found') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
