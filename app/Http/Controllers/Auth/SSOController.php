<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\Unor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SSOController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $driver = Socialite::driver('keycloak');
        $redirect = $driver->redirect();

        return $redirect;
    }


    public function callback(Request $request): RedirectResponse
    {
        $driver = Socialite::driver('keycloak');
        $keycloakUser = $driver->user();


        $accessTokenResponseBody = $keycloakUser->accessTokenResponseBody;
        $accessToken = $accessTokenResponseBody['access_token'];
        $refreshToken = $accessTokenResponseBody['refresh_token'];

        $user = User::where('sso_id', $keycloakUser->getId())->first();

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

        // ==================
        // if user not exists
        // ==================

        // check account from esurat
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $response = Http::withHeaders($headers)->get('https://esuratapi.bantulkab.go.id/api/v2/whoami');
        if ($response->status() != 200) {
            $notification = ['messege' => sprintf('Akun %s tidak terdaftar di esurat', $keycloakUser->getEmail()), 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $keycloakData = $response->json()['data'];

        // "code" => 200
        // "message" => ""
        // "data" => array:14 [▼
        //   "uuid" => "1HLy4m"
        //   "name" => "EMANUEL TEGAR WIBISONO, S.Kom."
        //   "namaJabatan" => "Pranata Komputer Pertama"
        //   "unorId" => 20299
        //   "plt" => false
        //   "type" => "jft"
        //   "has_plt" => false
        //   "unor_name" => "Bidang Tata Kelola E-Goverment, Aplikasi Informatika dan Statistik"
        //   "instansi_id" => 18
        //   "instansi_name" => "Dinas Komunikasi dan Informatika"
        //   "atasan_name" => "SRI MULYANI, SSTP,M.Eng"
        //   "atasan_jabatan" => "Kepala Bidang Tata Kelola E-Goverment, Aplikasi Informatika dan Statistik"
        //   "permissions" => array:3 [▼
        //     0 => "create_agenda"
        //     1 => "receive_disposisi"
        //     2 => "view_statistics"
        //   ]
        //   "unor_jenis_name" => "Bidang"
        // ]

        // create opd
        $opdID = $keycloakData['instansi_id'];
        $unorID = $keycloakData['unorId'];

        Opd::updateOrCreate([
            'id' => $opdID,
        ], [
            'name' => $keycloakData['instansi_name'],
        ]);

        Unor::updateOrCreate([
            'id' => $unorID,
        ], [
            'name' => $keycloakData['unor_name'],
            'opd_id' => $opdID,
        ]);

        $user = User::create([
            'unor_id' => $unorID,
            'name' => $keycloakData['name'],
            'email' => $keycloakUser->getEmail(),
            'sso_id' => $keycloakUser->getId(),
            'password' => bcrypt(Str::random(10)),
            'role' => 'student',
            'user_type' => $keycloakData['type'],
            'email_verified_at' => now(),
        ]);

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
}
