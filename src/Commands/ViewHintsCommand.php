<?php

namespace Laraning\Boost\Commands;

use Illuminate\Console\Command;

class ViewHintsCommand extends Command
{

    /**
     * Table headers.
     * @var array
     */
    protected $headers = ['Hint', 'Path'];

    /**
     * View hints.
     * @var array
     */
    protected $hints = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:hints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all the View hints that were loaded via Service Providers.';

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
        // Transform array.
        foreach (view()->getFinder()->getHints() as $key => $value) {
            $this->hints[] = [$key, $value[0]];
        }

        $this->table($this->headers, $this->hints);
    }
}
