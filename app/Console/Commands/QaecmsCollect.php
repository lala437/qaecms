<?php

namespace App\Console\Commands;

use App\Librarys\Services\Method\MethodService;
use App\Model\QaecmsJob;
use Illuminate\Console\Command;

class QaecmsCollect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect:exc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $joblist = QaecmsJob::where(['status' => 1])->get();
        foreach ($joblist as $job) {
            $bindstatus = $job->bindstatus;
            if ($bindstatus) {
                $method = new MethodService($job);
                $method->Method();
                $job->update(['lasttime' => date("Y-m-d H:i:s", time())]);
            }
        }
    }
}
