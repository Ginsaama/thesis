<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function showLoginForm()
    {
        return view('login'); // Assuming your login page is named login.blade.php
    }

    public function login(Request $request)
    {
        // Retrieving only 'username' and 'password' from the request
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admins')->attempt($credentials)) {
            // Authentication passed
            // Redirecting to the intended URL or '/part' if no intended URL is set
            return redirect()->intended('/dashboard');
        }
        // Authentication failed
        // Redirecting back with an error message
        return back()->withErrors(['username' => 'Invalid credentials']);
    }
}
