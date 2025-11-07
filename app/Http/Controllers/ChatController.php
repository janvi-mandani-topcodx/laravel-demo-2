<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Chat;
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
//        $role = auth()->user()->getRoleNames()->first();
//        if($role == 'admin'){
//            $messages = Message::where('admin_id', auth()->id())->get();
//        }
//        else{
//            $messages = Message::where('user_id', auth()->id())->get();
            $messages = Chat::get();
//        }

        return view('chats.dashboard' , compact('messages'));

    }

    public function SearchUser(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $role = auth()->user()->getRoleNames()->first();
            if($role == 'admin' || $role == 'agent'){
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->where(function ($query) use ($search) {
                    $query->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })->get();
            }
//            else{
//                $users = User::whereHas('roles', function ($query) {
//                    $query->where('name', ['admin', 'agent']);
//                })->where(function ($query) use ($search) {
//                    $query->where('first_name', 'LIKE', '%' . $search . '%')
//                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
//                        ->orWhere('email', 'LIKE', '%' . $search . '%');
//                })->get();
//            }
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
//        if($user == 'admin' ){
//            $sendByAdmin = 1;
//        }
//        else{
//            $sendByAdmin = 0;
//        }
        $chatMessage = ChatMessage::create([
            'chat_id' => $input['message_id'],
            'user_type' => $user,
            'message' => $input['message'] ?? null,
            'attachment_name' => $input['attachment'] ?? null,
            'attachment_url' => $input['attachment_url'] ?? null,
        ]);

//        $messageReply = MessageReply::create([
//            'message_id' => $input['message_id'],
//            'send_by_admin' => $sendByAdmin,
//            'message' => $input['message'],
//        ]);
//        $chatUser = Message::find($input['message_id']);
        $chatUser = Chat::find($input['message_id']);

        return response()->json([
            'success' => true,
            'message_reply_id' => $chatMessage->id,
            'message' => $chatMessage->message,
            'created_at' => $chatMessage->created_at->diffForHumans(),
            'send_by_admin' => $chatMessage->user_type,
            'message_user' => $chatUser,
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
        if(auth()->user()->hasPermissionTo('create_permission')) {
            $messageReply =  MessageReply::find($input['message_id']);
            $messageReply->update([
                'message' => $input['message'],
            ]);

            return response()->json([
                'message' => $messageReply->message,
                'messageId' => $messageReply->id,
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $messageReply = MessageReply::find($id);
        if(auth()->user()->hasPermissionTo('delete_chat')) {
            $messageReply->delete();
        }
    }

    public function messageStore(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['user_id']);
        $authUser = auth()->user();

//        if($authUser->getRoleNames()->first() == 'admin'){
//            $adminId = $authUser->id;
//            $userId = $input['user_id'];
//        }
//        else{
//            $userId = $authUser->id;
//            $adminId = $input['user_id'];
//        }
        $userId = $authUser->id;
        $existingMessage = Chat::where('user_id', $userId)->first();
//        $existingMessage = Chat::where('user_id', $userId)
//            ->where('admin_id', $adminId)->orWhere('user_id', $adminId)->where('admin_id' , $userId)
//            ->first();

        if($existingMessage){
            return response()->json([
                'success' => true,
                'messageUser' => $user->full_name,
                'messageId' => $existingMessage->id,
            ]);
        }
        $message = Chat::create([
            'user_id' => $userId,
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
        $message = Chat::find($input['message_id']);
        $reply = '';
        if(auth()->user()->hasPermissionTo('view_chat')) {

            foreach ($message->chatMessages as $messageReply) {
                $align = $messageReply->user_type == 'user' ? 'justify-content-end' : 'justify-content-start';
//                $align = $messageReply->send_by_admin == 1 ? 'justify-content-end' : 'justify-content-start';
//                if ($messageReply->send_by_admin == 1 && auth()->user()->id == $message->admin_id ||
//                    $messageReply->send_by_admin == 0 && auth()->user()->id == $message->user_id) {
                    $display = '';
//                } else {
//                    $display = 'd-none';
//                }

                $update = auth()->user()->hasPermissionTo('update_chat') ? '' : 'd-none';
                $delete = auth()->user()->hasPermissionTo('delete_chat') ? '' : 'd-none';
                $updateOrDelete = auth()->user()->hasPermissionTo('delete_chat') ||  auth()->user()->hasPermissionTo('update_chat') ? '' : 'd-none';


                $reply .= '
                    <div class="d-flex ' . $align . '">
                        <div class="message  message-' . $messageReply->id . '" data-message-id="' . $message->id . '" data-message-reply-id="' . $messageReply->id . '" data-message="' . $messageReply->message . '" data-send-by-admin="' . $messageReply->send_by_admin . '">
                            <small>' . $messageReply->created_at->diffForHumans() . '</small>
                           <div class="d-flex">
                               <p class="one-message">' . $messageReply->message . '</p>
                               <div class="dropdown  ' . $display . '"" >
                                  <button class="dropdown-toggle '.$updateOrDelete.'"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                       </svg>
                                  </button>
                                  <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                       <li class="mb-2 '.$update.'">
                                          <input type="hidden" name="edit_message" value="' . $messageReply->id . '">
                                          <input type="hidden" name="message_id" value="' . $message->id . '">
                                          <span  class="edit-btn dropdown-item m-0" data-message = "' . $messageReply->message . '" data-id="' . $messageReply->id . '"> Edit </span>
                                      </li>
                                     <li>
                                        <span class="delete-btn dropdown-item '.$delete.'" data-id="' . $messageReply->id . '">Delete</span>
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
        else{
            return view('chats.dashboard');
        }
    }
}
