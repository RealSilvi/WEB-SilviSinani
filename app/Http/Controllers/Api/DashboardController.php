<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Enum\ProfileType;
use App\Exceptions\CannotDeleteDefaultProfileException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Exceptions\ProfileIsNotAUserProfileException;
use App\Exceptions\ToManyProfilesException;
use App\Exceptions\UserHasNotProfilesException;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class DashboardController
{

    public function show(Request $request, User $user, Profile $profile): AnonymousResourceCollection
    {
        $friendsPosts = Post::query()
            ->whereIn(
                'profile_id',
                $profile->following()->pluck('id')
            );

        $friendsPosts = QueryBuilder::for($friendsPosts, $request)
            ->allowedIncludes([
                'profile',
                'likes',
                'comments',
                'comments.profile',
                'comments.likes',
            ]);

        $randomAdvicePosts = Post::query()
            ->whereHas(
                'profile',
                function (Builder $p) use ($profile) {
                    return $p->where('type', $profile->type)
                        ->where('profile_id', '!=', $profile->id)
                        ->whereIn('id', $profile->following()->pluck('id'), not: true);
                }
            );

        $randomAdvicePosts = QueryBuilder::for($randomAdvicePosts, $request)
            ->allowedIncludes([
                'profile',
                'likes',
                'comments',
                'comments.profile',
                'comments.likes',
            ]);

        $postQuery = $friendsPosts->count() > 100 ? $friendsPosts : $friendsPosts->unionAll($randomAdvicePosts);

        $posts = $postQuery
            ->defaultSort('-created_at')
            ->simplePaginate(10);

        return PostResource::collection($posts);
    }

}
