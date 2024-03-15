<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AnonymousResourceCollection;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\QueryBuilder;

class MessageController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): AnonymousResourceCollection
    {
        $messages = QueryBuilder::for(Message::class, $request)
            ->allowedIncludes(['chat', 'sender'])
            ->defaultSort('updated_at')
            ->paginate($request->get('perPage', 15));

        return MessageResource::collection($messages);
    }

}
