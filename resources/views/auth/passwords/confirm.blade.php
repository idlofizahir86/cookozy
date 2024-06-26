@extends('layouts.app')

@section('content')
<x-guest-layout>
    <div class="px-5 py-5 p-lg-0 bg-surface-secondary">
        <div class="d-flex justify-content-center">
            <div class="col-lg-5 col-xl-4 p-12 p-xl-20 position-fixed start-0 top-0 h-screen overflow-y-hidden bg-primary d-none d-lg-flex flex-column">
                <!-- Logo -->
                <a class="d-block" href="#">
                    <img src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FCookozy-svg.svg?alt=media&token=7a4164c2-2734-4928-8363-37af32ca3656" class="h-10" alt="...">
                </a>
                <!-- Title -->
                <div class="mt-32 mb-20">
                    <h1 class="ls-tight font-bolder display-6 text-white mb-5">
                        Let’s build something amazing today.
                    </h1>
                    <p class="text-white-80">
                        Maybe some text here will help me see it better. Oh God. Oke, let’s do it then.
                    </p>
                </div>
                <!-- Circle -->
                <div class="w-56 h-56 bg-orange-500 rounded-circle position-absolute bottom-0 end-20 transform translate-y-1/3"></div>
            </div>
            <div class="col-12 col-md-9 col-lg-7 offset-lg-5 border-left-lg min-h-lg-screen d-flex flex-column justify-content-center py-lg-16 px-lg-20 position-relative">
                <div class="row">
                    <div class="col-lg-10 col-md-9 col-xl-6 mx-auto ms-xl-0">
                        <div class="mt-10 mt-lg-5 mb-6 d-flex align-items-center d-lg-block">
                            <h1 class="ls-tight font-bolder h2 mb-2">
                                {{ __('Confirm Password') }}
                            </h1>
                            <p>
                                {{ __('Please confirm your password before continuing.') }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-5">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary w-full">
                                    {{ __('Confirm password') }}
                                </button>
                            </div>
                        </form>
                        <div class="my-6">
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-warning text-sm font-semibold">Forgot password?</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
@endsection
