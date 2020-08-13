<?php

namespace App\Workflow\OperationRunner;

use App\Models\PullRequest;
use App\Participants\Reviewer;
use App\phpmentors\workflower\src\Workflow\Operation\OperationalInterface;
use App\phpmentors\workflower\src\Workflow\Operation\OperationRunnerInterface;
use App\phpmentors\workflower\src\Workflow\Workflow;

class PrimesteComandaPullRequestOperationRunner implements OperationRunnerInterface
{
    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new Dev();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {

        $processData = $workflow->getProcessData();
        $pullRequest = PullRequest::findOrFail($processData['id']);
        dd('sunt aici');
        $pullRequest->primestecomanda = true;


        $pullRequest->save();
    }
}
