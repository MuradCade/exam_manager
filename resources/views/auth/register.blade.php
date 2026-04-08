<x-layout>
    <x-slot name="title">Exam Manager | Register</x-slot>
   <x-navbar />
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-4 mx-auto">
                <div class="card">
                    <h4 class="card-header small" style='font-size:17px;color:black;'>
                        Create Account
                    </h4>
                    <div class="card-body">

                        <form action="{{ route('register') }}" method='POST'>
                            @csrf
                            <div class="form-group">
                                <lable class="form-label mb-2" style='font-size:14px;color:black;'>Fullname</lable>
                                <input type='text' class='form-control' name='name' placeholder="Enter Fullname" style='font-size:15px;'
                                value='{{ old('name') }}'/>
                                @error('name')
                                    <p class='text-danger mt-1' style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <lable class="form-label mb-2" style='font-size:14px;color:black;'>Email</lable>
                                <input type='email' class='form-control' name='email' placeholder="Enter Email" style='font-size:15px;'
                                value='{{ old('email') }}'/>
                                   @error('email')
                                    <p class='text-danger mt-1' style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <lable class="form-label mb-2" style='font-size:14px;color:black;'>Password</lable>
                                <input type='password' class='form-control' name='password' placeholder="Enter Password" style='font-size:15px;'
                                />
                                   @error('password')
                                    <p class='text-danger mt-1'  style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <lable class="form-label mb-2" style='font-size:14px;color:black;'>Confirm Password</lable>
                                <input type='password' class='form-control' name='password_confirmation' placeholder="Confirm Password" style='font-size:15px;'/>
                                   @error('confirmed')
                                    <p class='text-danger mt-1'  style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                            <button class='btn btn-primary btn-sm fw-bold text-white mt-3 shadow-0 text-capitalize' style='font-size:15px;'>Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>