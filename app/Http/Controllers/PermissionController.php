<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        $permission = Permission::all();
        if ($request->ajax()) {
            return DataTables::of(Permission::get())
                ->editColumn('name', function ($permission) {
                    $name = Str::of($permission->name)->replace('_', ' ');
                    return ucwords($name);
                })
                ->make(true);
        }
        return view('permissions.index', compact('permission'));
    }


    public function create()
    {
        return view('permissions.create');
    }


    public function store(CreatePermissionRequest $request)
    {
        Permission::create([
            'name' => Str::of($request->name)->replace(' ', '_'),
            'guard_name' => 'web'
        ]);


        return redirect()->route('permission.index');
    }


    public function edit(string $id)
    {
        $permission = Permission::find($id);
        return view('permissions.edit', compact('permission'));
    }


    public function update(UpdatePermissionRequest $request, string $id)
    {
        Permission::find($id)->update([
            'name' => Str::of($request->name)->replace(' ', '_'),
        ]);
        return redirect()->route('permission.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        $permission->delete();
    }
}
