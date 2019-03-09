<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Office;

class TestDatabase extends Command
{
    protected $signature = 'test:database';

    protected $description = 'DB Sanity check';

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
        $offices = Office::get();
        $this->info( json_encode($offices->toArray()) );
    }
}
