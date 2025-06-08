<?php

namespace Itstudioat\Hpm\Commands;

use Illuminate\Console\Command;

class HpmCommand extends Command
{
    public $signature = 'hpm';

    public $description = 'Version Of HPMaker';

    public function handle(): int
    {
        $this->comment(config('hpm.version'));

        return self::SUCCESS;
    }
}
