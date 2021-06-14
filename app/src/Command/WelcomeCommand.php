<?php

declare(strict_types=1);

namespace App\Command;

use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class WelcomeCommand extends Command
{
    protected const NAME        = 'welcome';
    protected const DESCRIPTION = 'Welcome command';
    protected const ARGUMENTS   = [
        ['name', InputArgument::OPTIONAL, 'Name', 'User'],
    ];

    /**
     * This method supports argument injection.
     */
    public function perform(): void
    {
        $this->sprintf(
            'Welcome, <fg=green>%s</fg=green>!' . PHP_EOL,
            $this->argument('name')
        );
    }
}
