<?php

namespace App\Usecase;

use App\Participants\Dev;
use App\Participants\Furnizor;
use App\Workflow\Entities\PullRequest;
use App\phpmentors\workflower\src\Process\EventContext;
use App\phpmentors\workflower\src\Process\Process;
use App\phpmentors\workflower\src\Process\WorkItemContext;
use App\phpmentors\workflower\src\Process\ProcessAwareInterface;

class CreatePullRequestUsecase implements ProcessAwareInterface
{

    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function run(PullRequest $pullRequest)
    {
        $dev = new Furnizor();

        $eventContext = new EventContext('StartEvent_1', $pullRequest);

        $this->process->start($eventContext);

        $workItem = new WorkItemContext($dev);
        $workItem->setProcessContext($pullRequest);
        $workItem->setActivityId($pullRequest->getWorkflow()->getCurrentFlowObject()->getId());
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        return $pullRequest;
    }
}
