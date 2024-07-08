<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ChatHelper;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function chatDetail(?string $id = null)
    {
        $currentUser = get_current_user_login();
        $conversationId = $id;

        $conversation = (object) [];
        $lastMessageSeen = (object) [];

        if ($conversationId == null) {
            $conversation = ChatHelper::getListConversation($currentUser->id)->latest()->first();
        } else {
            $conversation = Conversation::with('users:id,name,profile_photo_path')->whereId($conversationId)->first();
        }
        
        $seenMessageId = ChatHelper::getSeenMessageId($conversation, $currentUser->id);

        $lastMessageSeen = Message::find($seenMessageId) ?? (object) [];

        return Inertia::render('Chat/ChatDetail', [
            'conversation' => $conversation,
            'conversationIdParam' => $conversationId,
            'lastMessageSeen' =>  $lastMessageSeen,
        ]);
    }
}
