<?php

namespace App\Console\Commands;

use App\Services\Export\Parser;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class ExportCommand
 * @package App\Console\Commands
 */
class ExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Parser[]
     */
    protected $parsers;

    /**
     * Create a new command instance.
     *
     * @param iterable $parsers
     */
    public function __construct(iterable $parsers)
    {
        $this->parsers = $parsers;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->parsers as $parser) {
            $parser->parse();
        }
    }
}
