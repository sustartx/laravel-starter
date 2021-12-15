<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SystemClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all caches';

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
     * @return int
     */
    public function handle()
    {
        /*
          clear-compiled       Remove the compiled class file
          auth:clear-resets    Flush expired password reset tokens
          cache:clear          Flush the application cache
          config:clear         Remove the configuration cache file
          event:clear          Clear all cached events and listeners
          optimize:clear       Remove the cached bootstrap files
          queue:clear          Delete all of the jobs from the specified queue
          route:clear          Remove the route cache file
          view:clear           Clear all compiled view files
        */

        $this->callSilently('route:clear');
        $this->callSilently('config:clear');
        $this->callSilently('view:clear');
        $this->callSilently('clear-compiled');
        $this->callSilently('optimize:clear');
//        $this->callSilently('auth:clear-resets');
        $this->callSilently('cache:clear');
        $this->callSilently('event:clear');
        $this->callSilently('queue:clear');
        return Command::SUCCESS;
    }
}
