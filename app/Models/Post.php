<?php

namespace App\Models;

use Laravel\Scout\Searchable;

// 表 => posts
class Post extends Base
{
    // 可以搜索
    use Searchable;

    // 定义index（索引）里面的type
    public function searchableAs()
    {
        return "posts";
    }

    // 定义哪些字段可以搜索
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content
        ];
    }

    // 关联操作

    // 关联用户
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    // 评论模型
    public function comments()
    {
        return $this->hasMany('App\Models\Comment')->orderBy('created_at', 'desc');
    }

    // 赞 和用户表进行关联
    public function zan($user_id)
    {
        return $this->hasOne(\App\Models\Zan::class)->where('user_id', $user_id);
    }

    // 文章所有赞
    public function zans()
    {
        return $this->hasMany(\App\Models\Zan::class);
    }

    // 属于某个作者的文章
    public function scopeAuthorBy($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    // 专题模型
    public function postTopics()
    {
        return $this->hasMany(\App\Models\PostTopic::class, 'post_id', 'id');
    }

    // 不属于某个专题的文章
    public function scopeTopicNotBy($query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function ($q) use ($topic_id) {
            $q->where('topic_id', $topic_id);
        });
    }

    // 全局scope的方式
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope("avaiable", function ($builder) {
            $builder->whereIn('status', [0, 1]);
        });
    }
}
