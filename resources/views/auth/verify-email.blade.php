<x-layout>
    <x-slot name="title">Exam Manager | Verify Email Page</x-slot>
    <x-navbar/>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-4 mx-auto">
                <div x-data="{ loading: false }" class="card">
                    <h4 class="card-header small" style="font-size:17px;color:black;">
                        Verify Email
                    </h4>
                    <div class="card-body">

                       @if (session('status') == 'verification-link-sent')
                        <p   class='bg-success-light p-2'>A new verification link has been sent to your email address.</p>
                    @endif

                    <p  style='color:black;'> Thanks for signing up, <strong>{{ auth()->user()->name }}</strong>! 
                    Before you can access your account, please verify your email address.</p>
                    <p style="color: #333; font-size: 16px; line-height: 1.5; margin-top: 20px;">
                    We’ve sent a verification link to <strong>{{ auth()->user()->email }}</strong>. 
                    Check your inbox and click the link to activate your account.
                    </p>

                    <p style="color: #555; font-size: 14px; margin-top: 30px;">
                    Didn’t receive the email? 
                    <form method="POST" action="{{ route('verification.send') }}" x-on:submit="loading = true">
                        @csrf
                        <button 
                        type="submit" 
                        class='btn btn-primary btn-sm shadow-0 text-capitalize fw-bold' 
                        style='font-size:15px;'
                        :disabled="loading">
                        <span x-show="loading">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true">
                        </span></span>
                        Resend Verification Email</button>
                    </form>
                </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
