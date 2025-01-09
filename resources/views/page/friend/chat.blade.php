<x-layout>
    <x-slot:active>chat</x-slot:active>
    <div class="container mt-3">
        <h1 class="text-center mb-4">Chat</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($user->friends as $friend)
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <img src="{{ $friend->image ? 'data:image/png;base64,' . base64_encode($friend->image) : 'https://via.placeholder.com/30' }}"
                                    alt="Friend Picture" class="rounded-circle me-2"
                                    style="width: 30px; height: 30px; object-fit: cover;">
                                {{ $friend->name }}
                            </div>
                            <a class="ba bi-chat-dots" href="{{ route('message', ['friend_id' => $friend->id]) }}"></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>
