<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
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
        $role = auth()->user()->getRoleNames()->first();
//        if($role == 'admin'){
//            $messages = Message::where('admin_id', auth()->id())->get();
//        }
//        else{
//            $messages = Message::where('user_id', auth()->id())->get();

        $messages = Chat::where('status' , 0)->get();


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
        $media = $input['image']  ?? null;


//        $messageReply = MessageReply::create([
//            'message_id' => $input['message_id'],
//            'send_by_admin' => $sendByAdmin,
//            'message' => $input['message'],
//        ]);
//        $chatUser = Message::find($input['message_id']);
        $chatMessage = '';
        $chatUser = Chat::find($input['chat_id']);
        if ($media) {
            $imageUrls = [];
            foreach ($media as $file) {
                $chatMessage = ChatMessage::create([
                    'chat_id' => $input['chat_id'],
                    'user_type' => $user,
                    'message' => $input['message'] ?? null,
                ]);
                $media = $chatMessage->addMedia($file)->toMediaCollection('chat');
                $imageUrls[] = $media->getUrl();
//                $chatMessage->update([
//                    'attachment_name' => $media->file_name,
//                    'attachment_url' => $media->getUrl(),
//                ]);
            }
        }
        else{
            $chatMessage = ChatMessage::create([
                'chat_id' => $input['chat_id'],
                'user_type' => $user,
                'message' => $input['message'] ?? null,
            ]);
        }


//        if ($chatMessage->image_url){
//            foreach($chatMessage->image_url as $image){
//                $imageUrl[] = $image;
//            }
//        }
        event(new ChatEvent($chatMessage->message, $chatUser->user_id , $imageUrls , $chatMessage->created_at->diffForHumans() , auth()->user()->full_name , auth()->user()->image_url[0] ,  $chatMessage->id , $chatMessage->user_type));

//        broadcast(new ChatEvent($chatMessage->message  ,  auth()->user()));

        return response()->json([
            'success' => true,
            'chat_message_id' => $chatMessage->id,
            'message' => $chatMessage->message,
            'created_at' => $chatMessage->created_at->diffForHumans(),
            'user_type' => $chatMessage->user_type,
            'chat_id' => $chatUser->id,
            'auth_id' => auth()->id(),
            'attachment_url' => $imageUrls,
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $input = $request->all();
        if(auth()->user()->hasPermissionTo('update_permission')) {
            $messageReply =  ChatMessage::find($input['message_id']);
            $messageReply->update([
                'message' => $input['message'],
            ]);

            return response()->json([
                'message' => $messageReply->message,
                'messageId' => $messageReply->id,
            ]);
        }
    }

    public function destroy(string $id)
    {
        $messageReply = ChatMessage::find($id);
        if(auth()->user()->hasPermissionTo('delete_chat')) {
            $messageReply->delete();
            $deleteImg = $messageReply->getMedia('chat');
            if ($deleteImg) {
                foreach ($deleteImg as $img) {
                    $img->delete();
                }
            }
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
        $user = auth()->user();
        if($user->hasPermissionTo('view_chat')) {
            foreach ($message->chatMessages as $messageReply) {
                $align = $messageReply->user_type == 'user' ? 'justify-content-end' : 'justify-content-start';
                $display = $messageReply->user_type == 'user' ? 'd-none' : '';

                $imageClass = $messageReply->user_type != 'user' ? 'd-none' : '';

                $sender = User::role($messageReply->user_type)->first();
                $name = $messageReply->user_type == 'user' ? $sender->full_name : 'you';


                $update = $user->hasPermissionTo('update_chat') ? '' : 'd-none';
                $delete = $user->hasPermissionTo('delete_chat') ? '' : 'd-none';
                $updateOrDelete = $user->hasPermissionTo('delete_chat') || $user->hasPermissionTo('update_chat') ? '' : 'd-none';
                $imageShow = '';
                if ($messageReply->image_url) {
                    foreach ($messageReply->image_url as $image){
                        $imageShow  = '<img src = "'.$image.'" alt = "User Image" class="img-thumbnail mt-2" style = "max-width: 90px;" >';
                    }
                }
                $bgColor = $messageReply->user_type == 'user' ? 'lightgray' : 'beige';
                $messageShow = $messageReply->message ? '<p class="one-message ps-1 rounded" style="background-color: '.$bgColor.' ; height : 38px;">' . $messageReply->message . '</p>' : '';


                $reply .= '
                    <div class="d-flex  ' . $align . '">
                        <div class="message  message-' . $messageReply->id . '" data-message-id="' . $message->id . '" data-message-reply-id="' . $messageReply->id . '" data-message="' . $messageReply->message . '" data-send-by-admin="' . $messageReply->send_by_admin . '">

                            <div class="d-flex gap-1">
                                <div class="image rounded-circle '.$imageClass.'">
                                    <img src="'.$sender->image_url[0].'" class="rounded-circle" height="30" width="30">
                                </div>
                                <div class="full-name-show">
                                    <span style="font-size: 18px">'. $name .'</span>
                                </div>
                                <p class="text-secondary mt-1" >' . $messageReply->created_at->diffForHumans() . '</p>
                            </div>
                            '.($messageReply->message
                                ? $imageShow
                                : '').'
                           <div class="d-flex w-50 ms-4 py-2 my-1 rounded" >
                           '.($messageReply->message
                                ? $messageShow
                                : $imageShow).'
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
                                          <span  class="edit-btn dropdown-item m-0" data-message = "' . $messageReply->message . '" data-image ="'. $messageReply->attachment_name.'" data-id="' . $messageReply->id . '"> Edit </span>
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

    public function sendMessageAdminAgent(Request $request)
    {
        $input = $request->all();
        $media = $input['image'] ?? null;
        $chat = Chat::find($input['chat_id']);
        $chatMessage = '';
        if ($media) {
            $imageUrls = [];
            foreach ($media as $file) {
                $chatMessage = ChatMessage::create([
                    'chat_id' => $input['chat_id'],
                    'user_type' => 'user',
                    'message' => $input['message'] ?? null,
                ]);
                $media = $chatMessage->addMedia($file)->toMediaCollection('chat');
                $imageUrls[] = $media->getUrl();
//                $chatMessage->update([
//                    'attachment_name' => $media->file_name,
//                    'attachment_url' => $media->getUrl(),
//                ]);
            }
        }
        else{
            $chatMessage = ChatMessage::create([
                'chat_id' => $input['chat_id'],
                'user_type' => 'user',
                'message' => $input['message'] ?? null,
            ]);
        }

//        $imageUrl = '';
//        if ($chatMessage->image_url){
//            foreach($chatMessage->image_url as $image){
//                $imageUrl = $image;
//            }
//        }
        event(new ChatEvent($chatMessage->message, auth()->id() , $imageUrls , $chatMessage->created_at->diffForHumans() , $chat->user->full_name , $chat->user->image_url[0] ,  $chatMessage->id , $chatMessage->user_type));


        return response()->json([
            'message' => $chatMessage->message,
            'created_at' => $chatMessage->created_at->diffForHumans(),
            'image' => $imageUrls,
            'chat_message_id' => $chatMessage->id,
            'chat_id' => $chatMessage->chat_id,
        ]);
    }

    public function chatDelete(Request $request)
    {
        Chat::find($request->delete_id)->delete();
    }

    public function getArchiveChat()
    {
        $chats = Chat::where('status' , 1)->get();
        $html = '';
        foreach ($chats as $chat){
            $html .= '
                <div class="pt-2 user-message-data border-bottom chat-user-'.$chat->id.'" data-name="'.$chat->user->full_name.'" data-message-id="'.$chat->id.'" data-email="'.$chat->user->email.'">
                    <div class="row">
                        <div class="col-3">
                            <img src="'.$chat->user->image_url[0].'" width="50" height="50" class="rounded-circle">
                        </div>
                        <div class="col-4 d-flex flex-wrap" style="font-size: 17px">
                            '.$chat->user->full_name.'
                            <p class="text-secondary">
                                '.$chat->chatMessages->last()->attachment_name.'
                            </p>
                        </div>
                        <div class="col text-secondary text-end">
                            '.$chat->chatMessages->last()->created_at->diffForHumans().'
                            <button class="dropdown-toggle "  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                <li class="mb-2">
                                    <span class="unArchive-chat-btn dropdown-item m-0"  data-id="'.$chat->id.'"> Unarchive Chat </span>
                                </li>
                                <li>
                                    <span class="delete-chat-btn dropdown-item " data-id="'.$chat->id.'">Delete</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            ';
        }
        return response()->json([
            'success' => true,
            'chats' => $html,
        ]);
    }

    public function getActiveChat()
    {
        $chats = Chat::where('status' , 0)->get();
        $html = '';
        foreach ($chats as $chat){
            $html .= '
                <div class="pt-2 user-message-data border-bottom chat-user-'.$chat->id.'"  data-name="'.$chat->user->full_name.'" data-message-id="'.$chat->id.'" data-email="'.$chat->user->email.'">
                    <div class="row">
                        <div class="col-3">
                            <img src="'.$chat->user->image_url[0].'" width="50" height="50" class="rounded-circle">
                        </div>
                        <div class="col-4 d-flex flex-wrap" style="font-size: 17px">
                            '.$chat->user->full_name.'
                            <p class="text-secondary">
                                '.$chat->chatMessages->last()->attachment_name.'
                            </p>
                        </div>
                        <div class="col text-secondary text-end">
                            '.$chat->chatMessages->last()->created_at->diffForHumans().'
                            <button class="dropdown-toggle "  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                <li class="mb-2">
                                    <span class="archive-chat-btn dropdown-item m-0"  data-id="'.$chat->id.'"> Archive Chat </span>
                                </li>
                                <li>
                                    <span class="delete-chat-btn dropdown-item " data-id="'.$chat->id.'">Delete</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            ';
        }
        return response()->json([
            'success' => true,
            'chats' => $html,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $input = $request->all();
        $chat  = Chat::find($input['chat_id']);
        $chat->status = $input['status'];
        $chat->save();
        return response()->json([
            'success' => true,
        ]);
    }

    public function allUserMessageGet(Request $request)
    {
        $input = $request->all();
        $chat = Chat::find($input['chat_id']);
        $html = '';
        $html .= '<div class="message-chat message-' . $chat->id . '"  data-chat="' . $chat->id . '">';

        foreach ($chat->chatMessages as $chatMessage){
            $sender = User::role($chatMessage->user_type)->first();
            $userType = $chatMessage->user_type == 'user' ? 'flex-row-reverse' : '';
            $userAlign = $chatMessage->user_type == 'user' ? 'align-items-end' : '';
            $notShowImage = $chatMessage->user_type == 'user' ? 'd-none' : '';
            $userName = $chatMessage->user_type == 'user' ? 'you' : $sender->full_name;
            $bgColor = $chatMessage->user_type == 'user' ? 'lightgray' : 'beige';
            $display = $chatMessage->user_type == 'user' ? '' : 'd-none';

            $html .= '<div class="d-flex flex-column single-message-div-'.$chatMessage->id.'  mb-2 '.$userAlign.'" >
                        <div class="d-flex gap-1 '.$userType.'" >
                            <div class="image '.$notShowImage.'" >
                                <img src = "'.$sender->image_url[0].'" width = "30" height = "30" class="'.$notShowImage.'" >
                            </div>
                            <div class="full-name" >'.$userName.'</div>
                            <div class="time text-secondary pt-1" style = "font-size: 13px" > '.$chatMessage->created_at->diffForHumans().' </div>
                        </div>
                        <div class="messages w-50 ms-4 py-2 rounded d-flex" style = "background-color: '.$bgColor.' " >
                        <div class="ps-2 text-align-start one-message" >'.$chatMessage->message.'</div >';

            if ($chatMessage->image_url)
            {
                foreach ($chatMessage->image_url as $image)
                {
                    $html .= ' <img src = "'.$image.'" alt = "User Image" class="img-thumbnail mt-2" style = "max-width: 150px;" >';
                }
            }
            $html .= '
                   <div class="dropdown '.$display.'" >
                        <button class="dropdown-toggle "  type = "button" id = "dropdownMenuButton" data - toggle = "dropdown" aria - haspopup = "true" aria - expanded = "false" >
                            <svg  xmlns = "http://www.w3.org/2000/svg" width = "16" height = "16" fill = "currentColor" class="bi bi-three-dots-vertical" viewBox = "0 0 16 16" >
                                <path d = "M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                            </svg >
                        </button >
                        <ul class="dropdown-menu" style = "top: 3px; left: -98px;" >
                            <li class="mb-2 " >
                                <input type = "hidden" name = "edit_message" value = "'.$chatMessage->id.'" >
                                <input type = "hidden" name = "message_id" value = "'.$chat->id.'" >
                                <span  class="edit-btn dropdown-item m-0" data-message = "{{$chatMessage->message}}" data-id = "'.$chatMessage->id.'" > Edit </span >
                            </li >
                            <li >
                                <span class="delete-btn dropdown-item" data-id = "'.$chatMessage->id.'" > Delete</span >
                            </li >
                        </ul >
                    </div >
                </div >
            </div >
           </div >';
        }
        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }


}
