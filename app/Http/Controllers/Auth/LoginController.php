<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $auth;
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FirebaseAuth $auth)
    {
        $this->middleware("guest")->except("logout");
        // $this->auth = $auth;
        $this->auth =app("firebase.auth");
      // $auth = app("firebase.auth");
    }

    protected function login(Request $request)
{
    try {
        $auth = app("firebase.auth");
        $signInResult = $auth->signInWithEmailAndPassword(
            $request["email"],
            $request["password"]
        );

        // Ambil UID pengguna yang login
        $loginuid = $signInResult->firebaseUserId();
        Session::put('uid', $loginuid);

        // Ambil token akses dari respons autentikasi
        $accessToken = $signInResult->idToken();

        // Jika permintaan adalah API, kembalikan token JWT sebagai respons
        if ($request->wantsJson()) {
            return response()->json(['token' => $accessToken]);
        }

        // Jika bukan API, setel pengguna yang berhasil masuk ke dalam sesi Laravel
        $user = new User($signInResult->data());
        Auth::login($user);

        // Redirect ke halaman yang sesuai setelah berhasil login
        return redirect($this->redirectPath());
    } catch (\Kreait\Firebase\Exception\Auth\SignIn\FailedToSignIn $e) {
        throw ValidationException::withMessages([
            $this->username() => [trans("auth.failed")],
        ]);
    }
}
    protected function generateJWT($uid)
    {
        // Atur klaim token JWT, misalnya UID pengguna
        $payload = [
            'uid' => $uid,
            'exp' => time() + (60 * 60), // Token berlaku selama 1 jam (3600 detik)
        ];

        // Buat token JWT dengan menggunakan kunci rahasia yang disimpan di lingkungan Anda
        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return $jwt;
    }

    public function username()
    {
        return "email";
    }

        public function userInfo(Request $request)
        {
            // Periksa apakah pengguna sudah terotentikasi
            if (Auth::check()) {
                // Dapatkan pengguna yang terotentikasi
                $user = Auth::user();

                // Generate JWT Token
                $token = Auth::guard('api')->login($user);

                // Kembalikan respons JSON yang berisi token dan data pengguna
                return response()->json([
                    'token' => $token,
                    'user' => $user
                ]);
            }

            // Jika pengguna belum terotentikasi, kembalikan respons kesalahan
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    public function handleCallback(Request $request, $provider)
    {
        $socialTokenId = $request->input("social-login-tokenId", "");
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($socialTokenId);
            $user = new User();
            $user->displayName = $verifiedIdToken->getClaim("name");
            $user->email = $verifiedIdToken->getClaim("email");
            $user->localId = $verifiedIdToken->getClaim("user_id");
            Auth::login($user);
            return redirect($this->redirectPath());
        } catch (\InvalidArgumentException $e) {
            return redirect()->route("login");
        } catch (InvalidToken $e) {
            return redirect()->route("login");
        }
    }
}
