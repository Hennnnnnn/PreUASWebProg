<x-layout>
    <x-slot:active>notification</x-slot:active>
    <div class="container mt-4">
        <h1 class="text-center mb-4">{{ __('notification.title') }}</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($notification as $n)
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-bell-fill text-primary me-3" style="font-size: 1.5rem;"></i>
                            <span>{{ $n->message }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>
