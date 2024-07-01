<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class ReportService {
    public static function postsByUser($userId){
        return Post::where('userId', $userId)->get();
    }

    public static function postsMostLikes($limit=1){
        $query = Post::withCount('likes')->orderBy('likes_count', 'desc')->limit($limit)->get();

        return $query;
    }

    public static function postsByTag($tagId){
        return Post::whereHas('tags', function($query) use ($tagId){
            $query->where('tag_id', $tagId);
        })->get();
    }

    public static function postsByUserAndTag($userId, $tagId){
        return Post::where('userId', $userId)->whereHas('tags', function($query) use ($tagId){
            $query->where('tag_id', $tagId);
        })->get();
    }

    public static function userActivity($userId){
        return User::withCount('posts')->withCount('likes')->where('id', $userId)->get();
    }

    public static function recentPosts($limit =1, $page=1){
        return Post::orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);
    }
}
