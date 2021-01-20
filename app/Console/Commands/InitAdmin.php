<?php

namespace App\Console\Commands;

use App\Model\QaecmsAdmin;
use Illuminate\Console\Command;

class InitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init {user} {pass}';

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
        $user = $this->argument('user');
        $pass = $this->argument('pass');
        $res = QaecmsAdmin::where(['id'=>1])->update(['name'=>$user,'password'=>md5($pass)]);
        if($res){
            echo "初始化管理员账号密码成功\n";
            echo "新账号:{$user}\n";
            echo "新密码:{$pass}\n";
        }else{
            echo "初始化失败\n";
        }
    }
}
