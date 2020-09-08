<?php

namespace App\Http\Controllers;

use App\PullRequest;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\FixPullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use App\Usecase\IntraInContPullRequestUsecase;
use App\Usecase\CreeazaContFurnizorPullRequestUsecase;
use App\Usecase\CompleteazaDateDeBazaFurnizorPullRequestUsecase;
use App\Usecase\VerificareFurnizorPullRequestUsecase;


use App\Workflow\Entities\ConverterModelToWorkflowEntity;
use App\Workflow\Entities\PullRequest as PullRequestEntity;
use App\Workflow\OperationRunner\MergePullRequestOperationRunner;
use App\Workflow\OperationRunner\VerificareFurnizorPullRequestOperationRunner;
use App\Workflow\Repository\WorkflowRepository;
use App\Workflow\Workflows\PullRequestWorkflow;
use Illuminate\Http\Request;
use App\phpmentors\workflower\src\Persistence\PhpWorkflowSerializer;
use App\phpmentors\workflower\src\Process\Process;

class PullRequestController extends Controller
{
    public function index()
    {
        $pullRequests = PullRequest::all();
        return view('bpmn.index', compact('pullRequests'));
    }

    public function getCreatePullRequest(PullRequest $pullRequest)
    {
        return view("bpmn.create", compact('pullRequest'));
    }

    public function postCreatePullRequest(Request $request)
    {

        $model = $this->makeModelPullRequest($request);

        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeCreateUseCase($entity);

       $pr=$this->savePullRequest($model, $entityUpdated);

        return response()->json($pr, 200);
    }
/*
    public function getReviewPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("review", compact('pull_request'));
    }

    public function postReviewPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $model->approved = $request->approved;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeReviewUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }

    public function getFixPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("fix", compact('pull_request'));
    }

    public function postFixPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeFixUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }*/
    public function getCompleteazaDateDeBazaFurnizorPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.CompleteazaDateDeBazaFurnizor", compact('pull_request'));
    }

    public function postCompleteazaDateDeBazaFurnizorPullRequest(Request $request)
    {
        if($request->CompleteazaDateDeBazaFurnizor) {
            $model = PullRequest::findOrFail($request->id);
            $model->CompleteazaDateDeBazaFurnizor = $request->CompleteazaDateDeBazaFurnizor;
            $entity = $this->convertModelToEntity($model);
            $entityUpdated = $this->executeCompleteazaDateDeBazaFurnizorUseCase($entity);
            $this->savePullRequest($model, $entityUpdated);
        }
        return response()->json('I managed to CompleteazaDateDeBazaFurnizor ', 200);
        //return redirect()->route('index');
    }

    public function getCreeazaContFurnizorPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.CreeazaContFurnizor", compact('pull_request'));
    }

    public function postCreeazaContFurnizorPullRequest(Request $request)
    {
        if($request->CreeazaContFurnizor) {
            $model = PullRequest::findOrFail($request->id);
            $model->CreeazaContFurnizor = $request->CreeazaContFurnizor;
            $entity = $this->convertModelToEntity($model);
            $entityUpdated = $this->executeCreeazaContFurnizorUseCase($entity);

            $this->savePullRequest($model, $entityUpdated);
        }
        return response()->json('I managed to CreeazaContFurnizor ', 200);
    }

    public function getVerificareFurnizorPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        //return "purcel";
        return view("bpmn.VerificareFurnizor", compact('pull_request'));
    }

    public function postVerificareFurnizorPullRequest(Request $request)
    {
        if($request->VerificareFurnizor){
        $model = PullRequest::findOrFail($request->id);
        $model->VerificareFurnizor=$request->VerificareFurnizor;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeVerificareFurnizorUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

            return response()->json('I managed to VerificareFurnizor ->OK ', 200);
        }
        else{
            $model = PullRequest::findOrFail($request->id);
            $model->VerificareFurnizor=$request->VerificareFurnizor;
            $model->CreeazaContFurnizor=false;
            $model->CompleteazaDateDeBazaFurnizor=false;
           // $model->state="Catel";
            $entity = $this->convertModelToEntity($model);
            $entityUpdated = $this->executeVerificareFurnizorUseCase($entity);

            $this->savePullRequest($model, $entityUpdated);
//return "catel";
            return response()->json('I managed to VerificareFurnizor ->NOT OK ', 200);
        }
    }

    public function getIntraInContPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.IntraInCont", compact('pull_request'));
    }

    public function postIntraInContPullRequest(Request $request)
    {
        if($request->IntraInCont) {
            $model = PullRequest::findOrFail($request->id);
            $model->IntraInCont = $request->IntraInCont;
            $entity = $this->convertModelToEntity($model);
            $entityUpdated = $this->executeIntraInContUseCase($entity);

            $this->savePullRequest($model, $entityUpdated);
        }
        return response()->json('I managed to IntraInCont', 200);
    }



    private function makeModelPullRequest($request)
    {
        $pullRequest = new PullRequest();
        $pullRequest->title = $request->title;
        $pullRequest->CompleteazaDateDeBazaFurnizor= false ;
        $pullRequest->CreeazaContFurnizor= false;
        $pullRequest->VerificareFurnizor= false;
        $pullRequest->IntraInCont= false;
        //$pullRequest->merged = false;
       // $pullRequest->approved = false;
        return $pullRequest;
    }

    private function executeCreateUseCase($entity)
    {
        $usecase = new CreatePullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);
        return $entity;
    }

    private function executeCompleteazaDateDeBazaFurnizorUseCase($entity)
{
    $usecase = new CompleteazaDateDeBazaFurnizorPullRequestUsecase();
    $usecase->setProcess($this->createProcess());
    $entity = $usecase->run($entity);

    return $entity;
}
    private function executeCreeazaContFurnizorUseCase($entity)
    {
        $usecase = new CreeazaContFurnizorPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }
    private function executeVerificareFurnizorUseCase($entity)
    {
        $usecase = new VerificareFurnizorPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }
    private function executeIntraInContUseCase($entity)
    {
        $usecase = new IntraInContPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }

/*
    private function executeReviewUseCase($entity)
    {
        $usecase = new ReviewPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }

    private function executeFixUseCase($entity)
    {
        $usecase = new FixPullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }
*/

    private function convertModelToEntity($model)
    {
        $converter = new ConverterModelToWorkflowEntity();
        return $converter->convertModelToEntity($model);
    }

    private function createProcess()
    {
        $repository = new WorkflowRepository();
        $pullRequestWorkflow = new PullRequestWorkflow();
        //$operationRunner = new MergePullRequestOperationRunner();
        $operationRunner = new VerificareFurnizorPullRequestOperationRunner();
        $process = new Process($pullRequestWorkflow, $repository, $operationRunner);
        return $process;
    }

    private function savePullRequest(PullRequest $pullRequest, PullRequestEntity $pullRequestWorkflow)
    {
        $serializer = new PhpWorkflowSerializer();

        $pullRequest->title = $pullRequestWorkflow->getTitle();
        //$pullRequest->approved = $pullRequestWorkflow->isApproved();
        $pullRequest->CompleteazaDateDeBazaFurnizor=$pullRequestWorkflow->isCompleteazaDateDeBazaFurnizor();
        $pullRequest->CreeazaContFurnizor=$pullRequestWorkflow->isCreeazaContFurnizor();
        $pullRequest->VerificareFurnizor=$pullRequestWorkflow->isVerificareFurnizor();
        $pullRequest->IntraInCont=$pullRequestWorkflow->isIntraInCont();

        $workflow = $pullRequestWorkflow->getWorkflow();
        $pullRequest->state = $workflow->getCurrentFlowObject()->getId();
        $pullRequest->serialized_workflow = $serializer->serialize($workflow);
        $pullRequest->save();
        return $pullRequest;
    }
    public function showDiag($id){
        $pr=PullRequest::findOrFail($id);
        return view('showBPMNdiag',compact('pr'));
    }
}
