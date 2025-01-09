<x-layout>
    <x-slot:active>wishlist</x-slot:active>
    @if (session('success'))
        <div class="alert alert-success">{{ __('wishlist.success') }}</div>
    @endif
    <div class="container mt-4">
        <div class="row gap-4">
            @if (!empty($wishlist) && $wishlist->count() > 0)
                @foreach ($wishlist as $w)
                    <x-card>
                        <x-slot:image>
                            {{ 'data:image/png;base64,' . base64_encode($w->user->image) }}
                        </x-slot:image>
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $w->user->name ?? __('wishlist.unknown_user') }}
                            </h5>
                            <small>
                                {{ $w->user->instagram ?? __('wishlist.no_instagram') }}
                            </small>
                            @if (!empty($w->user->hobby) && is_array(json_decode($w->user->hobby, true)))
                                @foreach (json_decode($w->user->hobby, true) as $h)
                                    <span class="badge bg-warning d-flex flex-col mt-1 w-50">{{ $h }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary mt-1">{{ __('wishlist.no_hobbies') }}</span>
                            @endif
                            <form action="{{ route('wishlist.delete', ['wishlist_id' => $w->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-2">{{ __('wishlist.remove') }}</button>
                            </form>
                        </div>
                    </x-card>
                @endforeach
            @else
                <div>{{ __('wishlist.wishlist_empty') }}</div>
            @endif
        </div>
    </div>
</x-layout>
