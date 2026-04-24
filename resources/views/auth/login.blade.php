<x-layout>
    <x-slot name="title">Login Page</x-slot>
    <x-navbar/>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-4 mx-auto">
                <div x-data="{ loading: false }" class="card">
                    <h4 class="card-header small" style='font-size:17px;color:black;'>
                        Login
                    </h4>
                    <div class="card-body">
                       
                        <form action="{{ route('login') }}" method='POST' x-on:submit.prevent="loading = true; $el.submit()">
                            @csrf
                          
                            <div class="form-group mt-2">
                                <lable class="form-label" style='font-size:14px;color:black;'>Email</lable>
                                <input type='email' class='form-control' name='email' placeholder="Enter Email" style='font-size:15px;'/>
                                   @error('email')
                                    <p class='text-danger mt-1'  style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group mt-3 mb-2">
                                <div class='d-flex justify-content-between'>
                                    <lable class="form-label" style='font-size:14px;color:black;'>Password</lable>
                                    <a href="{{ route('password.request') }}" class='small text-decoration-underline'>Forget password</a>
                                </div>
                                
                                <input type='password' class='form-control' name='password' placeholder="Enter Password" style='font-size:15px;'/>
                                   @error('password')
                                    <p class='text-danger mt-1'  style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                              <div class="mb-3 form-check mt-3">
                                <input type="checkbox" class="form-check-input mt-1" id="rememberMe" name="remember">
                                <label class="form-check-label" for="rememberMe" style='font-size:15px;color:black;'>Remember Me</label>
                            </div>
                            <button 
                            class='btn btn-primary btn-sm fw-bold text-white mt-2 shadow-0 text-capitalize' 
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
