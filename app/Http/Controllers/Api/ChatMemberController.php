<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AnonymousResourceCollection;
use App\Http\Resources\ChatMemberResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\ChatMember;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\QueryBuilder;

class ChatMemberController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): AnonymousResourceCollection
    {
        $members = QueryBuilder::for(ChatMember::class, $request)
            ->allowedIncludes(['messages', 'chat'])
            ->defaultSort('name')
            ->paginate($request->get('perPage', 15));

        return ChatMemberResource::collection($members);
    }

}
