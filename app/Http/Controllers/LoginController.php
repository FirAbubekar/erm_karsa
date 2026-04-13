<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Custom Query based on user request:
        // select count(user.password) from user where user.id_user=aes_encrypt('$usere','nur') and user.password=aes_encrypt('$passworde','windi')
        
        try {
            $result = DB::select("SELECT COUNT(password) as total FROM user WHERE id_user = AES_ENCRYPT(?, 'nur') AND password = AES_ENCRYPT(?, 'windi')", [
                $username,
                $password
            ]);

            $count = $result[0]->total ?? 0;

            if ($count > 0) {
                // Manually start session
                Session::put('user_id', $username);
                Session::put('is_logged_in', true);

                return redirect()->intended('/dashboard');
            }

            return back()->withErrors([
                'login_error' => 'Username atau Password salah.',
            ])->withInput($request->only('username'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'login_error' => 'Terjadi kesalahan sistem atau database belum terhubung: ' . $e->getMessage(),
            ])->withInput($request->only('username'));
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
