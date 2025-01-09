<x-layout>
    <x-slot:active>profile</x-slot:active>
    <div class="container mt-4">
        <h1 class="mb-4">{{ __('edit-profile.edit_profile') }}</h1>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('edit-profile.name') }}</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $user->name) }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('edit-profile.email') }}</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $user->email) }}" required>
            </div>

            <!-- Image -->
            <div class="mb-3">
                <label for="image" class="form-label">{{ __('edit-profile.profile_picture') }}</label>
                <input type="file" name="image" id="image" class="form-control">
                @if ($user->image)
                    <img src="data:image/png;base64,{{ base64_encode($user->image) }}" alt="Profile Picture"
                        class="img-thumbnail mt-2" style="max-width: 150px;">
                @endif
            </div>

            <!-- Gender -->
            <div class="mb-3">
                <label for="gender" class="form-label">{{ __('edit-profile.gender') }}</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female
                    </option>
                </select>
            </div>

            <!-- Hobby -->
            <div class="mb-3">
                <label for="hobby" class="form-label">{{ __('edit-profile.hobby') }}</label>
                <textarea name="hobby" id="hobby" class="form-control">{{ old('hobby', implode(', ', json_decode($user->hobby, true) ?? [])) }}</textarea>
                <small class="text-muted">{{ __('edit-profile.separate_hobbies') }}</small>
            </div>

            <!-- Instagram -->
            <div class="mb-3">
                <label for="instagram" class="form-label">{{ __('edit-profile.instagram') }}</label>
                <input type="text" name="instagram" id="instagram" class="form-control"
                    value="{{ old('instagram', $user->instagram) }}" required>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phone_number" class="form-label">{{ __('edit-profile.phone_number') }}</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                    value="{{ old('phone_number', $user->phone_number) }}">
            </div>

            <!-- Friendship Reason -->
            <div class="mb-3">
                <label for="friendship_reason" class="form-label">{{ __('edit-profile.friendship_reason') }}</label>
                <textarea name="friendship_reason" id="friendship_reason" class="form-control" required>{{ old('friendship_reason', $user->friendship_reason) }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">{{ __('edit-profile.save_changes') }}</button>
            </div>
        </form>
    </div>
</x-layout>
