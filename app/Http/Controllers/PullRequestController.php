<?php

namespace App\Http\Controllers;

use App\PullRequest;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\FixPullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use App\Usecase\DauLaCuptorPullRequestUsecase;
use App\Usecase\FaceBlatulsiIngredientelePullRequestUsecase;
use App\Usecase\PrimesteComandaPullRequestUsecase;

use App\Workflow\Entities\ConverterModelToWorkflowEntity;
use App\Workflow\Entities\PullRequest as PullRequestEntity;
use App\Workflow\OperationRunner\MergePullRequestOperationRunner;
use App\Workflow\OperationRunner\PrimesteComandaPullRequestOperationRunner;
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
    public function getPrimesteComandaPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.PrimesteComanda", compact('pull_request'));
    }

    public function postPrimesteComandaPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $model->primestecomanda=$request->primestecomanda;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executePrimesteComandaUseCase($entity);
        $this->savePullRequest($model, $entityUpdated);

        return response()->json('I managed to P C ', 200);
        //return redirect()->route('index');
    }

    public function getFaceBlatulsiIngredientelePullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.FaceBlatulsiIngredientele", compact('pull_request'));
    }

    public function postFaceBlatulsiIngredientelePullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $model->faceblatulsiingredientele=$request->faceblatulsiingredientele;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeFaceBlatulsiIngredienteleUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }

    public function getDauLaCuptorPullRequest($id)
    {
        $pull_request = PullRequest::findOrFail($id);
        return view("bpmn.DauLaCuptor", compact('pull_request'));
    }

    public function postDauLaCuptorPullRequest(Request $request)
    {
        $model = PullRequest::findOrFail($request->id);
        $model->daulacuptor=$request->daulacuptor;
        $entity = $this->convertModelToEntity($model);
        $entityUpdated = $this->executeDauLaCuptorUseCase($entity);

        $this->savePullRequest($model, $entityUpdated);

        return redirect()->route('index');
    }



    private function makeModelPullRequest($request)
    {
        $pullRequest = new PullRequest();
        $pullRequest->title = $request->title;
        $pullRequest->primestecomanda= false ;
        $pullRequest->faceblatulsiingredientele= false;
        $pullRequest->daulacuptor= false;
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

    private function executePrimesteComandaUseCase($entity)
{
    $usecase = new PrimesteComandaPullRequestUsecase();
    $usecase->setProcess($this->createProcess());
    $entity = $usecase->run($entity);

    return $entity;
}
    private function executeFaceBlatulsiIngredienteleUseCase($entity)
    {
        $usecase = new FaceBlatulsiIngredientelePullRequestUsecase();
        $usecase->setProcess($this->createProcess());
        $entity = $usecase->run($entity);

        return $entity;
    }
    private function executeDauLaCuptorUseCase($entity)
    {
        $usecase = new DauLaCuptorPullRequestUsecase();
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
        $operationRunner = new PrimesteComandaPullRequestOperationRunner();
        $process = new Process($pullRequestWorkflow, $repository, $operationRunner);
        return $process;
    }

    private function savePullRequest(PullRequest $pullRequest, PullRequestEntity $pullRequestWorkflow)
    {
        $serializer = new PhpWorkflowSerializer();

        $pullRequest->title = $pullRequestWorkflow->getTitle();
        //$pullRequest->approved = $pullRequestWorkflow->isApproved();
        $pullRequest->primestecomanda=$pullRequestWorkflow->isPrimesteComanda();
        $pullRequest->faceblatulsiingredientele=$pullRequestWorkflow->isFaceblatulSiIngredientele();
        $pullRequest->daulacuptor=$pullRequestWorkflow->isDauLaCuptor();
        $workflow = $pullRequestWorkflow->getWorkflow();
        $pullRequest->state = $workflow->getCurrentFlowObject()->getId();
        $pullRequest->serialized_workflow = $serializer->serialize($workflow);
        $pullRequest->save();
        return $pullRequest;
    }
}
