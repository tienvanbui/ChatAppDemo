<?php

namespace App\Helpers;

use App\Models\Conversation;
use App\Models\Message;

class ChatHelper
{
    public static function getCurrentConversationWithUser($userId)
    {
        $callable = function ($query) use ($userId) {
            $query->where('user_id', $userId);
        };

        $conversations = Conversation::whereHas('participants', $callable)
            ->where('creator_id', $userId)
            ->with('users:id,name,profile_photo_path')
            ->first();

        return $conversations;
    }

    public static function getSeenMessageId($conversation, $userId)
    {
        if (!empty($conversation)) {
            $conversation->loadMissing('participants');

            $participant = $conversation->participants()
                ->where('user_id', $userId)
                ->first();

            $seenMessageId = $participant->seen_message_id;
            if (!$seenMessageId) {
                $seenMessageId = Message::query()
                    ->where('conversation_id', $conversation->id)
                    ->oldest()
                    ->first()?->id;
            }
            return $seenMessageId;
        }
    }

    public static function getInitMessages($query, $seenMessageId, $conversationId)
    {
        $getOldMessages = 20;

        $limitTotalMessages = config('const.limiMessagePerPage');

        if ($seenMessageId) {
            $query->where('id', '>=', $seenMessageId)
                ->orderBy('id', 'ASC')
                ->limit($limitTotalMessages - $getOldMessages);

            $query->unionAll(
                Conversation::query()
                    ->where('conversation_id', $conversationId)
                    ->where('id', '<', $seenMessageId)
                    ->orderBy('id', 'DESC')
                    ->limit($getOldMessages)
            );
        }
        return $query->orderBy('id', 'DESC');
    }

    public static function getListConversation($userId)
    {
        $query = Conversation::query()
            ->join('participants', 'participants.conversation_id', '=', 'conversations.id')
            ->leftJoin('messages', function ($join) use ($userId) {
                $join->on('messages.conversation_id', '=', 'conversations.id');
                // ->where(function ($query) use ($userId) {
                //     $query->where('participants.user_id', $userId)
                //         ->where(function ($q) {
                //             $q->whereNull('participants.seen_message_id')
                //                 ->orWhere('messages.id', '>', 'participants.seen_message_id');
                //         });
                // })
                // ->where('messages.sender_id', '!=', $userId);
            })
            ->where(function ($query) use ($userId) {
                return $query->where('participants.user_id', $userId)
                    ->whereNotNull('participants.last_message_id');
            })
            ->orWhere(function ($query) use ($userId) {
                return $query->where('participants.user_id', $userId)
                    ->where('creator_id', $userId);
            })
            ->orderBy('participants.pinned_at', 'DESC')
            ->orderBy('participants.updated_at', 'DESC')
            ->selectRaw(
                '
                    DISTINCT conversations.*,
                    participants.updated_at AS last_read_at,
                    participants.pinned_at AS pinned_at,
                    COUNT(DISTINCT IF
                        (
                            messages.id IS NOT NULL
                            AND ( participants.seen_message_id IS NULL OR messages.id > participants.seen_message_id), 
                            messages.id, 
                            NULL
                        )
                    ) AS unread_messages
                '
            )
            ->groupBy('conversations.id', 'participants.pinned_at', 'participants.updated_at')
            ->with([
                'users:id,name,profile_photo_path',
                'participants',
                'participants.messageLast'
            ]);
        return $query;
    }
}
