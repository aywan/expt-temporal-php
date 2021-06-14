<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\SimpleActivity;

use Carbon\CarbonInterval;
use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

class ExecuteCommand extends Command
{
    protected const NAME = 'simple-activity';
    protected const DESCRIPTION = 'Execute SimpleActivity\GreetingWorkflow';

    protected const ARGUMENTS = [
        ['name', InputArgument::OPTIONAL, 'name', 'user']
    ];

    private WorkflowClientInterface $workflowClient;

    public function __construct(WorkflowClientInterface $workflowClient, string $name = null)
    {
        parent::__construct($name);
        $this->workflowClient = $workflowClient;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $workflow = $this->workflowClient->newWorkflowStub(
            GreetingWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $output->writeln("Starting <comment>GreetingWorkflow</comment>... ");

        // Start a workflow execution. Usually this is done from another program.
        // Uses task queue from the GreetingWorkflow @WorkflowMethod annotation.
        $run = $this->workflowClient->start($workflow, $input->getArgument('name'));

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        // getResult waits for workflow to complete
        $output->writeln(sprintf("Result:\n<info>%s</info>", $run->getResult()));

        return self::SUCCESS;
    }
}
