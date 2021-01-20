<?php

namespace App\Console\Commands;

use App\Librarys\Services\Admin\AnnexService;
use App\Librarys\Services\Method\MethodService;
use App\Model\QaecmsCollectdata;
use App\Model\QaecmsJob;
use App\Model\QaecmsTask;
use Illuminate\Console\Command;

class QaecmsConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qaecms:console {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'QAECMS控制台';

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
        $action = $this->argument('action');
        $task =  QaecmsTask::where(['task'=>$action])->first();
        if($task&&$task->status==1){
            switch ($action){
                case "collect":
                    $this->call("collect:exc");
                    break;
                case "cleancache":
                    $this->call("qaecms-cache:clear");
                    break;
                case "syncsearch":
                    $this->call('scout:import',['model'=>"App\Model\QaecmsVideo"]);
                    break;
                case "cleandata":
                    QaecmsCollectdata::query()->delete();
                    break;
                case "syncannexvideo":
                    $this->call('syncannex:exc',['type'=>'video']);
                    break;
            }
            QaecmsTask::where(['task'=>$action])->update(['lasttime'=>date('Y-m-d H:i:s',time())]);
        }
    }

}
