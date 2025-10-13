<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $role = auth()->user()->getRoleNames()->first();
            if($role == 'admin'){
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->where(function ($query) use ($search) {
                    $query->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })->get();
            }
            else{
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->where(function ($query) use ($search) {
                    $query->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })->get();
            }
        }
        else{
            $users = [];
        }

        if ($request->ajax()) {
            $html = '';
            if(count($users) > 0){
                foreach ($users as $user) {
                    $html .= '<div id="selectUser" class="d-flex"  data-id="' . $user->id . '" data-name="' . $user->full_name . '">
                                 <div class="col-8 d-flex justify-content-end align-items-end px-0" style="padding-top: 20px;">
                                     <p>' . $user->full_name . '</p>
                                 </div>
                             </div>';
                }
            }
            else{
                $html .='<p>No admin found</p>';
            }
            return response()->json(['html' => $html]);
        }

        return view('chats.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function messageStore(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['user_id']);
        if(auth()->user()->roles->first()->name == 'admin'){
            $adminId = auth()->id();
            $userId = $input['user_id'];
        }
        else{
            $userId = auth()->id();
            $adminId = $input['user_id'];
        }

        Message::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
        ]);
        return response()->json([
            'success' => true,
            'messageUser' => $user->full_name,
        ]);
    }
}
