<?php

namespace Itstudioat\Hpm\Commands;

use Illuminate\Console\Command;

class HpmCommand extends Command
{
    public $signature = 'hpm';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
