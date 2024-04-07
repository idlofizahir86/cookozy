
<x-guest-layout>
    <div class="px-5 py-5 p-lg-0 bg-surface-secondary">
        <div class="d-flex justify-content-center">
            <div class="col-lg-5 col-xl-4 p-12 p-xl-20 position-fixed start-0 top-0 h-screen overflow-y-hidden bg-primary d-none d-lg-flex flex-column">
                <!-- Logo -->
                <a class="d-block" href="{{ url('/') }}">
                    <img src="https://firebasestorage.googleapis.com/v0/b/cookozy-if4506.appspot.com/o/Assets%2FCookozy-svg.svg?alt=media&token=7a4164c2-2734-4928-8363-37af32ca3656" class="h-10" alt="...">
                </a>
                <!-- Title -->
                <div class="mt-32 mb-20">
                    <h1 class="ls-tight font-bolder display-6 text-white mb-5">
                        Bagikan resep luar biasa milikmu di sini.
                    </h1>
                    <p class="text-white-80">
                        Temukan juga resep sehat sesuai budget kamu di sini
                    </p>
                </div>
                <!-- Circle -->
                <div class="w-56 h-56 bg-orange-500 rounded-circle position-absolute bottom-0 end-20 transform translate-y-1/3"></div>
            </div>
            <div class="col-12 col-md-9 col-lg-7 offset-lg-5 border-left-lg min-h-lg-screen d-flex flex-column justify-content-center py-lg-16 px-lg-20 position-relative">
                <div class="row">
                    <div class="col-lg-10 col-md-9 col-xl-6 mx-auto ms-xl-0">
                        <div class="mt-10 mt-lg-5 mb-6 d-flex align-items-center d-lg-block">
                            <span class="d-inline-block d-lg-block h1 mb-lg-6 me-3">?</span>
                            <h1 class="ls-tight font-bolder h2">
                                {{ __('Nice to see you!') }}
                            </h1>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-5">
                                <label class="form-label" for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-label" for="password">{{ __('Password') }}</label>
                                    </div>
                                    <div class="mb-2">
                                        @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="small text-muted">Forgot password?</a>
                                        @endif
                                    </div>
                                </div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary w-full">
                                    {{ __('Sign in') }}
                                </button>
                            </div>
                        </form>
                        <div class="my-6">
                            <small>{{ __('Don\'t have an account') }}</small>
                            <a href="{{ route('register') }}" class="text-warning text-sm font-semibold">{{ __('Sign up') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-auth.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
      <script>
      // Initialize Firebase
      var firebaseConfig = {
        apiKey: "AIzaSyA1afDyJ2Uofz65rZFhaGjYLhWaJJsbI1w",
        authDomain: "cookozy-if4506.firebaseapp.com",
        databaseURL: "https://cookozy-if4506-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "cookozy-if4506",
        storageBucket: "cookozy-if4506.appspot.com",
        messagingSenderId: "880295839955",
        appId: "1:880295839955:web:d7dbc9092e551b57af6c26",
        measurementId: "G-M08VXRRCW6"
      };
      firebase.initializeApp(firebaseConfig);
      var facebookProvider = new firebase.auth.FacebookAuthProvider();
      var googleProvider = new firebase.auth.GoogleAuthProvider();
      var facebookCallbackLink = '/login/facebook/callback';
      var googleCallbackLink = '/login/google/callback';
      async function socialSignin(provider) {
        var socialProvider = null;
        if (provider == "facebook") {
          socialProvider = facebookProvider;
          document.getElementById('social-login-form').action = facebookCallbackLink;
        } else if (provider == "google") {
          socialProvider = googleProvider;
          document.getElementById('social-login-form').action = googleCallbackLink;
        } else {
          return;
        }
        firebase.auth().signInWithPopup(socialProvider).then(function(result) {
          result.user.getIdToken().then(function(result) {
            document.getElementById('social-login-tokenId').value = result;
            document.getElementById('social-login-form').submit();
          });
        }).catch(function(error) {
          // do error handling
          console.log(error);
        });
      }
      </script>
</x-guest-layout>