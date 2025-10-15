<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageReply;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->getRoleNames()->first();
        if($role == 'admin'){
            $messages = Message::where('admin_id', auth()->id())->get();
        }
        else{
            $messages = Message::where('user_id', auth()->id())->get();
        }

        return view('chats.dashboard' , compact('messages'));

    }

    public function SearchUser(Request $request)
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
                                 <div class="col-8 d-flex justify-content-between align-items-end px-0 w-100" style="padding-top: 20px;">
                                     <span>' . $user->full_name . '</span>
                                     <span>' . $user->email . '</span>
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
        $input = $request->all();
        $user = auth()->user()->getRoleNames()->first();
        if($user == 'admin'){
            $sendByAdmin = 1;
        }
        else{
            $sendByAdmin = 0;
        }
        $messageReply = MessageReply::create([
            'message_id' => $input['message_id'],
            'send_by_admin' => $sendByAdmin,
            'message' => $input['message'],
        ]);
        $messageUser = Message::find($input['message_id']);
        return response()->json([
            'success' => true,
            'message_reply_id' => $messageReply->id,
            'message' => $messageReply->message,
            'created_at' => $messageReply->created_at->diffForHumans(),
            'send_by_admin' => $messageReply->send_by_admin,
            'message_user' => $messageUser,
            'auth_id' => auth()->id(),
        ]);
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
        $input = $request->all();
        $messageReply =  MessageReply::find($input['message_id']);
        $messageReply->update([
            'message' => $input['message'],
        ]);

        return response()->json([
            'message' => $messageReply->message,
            'messageId' => $messageReply->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $messageReply = MessageReply::find($id);
        $messageReply->delete();
    }

    public function messageStore(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['user_id']);
        if(auth()->user()->getRoleNames()->first() == 'admin'){
            $adminId = auth()->id();
            $userId = $input['user_id'];
        }
        else{
            $userId = auth()->id();
            $adminId = $input['user_id'];
        }

        $message = Message::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
        ]);
        return response()->json([
            'success' => true,
            'messageUser' => $user->full_name,
            'messageId' => $message->id,
        ]);
    }

    public function allMessageGet(Request $request)
    {
        $input = $request->all();
        $message = Message::find($input['message_id']);
        $reply = '';
        foreach ($message->messageReplies as $messageReply){
            $align = $messageReply->send_by_admin == 1 ? 'justify-content-end' : 'justify-content-start';
            if ($messageReply->send_by_admin == 1 && auth()->user()->id == $message->admin_id ||
                $messageReply->send_by_admin == 0 && auth()->user()->id == $message->user_id)
            {
                $display = '';
            }
            else {
                $display = 'd-none';
            }
            $reply .= '
                <div class="d-flex '.$align.'">
                    <div class="message message-'.$messageReply->id.'" data-message-id="'.$message->id.'" data-message-reply-id="'.$messageReply->id.'" data-message="'.$messageReply->message.'" data-send-by-admin="'.$messageReply->send_by_admin.'">
                        <small>'.$messageReply->created_at->diffForHumans().'</small>
                       <div class="d-flex">
                           <p class="one-message">'.$messageReply->message.'</p>
                           <div class="dropdown  '.$display.'"" >
                              <button class="dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                   </svg>
                              </button>
                              <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                   <li class="mb-2">
                                      <input type="hidden" name="edit_message" value="'. $messageReply->id .'">
                                      <input type="hidden" name="message_id" value="'.$message->id.'">
                                      <span  class="edit-btn dropdown-item m-0" data-message = "'.$messageReply->message.'" data-id="'.$messageReply->id.'"> Edit </span>
                                  </li>
                                 <li>
                                    <span class="delete-btn dropdown-item" data-id="'.$messageReply->id.'">Delete</span>
                                 </li>
                              </ul>
                           </div>
                       </div>
                    </div>
                </div>
            ';
        }
        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}
