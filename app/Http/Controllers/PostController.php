<?php

namespace App\Http\Controllers;


use App\Models\Post;
use App\Models\Profile;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __invoke(Request $request, Post $post): mixed
    {
        $authProfile = Profile::query()->where('nickname', $request->query('authProfile'))->first();

        if(!$authProfile){
            abort(404);
        }

        $user = $request->user();

        $profile = $post->profile;

        return view('pages.posts._post', [
            'user' => $user,
            'profile' => $profile,
            'authProfile' => $authProfile,
            'post' => $post,
        ]);
    }

}
