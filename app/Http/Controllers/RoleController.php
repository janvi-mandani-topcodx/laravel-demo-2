<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $role = Role::all();
        if ($request->ajax()) {
            return DataTables::of(Role::get())
                ->make(true);
        }
        return view('roles.index', compact('role'));
    }


    public function create()
    {
        $permissions = Permission::all();
        $permissionDetails = [];
        foreach ($permissions as $permission) {
            $permissionDetails[] = [
                'id' => $permission->id,
                'name' => Str::of($permission->name)->replace('_', ' ')
            ];
        }
        return view('roles.create' , compact('permissionDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();
        $permissions = Permission::find($input['permission']);
        $role = Role::updateOrCreate([
            'name' => $input['name'],
            'guard_name' => 'web'
        ]);
        $role->syncPermissions($permissions);
       return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::with('permissions')->find($id);
        $permissions = Permission::all();
        $permissionDetails = [];

        foreach ($permissions as $permission) {
            $permissionDetails[] = [
                'id' => $permission->id,
                'name' => Str::of($permission->name)->replace('_', ' ')
            ];
        }
        return view('roles.edit', compact('role' , 'permissions' , 'permissionDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $input = $request->all();
        $role = Role::find($id);
        $permissions = Permission::find($input['permission']);
        $rolePermission = $role->update([
            'name' => $input['name'],
        ]);
        $rolePermission->syncPermissions($permissions);
        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();
    }
}
