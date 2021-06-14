<?php

declare(strict_types=1);

namespace App\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowClientInterface;

class TemporalBootloader extends Bootloader
{
    public function boot(Container $container, ConfiguratorInterface $configurator): void
    {
        $configurator->setDefaults(
            'temporal',
            [
                'host' => 'temporal:7233'
            ]
        );

        $container->bindSingleton(
            WorkflowClientInterface::class,
            function() use ($configurator) {
                $config = $configurator->getConfig('temporal');
                return WorkflowClient::create(ServiceClient::create($config['host']));
            }
        );
    }
}
