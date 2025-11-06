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


    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();
        $role = Role::updateOrCreate([
            'name' => $input['name'],
            'guard_name' => 'web'
        ]);

        if(isset($input['permission']))
        {
            $permissions = Permission::find($input['permission']);
            $role->syncPermissions($permissions);
        }
        else{
            $role->syncPermissions([]);
        }

       return redirect()->route('role.index');
    }




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


    public function update(UpdateRoleRequest $request, string $id)
    {
        $input = $request->all();
        $role = Role::find($id);

        $role->update([
            'name' => $input['name'],
        ]);


        if(isset($input['permission']))
        {
            $permissions = Permission::find($input['permission']);
            $role->syncPermissions($permissions);
        }
        else{
            $role->syncPermissions([]);
        }
        return redirect()->route('role.index');
    }


    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();
    }
}
