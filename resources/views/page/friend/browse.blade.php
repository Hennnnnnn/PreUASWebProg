<x-layout>
    <x-slot:active>friends</x-slot:active>
    <div class="container mt-4">
        <h1 class="mb-4">{{ __('friends.add_friends') }}</h1>

        <!-- Search Form -->
        <form action="{{ route('friends.add') }}" method="GET">
            <div class="mb-3">
                <input type="text" name="search" class="form-control" placeholder="{{ __('friends.search_placeholder') }}"
                    value="{{ request()->query('search') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('friends.search') }}</button>
        </form>

        @if (isset($users) && $users->isNotEmpty())
            <h3 class="mt-4">{{ __('friends.search_results') }}</h3>
            <div class="list-group">
                @foreach ($users as $user)
                    @if ($user->id != Auth::id())
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="{{ $user->image ? 'data:image/png;base64,' . base64_encode($user->image) : 'https://via.placeholder.com/50' }}"
                                    alt="User Picture" class="rounded-circle me-3"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                {{ $user->name }}
                            </div>

                            @if ($user->friendship_status)
                                @if ($user->friendship_status->status === 'pending')
                                    <button class="btn btn-warning btn-sm" disabled>{{ __('friends.pending') }}</button>
                                @elseif ($user->friendship_status->status === 'accepted')
                                    <button class="btn btn-success btn-sm" disabled>{{ __('friends.friend') }}</button>
                                @elseif ($user->friendship_status->status === 'rejected')
                                    <form action="{{ route('friends.send', $user->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('friends.send_request') }}</button>
                                    </form>
                                @endif
                            @else
                                <form action="{{ route('friends.send', $user->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-primary btn-sm">{{ __('friends.send_request') }}</button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @elseif (isset($users))
            <p>{{ __('friends.no_users_found') }}</p>
        @endif
    </div>
</x-layout>
