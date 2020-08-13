<?php
namespace App\piece\stagehandfsm\src\StateMachine\StateMachineTest;

use App\piece\stagehandfsm\src\State\StateInterface;
use App\piece\stagehandfsm\src\StateMachine\StateMachineBuilder;
use App\piece\stagehandfsm\src\StateMachine\StateMachineInterface;

class RegistrationStateMachineBuilder
{
    /**
     * @return StateMachineInterface
     */
    public function build()
    {
        $stateMachineBuilder = new StateMachineBuilder('registration');
        $stateMachineBuilder->addState('input');
        $stateMachineBuilder->addState('confirmation');
        $stateMachineBuilder->addState('success');
        $stateMachineBuilder->setStartState('input');
        $stateMachineBuilder->addTransition('input', 'confirmation', 'confirmation');
        $stateMachineBuilder->addTransition('confirmation', 'success', 'success');
        $stateMachineBuilder->addTransition('confirmation', 'input', 'input');
        $stateMachineBuilder->setEndState('success', StateInterface::STATE_FINAL);

        return $stateMachineBuilder->getStateMachine();
    }
}
