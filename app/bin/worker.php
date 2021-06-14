<?php

declare(strict_types=1);

use App\SimpleActivity\GreetingActivity;
use App\SimpleActivity\GreetingWorkflow;
use Temporal\WorkerFactory;

ini_set('display_errors', 'stderr');
define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

$factory = WorkerFactory::create();
$worker = $factory->newWorker();

$worker->registerWorkflowTypes(
    GreetingWorkflow::class
);

$worker->registerActivityImplementations(
    new GreetingActivity()
);

// start primary loop
$factory->run();
