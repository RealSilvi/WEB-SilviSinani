<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateNewsInput;
use App\Actions\Profile\CreateNewsAction;
use App\Actions\Profile\CreateProfileFollowerAction;
use App\Actions\Profile\SeeAllNewsAction;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;
use Illuminate\Http\Request;

class NewsController
{

    public function index(Request $request, User $user, Profile $profile): AnonymousResourceCollection
    {
        $news = QueryBuilder::for(News::class, $request)
            ->allowedIncludes([
                'profile',
                'from',
            ])
            ->defaultSort('-created_at')
            ->where('profile_id', $profile->id)
            ->simplePaginate(10);

        return NewsResource::collection($news);
    }
    /**
     * @throws Throwable
     */
    public function store(User $user, Profile $profile, CreateNewsInput $input, CreateNewsAction $action): NewsResource
    {
        $news = $action->execute($user,$profile, $input);

        return new NewsResource($news);
    }

    /**
     * @throws Throwable
     */
    public function seeAll(User $user, Profile $profile, SeeAllNewsAction $action): \Illuminate\Http\Response
    {
       $action->execute($user,$profile);

        return response()->noContent();
    }

}
