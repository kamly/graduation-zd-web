<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class ESInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init elasticsearch zd';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 废用
        $this->info('============= 开始创建posts_comments类型 =============');

        $client = new Client();
        $url = config('scout.elasticsearch.hosts')[0] . '/' . 'posts_comments';
        // 查看这个索引是不是存在，如果存在就删除
        try {
            $response = $client->get($url);
            if ($response) {
                $client->delete($url);
            }
        } catch (ClientException $e) {
        }
        // 索引参数
        $param = [
            'json' => [
                'mappings' => [
                    'post_comment' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'class_name' => [
                                'type' => 'keyword',
                            ],
                            'post_id' => [
                                'type' => 'keyword',
                            ],
                            'comment_id' => [
                                'type' => 'keyword',
                            ],
                            'title' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'content' => [
                                'type' => 'text',
                            ],
                            'status' => [
                                'type' => 'integer', // -1 违法 -2 删除 0 未审查 1 已审查
                            ],
                            'created_at' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss',
                            ],
                            'updated_at' => [
                                'type' => 'date',
                                'format' => 'yyyy-MM-dd HH:mm:ss',
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $client->put($url, $param);
        $this->info('============= 创建posts_comments类型成功 =============');
    }
}
