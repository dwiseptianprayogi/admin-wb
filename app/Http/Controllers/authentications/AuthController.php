<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login');
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');
    $credentials['Character02'] = $credentials['email'];
    unset($credentials['email']);
    // Attempt login
    if (Auth::attempt($credentials)) {
      // Check if the user is active
      $user = Auth::user();
      if (!$user->is_active) {
        Auth::logout(); // Log them out
        return redirect()->route('login')->with('error', 'Your account is not active.');
      }

      return redirect()->intended('/');
    }

    return redirect()->back()->with('error', 'Invalid Credentials.');
  }


  public function logout()
  {
    Auth::logout();
    return redirect('/auth/login')->with('success', 'You have been logged out successfully.');
  }
}
