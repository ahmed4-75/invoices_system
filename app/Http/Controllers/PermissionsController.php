<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\PermissionResource;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id','name','email','created_at')->with(['roles:id,name','roles.permissions:id,name'])->get();
        $users = UserResource::collection($users)->resolve();

        $roles = Role::select('id','name')->orderBy('id', 'asc')->get();
        $roles = RoleResource::collection($roles)->resolve();
        return view('users_permissions.roles_permissions',compact('users','roles'));
    }

    /**
     * Selecting Permissions Related to one Role 
     */
    public function get_permissions(Request $request)
    {
        $roleIds = $request->role_ids; // array
        $permissions = Permission::whereHas('roles', function($q) use ($roleIds){
            $q->whereIn('role_id', $roleIds);
        })->select('id', 'name')->get();

        $permissions = PermissionResource::collection($permissions);
        return response()->json( $permissions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $isFirstUser = User::orderBy('created_at')->orderBy('id')->value('id') === $user->id;
        if($isFirstUser){
            return to_route('rolesAndPermissions.index')->withErrors([
                'error' => "You Can't UPDATE the Role Or the Permission of the FIRST USER in the system"
            ]);
        }
        $request->validate([
            'roles' =>'required|array',
            'roles.*' => 'exists:roles,id',
            'permissions' =>'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        session()->flash('edit','The Roles & Permissions has been Edited Successfully ✅');
        return to_route('rolesAndPermissions.index');
    }
}
