<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
  public function index()
  {
    return view('content.user.list', [
      "users" => User::get()
    ]);
  }

  public function view($uuid)
  {
    return view('content.user.view', [
      'user' => User::findOrFail($uuid),
    ]);
  }

  public function create()
  {
    return view(
      'content.user.create',
      [
        "roles" => Role::get(),
        "user_role" => null,
      ]
    );
  }

  public function edit($uuid)
  {
    $user = User::with('roles')->findOrFail($uuid);
    $user_role = null;
    foreach ($user->roles as $role) {
      $user_role = $role;
    }
    return view('content.user.edit', [
      "user" => $user,
      "roles" => Role::get(),
      "user_role" => $user_role,
    ]);
  }

  public function update(UserUpdateRequest $request, $uuid)
  {
    $user = User::findOrFail($uuid);
    $validated = $request->validated();

    // Only update the password if it's provided
    if ($request->filled('password')) {
      $validated['password'] = bcrypt($validated['password']);
      $user->password = $validated['password']; // Hash the password
    } else {
      unset($validated['password']); // Remove password from the validated array if not provided
    }

    $role = Role::findById($validated['group']); // Get the role
    $user->roles()->detach(); // Remove existing roles
    $modelHasRoleOld = ModelHasRole::where('Key2', $user->uuid)->first();
    if ($modelHasRoleOld) {
      $modelHasRoleOld->role_uuid = $role->uuid;
      $modelHasRoleOld->save();
    } else {
      $modelHasRole = ModelHasRole::create([
        'role_uuid' => $role->uuid,
        'model_type' => 'App\Models\User',
        'model_uuid' => $user->uuid,
      ]);
    }

    $user->assignRole($role);

    $validated['is_active'] = $request->has('is_active') ? true : false;

    $validated['updated_at'] = Carbon::now();
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->is_active = $validated['is_active'];
    $user->updated_at = $validated['updated_at'];
    $user->save();

    return redirect()->route('account.user.edit', ['uuid' => $uuid])
      ->with('success', 'User updated successfully');
  }

  public function delete($uuid)
  {
    $user = User::findOrFail($uuid);
    $user->delete();

    return redirect()->route('account.user.list')
      ->with('success', 'User deleted successfully.');
  }

  public function store(UserStoreRequest $request)
  {
    $validated = $request->validated();

    $validated['is_active'] = $request->has('is_active') ? true : false;

    $user = User::create($validated);

    $role = Role::findById($validated['group']); // Get the role
    $user->roles()->detach(); // Remove existing roles
    $modelHasRoleOld = ModelHasRole::where('Key2', $user->uuid)->first();
    if ($modelHasRoleOld) {
      $modelHasRoleOld->role_uuid = $role->uuid;
      $modelHasRoleOld->save();
    } else {
      $modelHasRole = ModelHasRole::create([
        'role_uuid' => $role->uuid,
        'model_type' => 'App\Models\User',
        'model_uuid' => $user->uuid,
      ]);
    }

    if ($request->filled('password')) {
      $validated['password'] = bcrypt($validated['password']);
      $user->password = $validated['password']; // Hash the password
    } else {
      unset($validated['password']); // Remove password from the validated array if not provided
    }

    $user->assignRole($role);
    $user->save();

    return redirect()->route('account.user.list')
      ->with('success', 'User created successfully');
  }
}
