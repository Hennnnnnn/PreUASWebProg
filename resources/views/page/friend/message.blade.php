<x-layout>
    <x-slot:active>message</x-slot:active>
    <div class="container mt-3">
        <p class="fs-2 fw-bold">{{ __('message.chat_with', ['name' => $friend->name]) }}</p>

        <div class="chat-box border rounded p-3 mb-3" style="height: 400px; overflow-y: auto;">
            @foreach ($messages as $message)
                <div class="mb-2">
                    @if ($message->user_id === Auth::id())
                        <div class="text-end">
                            @if ($message->file_data)
                                <img src="data:{{ $message->file_type }};base64,{{ base64_encode($message->file_data) }}"
                                    class="img-fluid rounded mb-2" style="max-width: 200px;">
                            @endif
                            @if ($message->chat)
                                <span class="badge bg-primary">{{ $message->chat }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-start">
                            @if ($message->file_data)
                                <img src="data:{{ $message->file_type }};base64,{{ base64_encode($message->file_data) }}"
                                    class="img-fluid rounded mb-2" style="max-width: 200px;">
                            @endif
                            @if ($message->chat)
                                <span class="badge bg-secondary">{{ $message->chat }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach

            @if ($messages->isEmpty())
                <div class="text-center">
                    <p>{{ __('message.no_messages') }}</p>
                </div>
            @endif
        </div>

        <form action="{{ route('sendMessage', $friend->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-2">
                <input type="text" name="chat" class="form-control"
                    placeholder="{{ __('message.type_message') }}">
            </div>
            <div class="input-group mb-2">
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">{{ __('message.send') }}</button>
        </form>
    </div>
</x-layout>
