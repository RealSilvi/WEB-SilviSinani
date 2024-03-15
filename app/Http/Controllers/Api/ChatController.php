<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AnonymousResourceCollection;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\QueryBuilder;

class ChatController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): AnonymousResourceCollection
    {
        $chats = QueryBuilder::for(Chat::class, $request)
            ->allowedIncludes(['members', 'messages'])
            ->defaultSort('updated_at')
            ->paginate($request->get('perPage', 15));

        return ChatResource::collection($chats);
    }

}
