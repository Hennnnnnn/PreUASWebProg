<x-layout>
    <x-slot:active>profile</x-slot:active>
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="mb-4">{{ __('profile.profile') }}</h1>
        <div class="card">
            <div class="card-body">
                <!-- Profile Picture -->
                <div class="mb-4 text-center">
                    @if ($user->image)
                        <img src="data:image/png;base64,{{ base64_encode($user->image) }}" alt="Profile Picture"
                            class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                    @elseif ($user->visibility == 'invisible')
                        <img src="https://via.placeholder.com/150" alt="Default Profile Picture" class="rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <form action="{{ route('profile.restorePhoto') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-success mt-2">{{ __('profile.restore_picture') }}</button>
                        </form>
                        <p class="mt-2">{{ __('profile.restore_picture_message') }}</p>
                    @else
                        <img src="https://via.placeholder.com/150" alt="Default Profile Picture" class="rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                </div>

                <div class="text-center mb-4">
                    <form action="{{ route('profile.toggleVisibility') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            @if ($user->visibility === 'visible')
                                {{ __('profile.hide_profile') }}
                            @else
                                {{ __('profile.show_profile') }}
                            @endif
                        </button>
                    </form>
                </div>

                <!-- Show Purchased Avatars Button -->
                @if ($user->avatars->isNotEmpty())
                    <div class="text-center mb-4">
                        <button class="btn btn-info"
                            id="toggleAvatarsBtn">{{ __('profile.purchased_avatars') }}</button>
                    </div>

                    <!-- Avatars List -->
                    <div id="purchasedAvatars" style="display:none;">
                        <h4 class="mt-4">{{ __('profile.purchased_avatars') }}</h4>
                        <div class="d-flex justify-content-center gap-3">
                            @foreach ($user->avatars as $avatar)
                                <div class="avatar-card">
                                    @if ($avatar->image)
                                        <!-- Display avatar image using base64 encoding for BLOB data -->
                                        <img src="data:image/png;base64,{{ base64_encode($avatar->image) }}"
                                            alt="Avatar" class="rounded-circle"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <!-- Fallback if there's no image -->
                                        <img src="https://via.placeholder.com/150" alt="Default Avatar"
                                            class="rounded-circle"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                    <form action="{{ route('profile.setAvatar', $avatar->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-success mt-2">{{ __('profile.set_as_profile') }}</button>
                                    </form>
                                    <form action="{{ route('avatar.send') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="avatar_id" value="{{ $avatar->id }}">
                                        <label for="receiver_id">{{ __('profile.send_avatar') }}:</label>
                                        <select name="receiver_id" class="form-control">
                                            @foreach ($users as $otherUser)
                                                @if ($otherUser->id !== Auth::id())
                                                    <!-- Exclude the current user -->
                                                    <option value="{{ $otherUser->id }}">{{ $otherUser->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="btn btn-primary mt-2">{{ __('profile.send_avatar') }}</button>
                                    </form>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif

                <!-- Received Avatars -->
                @if ($receivedAvatars->isNotEmpty())
                    <h4 class="mt-4">{{ __('profile.received_avatars') }}</h4>
                    <div class="d-flex justify-content-center gap-3">
                        @foreach ($receivedAvatars as $avatar)
                            <div class="avatar-card">
                                <img src="{{ asset('images/avatar' . $avatar->avatar_id . '.png') }}" alt="Avatar"
                                    class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                <form action="{{ route('profile.setAvatar', $avatar->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-success mt-2">{{ __('profile.set_as_profile') }}</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('profile.no_avatars_received') }}</p>
                @endif

                <!-- Profile Details -->
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>{{ __('profile.name') }}</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.email') }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.gender') }}</th>
                            <td>{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.hobbies') }}</th>
                            <td>{{ implode(', ', json_decode($user->hobby, true) ?? []) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.instagram') }}</th>
                            <td><a href="https://instagram.com/{{ $user->instagram }}"
                                    target="_blank">{{ $user->instagram }}</a></td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.phone_number') }}</th>
                            <td>{{ $user->phone_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.friendship_reason') }}</th>
                            <td>{{ $user->friendship_reason }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.registration_price') }}</th>
                            <td>{{ $user->regist_price }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('profile.coins') }}</th>
                            <td>{{ $user->coin ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Edit Profile Button -->
                <div class="text-center mb-4">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('profile.edit_profile') }}</a>
                </div>

                <!-- Friends Section -->
                <h3 class="mt-4">{{ __('profile.friends') }}</h3>
                @if ($user->friends->isEmpty())
                    <p>{{ __('profile.no_friends') }}</p>
                @else
                    <ul class="list-group">
                        @foreach ($user->friends as $friend)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <img src="{{ $friend->image ? 'data:image/png;base64,' . base64_encode($friend->image) : 'https://via.placeholder.com/30' }}"
                                        alt="Friend Picture" class="rounded-circle me-2"
                                        style="width: 30px; height: 30px; object-fit: cover;">
                                    {{ $friend->name }}
                                </div>
                                <form action="{{ route('friends.remove', $friend->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger btn-sm">{{ __('profile.remove') }}</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.getElementById("toggleAvatarsBtn").addEventListener("click", function() {
            var avatarsSection = document.getElementById("purchasedAvatars");
            if (avatarsSection.style.display === "none") {
                avatarsSection.style.display = "block";
                this.textContent = "{{ __('profile.purchased_avatars') }}";
            } else {
                avatarsSection.style.display = "none";
                this.textContent = "{{ __('profile.purchased_avatars') }}";
            }
        });
    </script>
</x-layout>
