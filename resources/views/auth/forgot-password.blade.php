<x-layout>
    <x-slot name="title">Exam Manager | Forget Password</x-slot>
    <x-navbar />
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-4 mx-auto">
                <div class="card">
                    <h4 class="card-header small" style="font-size:16px;color:black;">
                      Forget Password
                    </h4>
                    <div class="card-body">
                       @if(session('status'))
                       <p class='bg-success-light  text-white fw-bold p-2'>{{session('status')}}</p>
                       @endif
                        <form action="{{ route('password.email') }}" method='POST'>
                            @csrf
                          
                            <div class="form-group ">
                                <lable class="form-label" style="font-size:14px;color:black;">Email</lable>
                                <input type='email' class='form-control' name='email' placeholder="Enter Email" style="font-size:14px;color:black;"/>
                                   @error('email')
                                    <p class='text-danger mt-1'  style='font-size:14px;'>{{$message}}</p>
                                @enderror
                            </div>
                          
                            <button class='btn btn-primary btn-sm fw-bold text-white mt-2 shadow-0 fw-bold text-capitalize' style='font-size:14px;'>Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
