<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMessageEvent;
use App\Helpers\ChatHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $conversationId)
    {
        /** @var User $user */
        $user = get_current_user_login();
        $userId = $user?->id;
        $conversation = ChatHelper::getCurrentConversationWithUser($userId, $conversationId);
        $seenMessageId = ChatHelper::getSeenMessageId($conversation, $userId);

        $limit = $request->input('limit', config('const.paginate'));

        $query = Message::query();
        $query->where('conversation_id', $conversationId)
            ->where('sender_id', $userId);

        $query = ChatHelper::getInitMessages($query, $seenMessageId, $conversationId);

        $messages = $query->with(['sender:id,name,profile_photo_path'])
            ->get()
            ->sortBy('id');

        return MessageResource::collection($messages)
            ->additional(['status_code' => 200, 'message' => 'Get list Successfully.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = get_current_user_login();
        $userId = $user?->id;
        $conversation = ChatHelper::getCurrentConversationWithUser($userId, $request->conversation_id);
        $dataMessage = [
            'conversation_id' => $request->conversation_id,
            'sender_id' => $userId,
            'content' => $request->content
        ];
        DB::beginTransaction();
        try {
            $message = Message::create($dataMessage);
            $message = MessageResource::make($message);
            $conversation->message = $message;
            broadcast(new SendMessageEvent($message));
            Notification::send($conversation->users, new NewMessageNotification($conversation, $message));

            DB::commit();
            
            return $this->sendSuccessResponse($message , __("Sent message successfully!") , 200);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $this->sendServerErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
}
