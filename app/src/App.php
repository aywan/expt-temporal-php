<?php

declare(strict_types=1);

namespace App;

use App\Bootloader\TemporalBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Framework\Kernel;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        Framework\CommandBootloader::class,
        TemporalBootloader::class,
    ];
}
