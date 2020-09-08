<?php

namespace App\Workflow\Entities;

use App\Workflow\Entities\PullRequest;
use App\phpmentors\workflower\src\Persistence\PhpWorkflowSerializer;

class ConverterModelToWorkflowEntity
{
    public function convertModelToEntity($model)
    {
        $pullRequest = new PullRequest();

        $pullRequest->setId($model->id);
        $pullRequest->setTitle($model->title);
        //$pullRequest->setMerged($model->merged);
        //$pullRequest->setApproved($model->approved);

        $pullRequest->setCompleteazaDateDeBazaFurnizor($model->CompleteazaDateDeBazaFurnizor);
        $pullRequest->setCreeazaContFurnizor($model->CreeazaContFurnizor);
        $pullRequest->setVerificareFurnizor($model->VerificareFurnizor);
        $pullRequest->setIntraInCont($model->IntraInCont);

        if ($model->serialized_workflow != null) {
            $pullRequest->setWorkflow($this->deserializeWorkflow($model));
            $pullRequest->setSerializedWorkflow($model->serialized_workflow);
        }

        return $pullRequest;
    }

    private function deserializeWorkflow($model)
    {
        $serialize = new PhpWorkflowSerializer();
        return $serialize->deserialize($model->serialized_workflow);
    }
}
