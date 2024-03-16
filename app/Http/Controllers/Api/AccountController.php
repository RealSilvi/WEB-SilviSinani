<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AccountResource;
use App\Http\Resources\AnonymousResourceCollection;
use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\QueryBuilder\QueryBuilder;

class AccountController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request, User $user): AnonymousResourceCollection
    {
        $accounts = QueryBuilder::for(Account::class, $request)
            ->where('user_id', $user->id)
            ->allowedIncludes(['chats.messages'])
            ->defaultSort('name')
            ->allowedSorts('id')
            ->paginate($request->get('perPage', 15));


        return AccountResource::collection($accounts);
    }

    public function show(Request $request, User $user, Account $account): AccountResource
    {
        $member = QueryBuilder::for(Account::class, $request)
            ->where('user_id', $user->id)
            ->allowedIncludes(['chats.messages'])
            ->findOrFail($account->id);

        assert($member instanceof Account);

        return AccountResource::make($member);
    }

}
