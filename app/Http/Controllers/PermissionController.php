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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permission = Permission::all();
        if ($request->ajax()) {
            return DataTables::of(Permission::get())
                ->make(true);
        }
        return view('permissions.index', compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request)
    {
        $name = Str::of($request->name)->replace(' ', '_');
        Permission::create([
            'name' => $name,
            'guard_name' => 'web'
        ]);


        return redirect()->route('permission.index');
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
        $permission = Permission::find($id);
        return view('permissions.edit', compact('permission'));
    }


    public function update(UpdatePermissionRequest $request, string $id)
    {
        $name = Str::of($request->name)->replace(' ', '_');
        Permission::find($id)->update([
            'name' => $name,
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
