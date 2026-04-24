<x-layout>
    <x-slot name="title">Exam Manager |  Reset Password</x-slot>
    <x-navbar />
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-4 mx-auto">
                <div class="card">
                    <h4 class="card-header small" style='font-size:16px; color:black;'>
                      Forget Password
                    </h4>
                    <div x-data="{ loading: false }" class="card-body">
                       
                        <form action="{{ route('password.update') }}" method='POST' x-on:submit="loading = true">
                            @csrf
                          
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="form-group mt-2">
                                <lable class="form-label" style='font-size:14px; color:black;'>Email</lable>
                                <input type='email' class='form-control' name='email' placeholder="Enter Email" value="{{ old('email', $request->email) }}"
                                style='font-size:14px; color:black;'/>
                                   @error('email')
                                    <p class='text-danger mt-1' style='font-size:14px;'style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>

                              <div class="form-group mt-2">
                                <lable class="form-label" style='font-size:14px; color:black;'>New Password</lable>
                                <input type='password' class='form-control' name='password' placeholder="Enter New Password" style='font-size:14px; color:black;'/>
                                   @error('password')
                                    <p class='text-danger mt-1' style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group mt-2">
                                <lable class="form-label" style='font-size:14px; color:black;'>Confirm Password</lable>
                                <input type='password' class='form-control' name='password_confirmation' placeholder="Confirm Password" style='font-size:14px; color:black;'/>
                                   @error('confirmed')
                                    <p class='text-danger mt-1' style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                          
                            <button 
                            class='btn btn-primary btn-sm fw-bold text-white mt-2 text-capitalize shadow-0' 
                            style='font-size:15px;'
                            :disabled="loading">
                            <span x-show="loading">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true">
                            </span></span>
                            Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

