<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class SSOController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $driver = Socialite::driver('keycloak');
        $redirect = $driver->redirect();

        return $redirect;
    }


    public function callback(Request $request)
    {
        try {
            $driver = Socialite::driver('keycloak');
            $keycloakUser = $driver->user();
            $keycloakUsername = $keycloakUser->getNickname();
            $keycloakUserEmail = $keycloakUser->getEmail();

            $accessTokenResponseBody = $keycloakUser->accessTokenResponseBody;
            $accessToken = $accessTokenResponseBody['access_token'];
            $refreshToken = $accessTokenResponseBody['refresh_token'];
            dd($accessToken);


            /**
             * Login Admin
             */
            $admin = Admin::where('username', $keycloakUsername)->first();
            if ($admin) {
                if ($admin->status == 'active') {
                    Auth::guard('admin')->login($admin);
                    session([
                        'sso' => true,
                        'access_token' => $accessToken,
                        'refresh_token' => $refreshToken
                    ]);
                    $notification = __('Logged in successfully.');
                    $notification = ['messege' => $notification, 'alert-type' => 'success'];

                    return redirect()->route('admin.dashboard')->with($notification);
                } else {
                    return view('auth.message', [
                        'message' => 'Akun Anda Dalam Status Tidak Aktif',
                        'email' => $keycloakUserEmail,
                        'username' => $keycloakUsername,
                    ]);
                }
            }


            /**
             * Login User
             */
            $user = User::where('username', $keycloakUsername)->first();
            // check if user exists
            if ($user) {
                Auth::login($user);
                session([
                    'sso' => true,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken
                ]);

                // Redirect user to dashboard based on role
                $notification = __('Logged in successfully.');
                $notification = ['message' => $notification, 'alert-type' => 'success'];

                return redirect()->intended(
                    $user->role === 'instructor' ?
                        route('instructor.dashboard') : route('student.dashboard')
                )->with($notification);
            }

            return view('auth.message', [
                'message' => 'Akun Anda Tidak Terdaftar',
                'email' => $keycloakUserEmail,
                'username' => $keycloakUsername,
            ]);
        } catch (\Throwable $th) {
            report($th);
            abort(400, 'invalid request');
        }
    }

    /**
     * @OA\Get(
     *     path="/whoami",
     *     summary="Get current user information",
     *     tags={"Auth"},
     *     security={{"bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User information",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="instansi",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="PT. Example"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function whoami(Request $request)
    {
        $user = User::with('instansi:id,name')->where('id', $request->user()->id)->first();
        return $user;
    }
}
