<x-layout>
    <x-slot:active>friends</x-slot:active>
    <div class="container mt-4">
        <h1 class="mb-4">{{ __('friends.friend_requests') }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($requests->isNotEmpty())
            <div class="list-group">
                @foreach ($requests as $request)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="{{ $request->user->image ? 'data:image/png;base64,' . base64_encode($request->user->image) : 'https://via.placeholder.com/50' }}"
                                alt="User Picture" class="rounded-circle me-3"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            {{ $request->user->name }}
                        </div>

                        <div>
                            <form action="{{ route('friends.accept', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-success btn-sm">{{ __('friends.accept') }}</button>
                            </form>

                            <form action="{{ route('friends.reject', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('friends.reject') }}</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>{{ __('friends.no_pending_requests') }}</p>
        @endif
    </div>
</x-layout>
