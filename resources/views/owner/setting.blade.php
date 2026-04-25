<x-ownerlayout>
        <x-slot name="title">Exam Manager | Setting Page </x-slot>
   <x-ownersidebar />
    
   <x-decomentcontentarea>
       
    <div class="container">
           <div class="d-flex align-items-center justify-content-evently mt-2 mb-4 border-bottom">
              <h4 style="font-size:16px;color:black;" class="mb-3 fw-bold">Setting Page</h4>
        </div>


        <div class="row">
             {{-- Profile Information starts here--}}
        <div class="col-lg-7 col-md-7 col-sm-12 mt-3">
            <div x-data="{ loading: false }" class="card border shadow-sm">
                <div class="card-header">
                    <h2 class="h6 mb-0 text-black " style='font-size:15px;' >Update Profile Information</h2>
                </div>

                <div class="card-body">
                    @if (session('status') === 'profile-information-updated')
                        <div class="bg-success-light p-2 small"
                        x-data="{ show: true }" x-init="setTimeout(() => {
                                    show = false;
                                    $wire.examMessage = null;
                                }, 3000)" x-show="show">
                            Profile information updated successfully.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user-profile-information.update') }}" x-on:submit="loading = true">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">
                                Name
                            </label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ auth()->user()->name }}"
                                Placeholder="Enter Name"
                                style='font-size:14px;'
                            >
                            @error('name','updateProfileInformation')
                            <span class='text-danger small'>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">
                                Email Address
                            </label>
                            <input
                                type="email"
                                name='email'
                                class="form-control  mb-2"
                                value="{{ auth()->user()->email }}"
                                style='font-size:14px;'
                            >
                            @error('email','updateProfileInformation')
                            <span class='text-danger small'>
                                {{ $message }}
                            </span>
                            @enderror
                            <p class="bg-warning-lights p-2 small">
                                Please note that you can update your email address at any time; however, the new address must be verified before the change takes effect.
                            </p>
                        </div>

                        <button type="submit" :disabled="loading" class="btn btn-primary btn-sm fw-bold shadow-0 text-capitalize" style='font-size:13px;'>
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" x-show="loading"></span>
                            <span x-text="loading ? 'Updating...' : 'Update Profile Information'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
             {{-- Profile Information ends here--}}

              {{-- Password  Update Starts Here --}}
        <div class="col-lg-7 col-md-7 col-sm-12 mt-4">
            <div x-data="{ loading: false }" class="card border shadow-sm">
                <div class="card-header">
                    <h2 class="h6 mb-0 text-black" style='font-size:15px;'>Update Password</h2>
                </div>

                <div class="card-body">
                    @if (session('status') === 'password-updated')
                        <div class="bg-success-light  p-2 mb-2 mt-2"
                        x-data="{ show: true }" x-init="setTimeout(() => {
                                    show = false;
                                    $wire.examMessage = null;
                                }, 3000)" x-show="show">
                            Password updated successfully.
                        </div>
                    @endif
                        <ul>
                            <li>
                                 <small class="form-text text-muted">
                                New Password must be minimum 8 characters.
                            </small>
                            </li>
                        </ul>
                    <form method="POST" action="{{ route('user-password.update') }}" x-on:submit="loading = true">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-dark">
                                Current Password
                            </label>
                            <input
                                type="password"
                                name="current_password"
                                autocomplete="current-password"
                                class="form-control"
                            Placeholder="Enter Current Password"
                            style='font-size:14px;'>
                            @error('current_password', 'updatePassword')
                                <p class="text-danger small">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-dark">
                                New Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                class="form-control"
                                Placeholder="Enter New Password"
                                style='font-size:14px;'
                            >
                            @error('password', 'updatePassword')
                                <p class="text-danger small">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">
                                Confirm New Password
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                class="form-control"
                                Placeholder="Confrim New Password"
                                style='font-size:14px;'
                            >
                        </div>

                        <button type="submit" :disabled="loading" class="btn btn-primary btn-sm fw-bold shadow-0 text-capitalize" style='font-size:13px;'>
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" x-show="loading"></span>
                            <span x-text="loading ? 'Updating...' : 'Update Password'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
                      {{-- Password  Update Ends Here --}}

        </div>
        <!-- row ends here-->
    </div>
    </x-decomentcontentarea>


</x-ownerlayout>



