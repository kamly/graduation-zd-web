<?php
/**
 * Created by PhpStorm.
 * User: lijiaming
 * Date: 2018/2/23
 * Time: 下午9:14
 */

namespace App\Jobs;

use App\Models\Post;
use App\Models\Comment;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ElasticsearchException;

class ElasticsearchTodo
{
    protected $client = null;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    public function index($type, $action, $id)
    {
        $result = false;
        if ($action == 'save') {
            $result = $this->_store_posts_comments($type, $id);
        } elseif ($action == 'delete') {
            $result = $this->_delete_posts_comments($type, $id);
        }
        return $result;
    }


    // 存储 posts_comments
    private function _store_posts_comments($type, $id)
    {
        $payload = null;
        if ($type == 'post') {
            $post = (new Post)->where(['id' => $id])->first();
            $payload['class_name'] = 'post';
            $payload['post_id'] = $id;
            $payload['comment_id'] = 0;
            $payload['title'] = $post->title;
            $payload['description'] = $post->des;
            $payload['content'] = $post->content;
            $payload['status'] = $post->status;
            $payload['created_at'] = (new \DateTime($post->created_at))->format('Y-m-d H:i:s');
            $payload['updated_at'] = (new \DateTime($post->updated_at))->format('Y-m-d H:i:s');
        } elseif ($type == 'comment') {
            $comment = (new Comment)->where(['id' => $id])->first();
            $payload['class_name'] = 'comment';
            $payload['post_id'] = $comment->post_id;
            $payload['comment_id'] = $id;
            $payload['title'] = '';
            $payload['description'] = '';
            $payload['content'] = $comment->content;
            $payload['status'] = 1;
            $payload['created_at'] = (new \DateTime($comment->created_at))->format('Y-m-d H:i:s');
            $payload['updated_at'] = (new \DateTime($comment->updated_at))->format('Y-m-d H:i:s');
        }
        if (!$payload) {
            return false;
        }
        $params['index'] = 'posts_comments';
        $params['type'] = 'post_comment';
        $params['id'] = "{$type}_{$id}";
        $params['body'] = $payload;
        try {
            $this->client->index($params);
            return true;
        } catch (ElasticsearchException $e) {
            return false;
        }
    }

    // 删除 posts_comments
    private function _delete_posts_comments($type, $id)
    {

        if ($type == 'post') {
            // 删除帖子，需要把回复都删除  因为设置了外键会自动删除，所以我们只要处理es的数据就可以了
            $params = [
                'index' => 'posts_comments',
                'type' => 'post_comment',
                'body' => [
                    'query' => [
                        'term' => [
                            'post_id' => $id
                        ]
                    ],
                    'script' => [
                        'inline' => 'ctx._source.status=-2;'
                    ]
                ]
            ];

            try {
                $this->client->updateByQuery($params);
                return true;
            } catch (ElasticsearchException $e) {
                return false;
            }

        } elseif ($type == 'comment') {
            $params = [
                'id' => "comment_{$id}",
                'index' => 'posts_comments',
                'type' => 'post_comment',
                'body' => [
                    'script' => [
                        'inline' => 'ctx._source.status=-2;'
                    ]
                ]
            ];
            try {
                $this->client->update($params);
                return true;
            } catch (ElasticsearchException $e) {
                return false;
            }
        }
    }
}