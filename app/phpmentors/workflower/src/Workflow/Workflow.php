<?php
/*
 * Copyright (c) KUBO Atsuhiro <kubo@iteman.jp> and contributors,
 * All rights reserved.
 *
 * This file is part of Workflower.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace App\phpmentors\workflower\src\Workflow;

use App\phpmentors\domainkata\src\Entity\EntityInterface;
use App\phpmentors\domainkata\src\Entity\Operation\IdentifiableInterface;
use App\phpmentors\workflower\src\Workflow\Activity\ActivityInterface;
use App\phpmentors\workflower\src\Workflow\Activity\UnexpectedActivityException;
use App\phpmentors\workflower\src\Workflow\Connection\SequenceFlow;
use App\phpmentors\workflower\src\Workflow\Element\ConditionalInterface;
use App\phpmentors\workflower\src\Workflow\Element\ConnectingObjectCollection;
use App\phpmentors\workflower\src\Workflow\Element\ConnectingObjectInterface;
use App\phpmentors\workflower\src\Workflow\Element\FlowObjectCollection;
use App\phpmentors\workflower\src\Workflow\Element\FlowObjectInterface;
use App\phpmentors\workflower\src\Workflow\Element\TransitionalInterface;
use App\phpmentors\workflower\src\Workflow\Event\EndEvent;
use App\phpmentors\workflower\src\Workflow\Event\StartEvent;
use App\phpmentors\workflower\src\Workflow\Gateway\GatewayInterface;
use App\phpmentors\workflower\src\Workflow\Operation\OperationalInterface;
use App\phpmentors\workflower\src\Workflow\Operation\OperationRunnerInterface;
use App\phpmentors\workflower\src\Workflow\Participant\ParticipantInterface;
use App\phpmentors\workflower\src\Workflow\Participant\Role;
use App\phpmentors\workflower\src\Workflow\Participant\RoleCollection;
use App\piece\stagehandfsm\src\Event\TransitionEvent;
use App\piece\stagehandfsm\src\State\FinalState;
use App\piece\stagehandfsm\src\State\InitialState;
use App\piece\stagehandfsm\src\State\State;
use App\piece\stagehandfsm\src\State\StateInterface;
use App\piece\stagehandfsm\src\StateMachine\StateMachine;
use App\piece\stagehandfsm\src\StateMachine\StateMachineInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression;
class Workflow implements EntityInterface, IdentifiableInterface, \Serializable
{
    const DEFAULT_ROLE_ID = '__ROLE__';

    /**
     * @var string
     */
    private static $STATE_START = '__START__';

    /**
     * @var string
     */
    private $name;

    /**
     * @var ConnectingObjectCollection
     */
    private $connectingObjectCollection;

    /**
     * @var FlowObjectCollection
     */
    private $flowObjectCollection;

    /**
     * @var RoleCollection
     */
    private $roleCollection;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var array
     */
    private $processData;

    /**
     * @var StateMachineInterface
     */
    private $stateMachine;

    /**
     * @var ExpressionLanguage
     *
     * @since Property available since Release 1.1.0
     */
    private $expressionLanguage;

    /**
     * @var OperationRunnerInterface
     *
     * @since Property available since Release 1.2.0
     */
    private $operationRunner;

    /**
     * @param int|string $id
     * @param string     $name
     */
    public function __construct($id, $name)
    {
        $this->connectingObjectCollection = new ConnectingObjectCollection();
        $this->flowObjectCollection = new FlowObjectCollection();
        $this->roleCollection = new RoleCollection();
        $this->stateMachine = $this->createStateMachine($id);
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            'name' => $this->name,
            'connectingObjectCollection' => $this->connectingObjectCollection,
            'flowObjectCollection' => $this->flowObjectCollection,
            'roleCollection' => $this->roleCollection,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'stateMachine' => $this->stateMachine,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        foreach (unserialize($serialized) as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->stateMachine->getStateMachineId();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ConnectingObjectInterface $connectingObject
     */
    public function addConnectingObject(ConnectingObjectInterface $connectingObject)
    {
        $this->stateMachine->addTransition(
            $this->stateMachine->getState($connectingObject->getSource()->getId()),
            new TransitionEvent($connectingObject->getDestination()->getId()),
            $this->stateMachine->getState($connectingObject->getDestination()->getId()),
            null,
            null
        );

        $this->connectingObjectCollection->add($connectingObject);
    }

    /**
     * @param FlowObjectInterface $flowObject
     */
    public function addFlowObject(FlowObjectInterface $flowObject)
    {
        $this->stateMachine->addState(new State($flowObject->getId()));
        if ($flowObject instanceof StartEvent) {
            $this->stateMachine->addTransition(
                $this->stateMachine->getState(self::$STATE_START),
                new TransitionEvent($flowObject->getId()),
                $this->stateMachine->getState($flowObject->getId()),
                null,
                null
            );
        } elseif ($flowObject instanceof EndEvent) {
            $this->stateMachine->addTransition(
                $this->stateMachine->getState($flowObject->getId()),
                new TransitionEvent($flowObject->getId()),
                $this->stateMachine->getState(StateInterface::STATE_FINAL),
                null,
                null
            );
        }

        $this->flowObjectCollection->add($flowObject);
    }

    /**
     * @param int|string $id
     *
     * @return ConnectingObjectInterface|null
     */
    public function getConnectingObject($id)
    {
        return $this->connectingObjectCollection->get($id);
    }

    /**
     * @param TransitionalInterface $flowObject
     *
     * @return ConnectingObjectCollection
     */
    public function getConnectingObjectCollectionBySource(TransitionalInterface $flowObject)
    {
        return $this->connectingObjectCollection->filterBySource($flowObject);
    }

    /**
     * @param int|string $id
     *
     * @return FlowObjectInterface|null
     */
    public function getFlowObject($id)
    {
        return $this->flowObjectCollection->get($id);
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->roleCollection->add($role);
    }

    /**
     * @param int|string $id
     *
     * @return bool
     */
    public function hasRole($id)
    {
        return $this->roleCollection->get($id) !== null;
    }

    /**
     * @param int|string $id
     *
     * @return Role
     */
    public function getRole($id)
    {
        return $this->roleCollection->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->stateMachine->isActive();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnded()
    {
        return $this->stateMachine->isEnded();
    }

    /**
     * @return FlowObjectInterface|null
     */
    public function getCurrentFlowObject()
    {
        $state = $this->stateMachine->getCurrentState();
        if ($state === null) {
            return null;
        }

        if ($state instanceof FinalState) {
            return $this->flowObjectCollection->get($this->stateMachine->getPreviousState()->getStateId());
        } else {
            return $this->flowObjectCollection->get($state->getStateId());
        }
    }

    /**
     * @return FlowObjectInterface|null
     */
    public function getPreviousFlowObject()
    {
        $state = $this->stateMachine->getPreviousState();
        if ($state === null) {
            return null;
        }

        $previousFlowObject = $this->flowObjectCollection->get($state->getStateId());
        if ($previousFlowObject instanceof EndEvent) {
            $transitionLogs = $this->stateMachine->getTransitionLogs();

            return $this->flowObjectCollection->get($transitionLogs[count($transitionLogs) - 2]->getFromState()->getStateId());
        } else {
            return $previousFlowObject;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function start(StartEvent $event)
    {
        $this->startDate = new \DateTime();
        $this->stateMachine->start();
        $this->stateMachine->triggerEvent($event->getId());
        $this->selectSequenceFlow($event);
        $this->next();
    }

    /**
     * @param ActivityInterface    $activity
     * @param ParticipantInterface $participant
     */
    public function allocateWorkItem(ActivityInterface $activity, ParticipantInterface $participant)
    {
        $this->assertParticipantHasRole($activity, $participant);
        $this->assertCurrentFlowObjectIsExpectedActivity($activity);

        $activity->allocate($participant);
    }

    /**
     * @param ActivityInterface    $activity
     * @param ParticipantInterface $participant
     */
    public function startWorkItem(ActivityInterface $activity, ParticipantInterface $participant)
    {
        $this->assertParticipantHasRole($activity, $participant);
        $this->assertCurrentFlowObjectIsExpectedActivity($activity);

        $activity->start();
    }

    /**
     * @param ActivityInterface    $activity
     * @param ParticipantInterface $participant
     */
    public function completeWorkItem(ActivityInterface $activity, ParticipantInterface $participant)
    {
        $this->assertParticipantHasRole($activity, $participant);
        $this->assertCurrentFlowObjectIsExpectedActivity($activity);

        $activity->complete($participant);
        $this->selectSequenceFlow($activity);
        $this->next();
    }

    /**
     * @param array $processData
     */
    public function setProcessData(array $processData)
    {
        $this->processData = $processData;
    }

    /**
     * @return array
     *
     * @since Method available since Release 1.2.0
     */
    public function getProcessData()
    {
        return $this->processData;
    }

    /**
     * @param ExpressionLanguage $expressionLanguage
     *
     * @since Method available since Release 1.1.0
     */
    public function setExpressionLanguage(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * @param OperationRunnerInterface $operationRunner
     *
     * @since Method available since Release 1.2.0
     */
    public function setOperationRunner(OperationRunnerInterface $operationRunner)
    {
        $this->operationRunner = $operationRunner;
    }

    /**
     * @param EndEvent $event
     */
    private function end(EndEvent $event)
    {
        $this->stateMachine->triggerEvent($event->getId());
        $this->endDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return ActivityLogCollection
     */
    public function getActivityLog()
    {
        $activityLogCollection = new ActivityLogCollection();
        foreach ($this->stateMachine->getTransitionLog() as $transitionLog) {
            $flowObject = $this->getFlowObject($transitionLog->getToState()->getStateId());
            if ($flowObject instanceof ActivityInterface) {
                $activityLogCollection->add(new ActivityLog($flowObject));
            }
        }

        return $activityLogCollection;
    }

    /**
     * @param string $stateMachineName
     *
     * @return StateMachineInterface
     */
    private function createStateMachine($stateMachineName)
    {
        $stateMachine = new StateMachine($stateMachineName);
        $stateMachine->addState(new InitialState());
        $stateMachine->addState(new FinalState());
        $stateMachine->addState(new State(self::$STATE_START));
        $stateMachine->addTransition(
            $stateMachine->getState(StateInterface::STATE_INITIAL),
            new TransitionEvent(\App\piece\stagehandfsm\src\Event\EventInterface::EVENT_START),
            $stateMachine->getState(self::$STATE_START),
            null,
            null
        );

        return $stateMachine;
    }

    /**
     * @param TransitionalInterface $currentFlowObject
     *
     * @throws SequenceFlowNotSelectedException
     */
    private function selectSequenceFlow(TransitionalInterface $currentFlowObject)
    {
        foreach ($this->connectingObjectCollection->filterBySource($currentFlowObject) as $connectingObject) { /* @var $connectingObject ConnectingObjectInterface */
            if ($connectingObject instanceof SequenceFlow) {
                if (!($currentFlowObject instanceof ConditionalInterface) || $connectingObject->getId() !== $currentFlowObject->getDefaultSequenceFlowId()) {
                    $condition = $connectingObject->getCondition();
                    if ($condition === null) {
                        $selectedSequenceFlow = $connectingObject;
                        break;
                    } else {
                        $expressionLanguage = $this->expressionLanguage ?: new ExpressionLanguage();
                        if ($expressionLanguage->evaluate($condition, $this->processData)) {
                            $selectedSequenceFlow = $connectingObject;
                            break;
                        }
                    }
                }
            }
        }

        if (!isset($selectedSequenceFlow)) {
            if (!($currentFlowObject instanceof ConditionalInterface) || $currentFlowObject->getDefaultSequenceFlowId() === null) {
                throw new SequenceFlowNotSelectedException(sprintf('No sequence flow can be selected on "%s".', $currentFlowObject->getId()));
            }

            $selectedSequenceFlow = $this->connectingObjectCollection->get($currentFlowObject->getDefaultSequenceFlowId());
        }

        $this->stateMachine->triggerEvent($selectedSequenceFlow->getDestination()->getId());

        if ($this->getCurrentFlowObject() instanceof GatewayInterface) {
            $this->selectSequenceFlow($this->getCurrentFlowObject());
        }
    }

    /**
     * @param ActivityInterface    $activity
     * @param ParticipantInterface $participant
     *
     * @throws AccessDeniedException
     */
    private function assertParticipantHasRole(ActivityInterface $activity, ParticipantInterface $participant)
    {
        if (!$participant->hasRole($activity->getRole()->getId())) {
            throw new AccessDeniedException(sprintf('The participant "%s" does not have the role "%s" that is required to operate the activity "%s".', $participant->getId(), $activity->getRole()->getId(), $activity->getId()));
        }
    }

    /**
     * @param ActivityInterface $activity
     *
     * @throws UnexpectedActivityException
     */
    private function assertCurrentFlowObjectIsExpectedActivity(ActivityInterface $activity)
    {
        if (!$activity->equals($this->getCurrentFlowObject())) {
            throw new UnexpectedActivityException(sprintf('The current flow object is not equal to the expected activity "%s".', $activity->getId()));
        }
    }

    /**
     * @since Method available since Release 1.2.0
     */
    private function next()
    {
        $currentFlowObject = $this->getCurrentFlowObject();
        if ($currentFlowObject instanceof ActivityInterface) {
            $currentFlowObject->createWorkItem();

            if ($currentFlowObject instanceof OperationalInterface) {
                $this->executeOperationalActivity($currentFlowObject);
            }
        } elseif ($currentFlowObject instanceof EndEvent) {
            $this->end($currentFlowObject);
        }
    }

    /**
     * @since Method available since Release 1.2.0
     */
    private function executeOperationalActivity(ActivityInterface $operational)
    {
        $participant = $this->operationRunner->provideParticipant($operational, $this);
        $this->allocateWorkItem($operational, $participant);
        $this->startWorkItem($operational, $participant);
        $this->operationRunner->run($operational, $this);
        $this->completeWorkItem($operational, $participant);
    }
}
