<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ElasticsearchTodoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobData = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jobData)
    {
        //
        $this->jobData = $jobData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $type = $this->jobData['type'];
        $action = $this->jobData['action'];
        $id = $this->jobData['id'];
        (new ElasticsearchTodo)->index($type, $action, $id);
    }

}
