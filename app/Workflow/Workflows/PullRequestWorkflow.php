<?php

namespace App\Workflow\Workflows;

use App\phpmentors\workflower\src\Process\WorkflowContextInterface;

class PullRequestWorkflow implements WorkflowContextInterface
{
    public function getWorkflowId()
    {
        return "PullRequestProcess.bpmn";
    }
}
