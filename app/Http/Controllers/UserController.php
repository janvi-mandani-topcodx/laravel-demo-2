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
    private $User;

    public function __construct(UserRepository $userRepository)
    {
        $this->User = $userRepository;
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
                ->make(true);
        }
        return view('users.index', compact('user'));

    }


    public function create()
    {
        $roles = Role::all();
        return view('users.create' , compact('roles'));
    }


    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $this->UserRepo->store($input);

        return redirect()->route('user.index');
    }


    public function edit(string $id)
    {

        $user = User::find($id);
        $roles = Role::all();
        $credits = CreditLog::where('user_id', $id)->orderByDesc('id')->get();
        $currentCredit = $credits->first();
        return view('users.edit', compact('user' , 'roles' , 'credits' , 'currentCredit'));
    }


    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        $input = $request->all();
        $this->UserRepo->update($input, $user);
        return redirect()->route('user.index');
    }


    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function creditAdd(CreditRequest $request)
    {
        $user = auth()->user();
        $input = $request->all();

        $creditLog = CreditLog::create([
           'user_id' => $user->id,
            'amount' => $input['amount'],
            'previous_balance' => $user->credit ,
            'new_balance' => $user->credit +  $input['amount'],
            'reason' => $input['reason'],
        ]);

        $user->credit  = $user->credit +  $input['amount'];
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
