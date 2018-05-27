<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CustomerUser;
use App\User;
use App\Order;
use App\Http\Traits\TransferFile;


class GenerateFiles extends Command
{
    use TransferFile;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:files';

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

        $this->TransferOther();
        
        
    }
}