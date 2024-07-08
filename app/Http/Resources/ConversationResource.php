<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    private function getContentMessage($message)
    {
        if (!$message) {
            return '';
        }

        return $message->content;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = get_current_user_login() ?? $request->currentUser;
        $userId = $user->id;
        $participant = $this->participants->firstWhere('user_id', $userId);
        $message = $this->message ?? $participant->messageLast;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'message' => [
                'sender_id' => $message?->sender_id,
                'content' => $this->getContentMessage($message, $user),
                'created_at' => $message?->created_at,
            ],
            'type' => $this->type,
            'users' => get_conversation_users($this->users),
            'pinned_at' => $participant?->pinned_at ?? null,
            'unread_messages' => $this->unread_messages ?? 0,
            'last_read_at' => $this->last_read_at,
        ];
    }
}
