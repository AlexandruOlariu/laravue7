<?php

namespace App\Workflow\OperationRunner;

use App\Models\PullRequest;
use App\Participants\Reviewer;
use App\phpmentors\workflower\src\Workflow\Operation\OperationalInterface;
use App\phpmentors\workflower\src\Workflow\Operation\OperationRunnerInterface;
use App\phpmentors\workflower\src\Workflow\Workflow;

class MergePullRequestOperationRunner implements OperationRunnerInterface
{
    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new Reviewer();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {

        $processData = $workflow->getProcessData();
        $pullRequest = PullRequest::findOrFail($processData['id']);
        $pullRequest->merged = true;


        $pullRequest->save();
    }
}
