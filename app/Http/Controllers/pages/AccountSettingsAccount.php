<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsAccount extends Controller
{
  public function index()
  {
    return view(
      'content.pages.pages-account-settings-account',
      [
        'user' => auth()->user()
      ]
    );
  }


  public function update(Request $request)
  {
    $user = auth()->user();
    $validated = $request->only('name', 'password');

    // Only update the password if it's provided
    if ($request->filled('password')) {
      $validated['password'] = bcrypt($validated['password']); // Hash the password
    } else {
      unset($validated['password']); // Remove password from the validated array if not provided
    }
    $user->update($validated);

    return redirect()->route('account.profile')
      ->with('success', 'Profile updated successfully');
  }
}
