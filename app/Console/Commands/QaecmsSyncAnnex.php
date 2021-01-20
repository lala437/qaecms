<?php

namespace App\Console\Commands;

use App\Librarys\Services\Admin\AnnexService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class QaecmsSyncAnnex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncannex:exc {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync';

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
        $type = $this->argument('type');
        $annex = new AnnexService(Request::capture());
        $annex->CliSyncAnnex($type);
    }
}
