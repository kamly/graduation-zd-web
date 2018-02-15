<?php

namespace App\Models;


class Comment extends Base
{
    // 评论所属文章
    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id', 'id');
    }

    // 评论所属的用户
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
