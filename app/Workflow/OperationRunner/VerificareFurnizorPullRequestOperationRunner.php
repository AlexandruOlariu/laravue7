<?php

namespace App\Workflow\OperationRunner;

use App\PullRequest;
use App\Participants\Furnizor;
use App\Participants\Reviewer;
use App\phpmentors\workflower\src\Workflow\Operation\OperationalInterface;
use App\phpmentors\workflower\src\Workflow\Operation\OperationRunnerInterface;
use App\phpmentors\workflower\src\Workflow\Workflow;

class VerificareFurnizorPullRequestOperationRunner implements OperationRunnerInterface
{
    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new Furnizor();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {

        $processData = $workflow->getProcessData();
        $pullRequest = PullRequest::findOrFail($processData['id']);
        
        $pullRequest->CompleteazaDateDeBazaFurnizor = false;
        


        $pullRequest->save();
    }
}
