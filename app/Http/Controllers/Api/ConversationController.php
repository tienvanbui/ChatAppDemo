<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ChatHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function getConversationWithPerson(string $personId)
    {
        $userId = get_current_user_login()?->id;
        try {
            $params = ['userId' => $userId, 'personId' => (int)$personId, 'type' => Conversation::TYPE_PRIVATE];

            $conversation = DB::table('conversations as c')
                ->distinct()
                ->select('c.*')
                ->join('participants as cu1', function ($join) use ($params) {
                    $join->on('c.id', '=', 'cu1.conversation_id')
                        ->where('cu1.user_id', '=', $params['userId']);
                })
                ->join('participants as cu2', function ($join) use ($params) {
                    $join->on('c.id', '=', 'cu2.conversation_id')
                        ->where('cu2.user_id', '=', $params['personId']);
                })
                ->join(DB::raw('(SELECT conversation_id FROM participants GROUP BY conversation_id HAVING COUNT(conversation_id) = 2) as valid_conversations'), function ($join) {
                    $join->on('c.id', '=', 'valid_conversations.conversation_id');
                })
                ->where('c.type', $params['type'])
                ->first();
            if (empty($conversation)) {
                $conversation = Conversation::create([
                    'type' => Conversation::TYPE_PRIVATE,
                    'creator_id' => $userId,
                ]);

                $conversation->users()->attach([$userId, $personId]);
            }

            return $this->sendSuccessResponse($conversation);
        } catch (\Exception $e) {
            $this->sendServerErrorResponse($e->getMessage());
        }
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = get_current_user_login();
        $userId = $user?->id;

        $conversations = ChatHelper::getListConversation($userId)
            ->paginate($request->input('limit', 10));

        return ConversationResource::collection($conversations)
            ->additional(['status_code' => 200, 'message' => __('Get list successfully.')]);
    }

    public function pin($id)
    {
        try {
            $conversation = Conversation::with('participants')->find($id);

            $conversation->participants()
                ->where('user_id', get_current_user_login()?->id)
                ->update(
                    [
                        'pinned_at' => now(),
                        'updated_at' => DB::raw('updated_at')
                    ],
                );

            $conversation->load('participants');

            return ConversationResource::make($conversation)
                ->additional(['status_code' => 200, 'message' => __('Pinned successfully.')]);
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($e->getMessage());
        }
    }

    public function unPin($id)
    {
        try {
            $conversation = Conversation::with('participants')->find($id);

            $conversation->participants()
                ->where('user_id', get_current_user_login()?->id)
                ->update(
                    [
                        'pinned_at' => null,
                        'updated_at' => now()
                    ],
                );

            $conversation->load('participants');

            return ConversationResource::make($conversation)
                ->additional(['status_code' => 200, 'message' => __('Unpined successfully.')]);
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($e->getMessage());
        }
    }
}
