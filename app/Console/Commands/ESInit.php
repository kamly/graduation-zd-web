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
    protected $description = 'init elasticsearch a2c for post';

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
        $this->info('============= 开始创建posts类型 =============');

        $client = new Client();
        $url = config('scout.elasticsearch.hosts')[0] . '/' . config('scout.elasticsearch.index');
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
                    'posts' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'title' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword'
                                    ]
                                ]
                            ],
                            'content' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
         $client->put($url, $param);
        $this->info('============= 创建posts类型成功 =============');
    }
}
