<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreditRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\CreditLog;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

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
                ->editColumn('gender' , function ($user) {
                    return ucfirst($user->gender);
                })
                ->make(true);
        }
        return view('users.index', compact('user'));

    }


    public function create()
    {
        $roles = Role::all();
        if(auth()->user()->hasPermissionTo('create_user')){
            return view('users.create' , compact('roles'));
        }
        else{
            return view('users.index');
        }
    }


    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $this->userRepo->store($input);

        return redirect()->route('user.index');
    }


    public function edit(string $id)
    {

        $user = User::find($id);
        $roles = Role::all();
        $credits = CreditLog::where('user_id', $id)->orderByDesc('id')->get();
        $currentCredit = $credits->first();
        if(auth()->user()->hasPermissionTo('update_user')) {
            return view('users.edit', compact('user', 'roles', 'credits', 'currentCredit'));
        }
        else{
            return view('users.index');
        }
    }


    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        $input = $request->all();
        $this->userRepo->update($input, $user);
        return redirect()->route('user.index');
    }


    public function destroy(string $id)
    {
        $user = User::find($id);
        if(auth()->user()->hasPermissionTo('delete_user')){
            $user->delete();
            $deleteImg = $user->getMedia('user');
            if ($deleteImg) {
                foreach ($deleteImg as $img) {
                    $img->delete();
                }
            }
        }
        else{
            return view('users.index');
        }
    }

    public function creditAdd(CreditRequest $request)
    {
        $user = auth()->user();
        $input = $request->all();
        if(auth()->user()->hasPermissionTo('create_credit')) {

            $creditLog = CreditLog::create([
                'user_id' => $user->id,
                'amount' => $input['amount'],
                'previous_balance' => $user->credit,
                'new_balance' => $user->credit + $input['amount'],
                'reason' => $input['reason'],
            ]);

            $user->credit = $user->credit + $input['amount'];
            $user->save();

            return response()->json([
                'created_at' => $creditLog->created_at,
                'credit_amount' => $creditLog->amount,
                'previous_balance' => $creditLog->previous_balance,
                'new_balance' => $creditLog->new_balance,
                'reason' => $creditLog->reason,
            ]);
        }
    }
}
