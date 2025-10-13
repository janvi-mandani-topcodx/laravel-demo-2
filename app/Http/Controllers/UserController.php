<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $User;

    public function __construct(UserRepository $userRepository)
    {
        $this->User = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(auth()->check()){
            auth()->user()->email_verified_at  = now();
            auth()->user()->save();
        }
        $user = User::all();
        if ($request->ajax()) {
            return DataTables::of(User::get())
                ->editColumn('hobbies' , function ($user) {
                    return json_decode($user->hobbies);
                })
                ->make(true);
        }
        return view('users.index', compact('user'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create' , compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $this->User->store($input);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = User::find($id);
        $roles = Role::all();
        return view('users.edit', compact('user' , 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        $input = $request->all();
        $this->User->update($input, $user);
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
    }
}
