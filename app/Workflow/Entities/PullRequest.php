<?php


namespace App\Workflow\Entities;
use App\phpmentors\workflower\src\Persistence\WorkflowSerializableInterface;
use App\phpmentors\workflower\src\Process\ProcessContextInterface;
use App\phpmentors\workflower\src\Workflow\Workflow;

//use PHPMentors\Workflower\Persistence\WorkflowSerializableInterface;
//use PHPMentors\Workflower\Process\ProcessContextInterface;
//use PHPMentors\Workflower\Workflow\Workflow;

class PullRequest implements ProcessContextInterface, WorkflowSerializableInterface
{
    private $id;
    private $title;
   // private $approved = false;
   // private $merged = false;
    private $CompleteazaDateDeBazaFurnizor=false;
    private $CreeazaContFurnizor=false;
    private $VerificareFurnizor=false;
    private $IntraInCont=false;
    private $workflow;
    private $serializedWorkflow;

    /*public function getProcessData()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'approved' => $this->approved,
            'merged' => $this->merged,
            'data' => $this,
        ];
    }*/

    public function getProcessData()
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'CompleteazaDateDeBazaFurnizor'=>$this->CompleteazaDateDeBazaFurnizor,
            'CreeazaContFurnizor'=>$this->CreeazaContFurnizor,
            'VerificareFurnizor'=>$this->VerificareFurnizor,
            'IntraInCont'=>$this->IntraInCont,

            'data' => $this,
        ];
    }

    public function isCompleteazaDateDeBazaFurnizor(): bool
    {
        return $this->CompleteazaDateDeBazaFurnizor;
    }

    public function setCompleteazaDateDeBazaFurnizor(bool $CompleteazaDateDeBazaFurnizor)
    {
        $this->CompleteazaDateDeBazaFurnizor = $CompleteazaDateDeBazaFurnizor;
    }

    public function isCreeazaContFurnizor(): bool
    {
        return $this->CreeazaContFurnizor;
    }

    public function setCreeazaContFurnizor(bool $CreeazaContFurnizor)
    {
        $this->CreeazaContFurnizor = $CreeazaContFurnizor;
    }
    public function isVerificareFurnizor(): bool
    {
        return $this->VerificareFurnizor;
    }

    public function setVerificareFurnizor(bool $VerificareFurnizor)
    {
        $this->VerificareFurnizor = $VerificareFurnizor;
    }
    public function isIntraInCont(): bool
    {
        return $this->IntraInCont;
    }

    public function setIntraInCont(bool $IntraInCont)
    {
        $this->IntraInCont = $IntraInCont;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
/*
    public function isMerged(): bool
    {
        return $this->merged;
    }

    public function setMerged(bool $merged)
    {
        $this->merged = $merged;
    }*/



    public function getWorkflow()
    {
        return $this->workflow;
    }

    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    public function setSerializedWorkflow($serializedWorkflow)
    {
        $this->serializedWorkflow = $serializedWorkflow;
    }

    public function getSerializedWorkflow()
    {
        if (is_resource($this->serializedWorkflow)) {
            return stream_get_contents($this->serializedWorkflow, -1, 0);
        }

        return $this->serializedWorkflow;
    }
    /*
    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved)
    {
        $this->approved = $approved;
    }
*/
    public function __toString()
    {
        return print_r($this->getProcessData(), true);
    }
}
