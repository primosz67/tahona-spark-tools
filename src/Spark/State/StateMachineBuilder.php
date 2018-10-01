<?php

namespace Spark\State;


use Spark\Utils\Collections;

class StateMachineBuilder {

    private $initial = [];
    private $final = [];
    private $states = [];

    public function initial($state): StateMachineBuilder {
        $this->initial[$state] = $state;
        $this->states[$state] = new StateTransition($state);
        return $this;
    }

    public function final($state): StateMachineBuilder {
        $this->final[$state] = $state;
        $this->states[$state] = new StateTransition($state);
        return $this;
    }

    public function transition($state): StateTransitionBuilder {
        if (!isset($this->states[$state])) {
            $this->states[$state] = new StateTransition($state);
        }
        return new StateTransitionBuilder($this->states, $this->states[$state]);
    }

    public function build(): StateMachine {
        foreach ($this->initial as $initState) {
            $this->buildState($initState);
        }

        return new StateMachine($this->states);
    }

    /**
     * @param $state
     * @return mixed
     */
    private function getPrevState($state) {
        /** @var StateTransition $prev */
        if (null !== $this->tmp) {
            $prev = $this->states[$this->tmp->getState()];
            $prev->addNext($state);
            $prevState = $prev->getState();
            return $prevState;
        }
        return null;
    }

    /**
     * @param $initState
     * @throws StateMachineException
     */
    private function buildState($initState, $prevState = null): void {
        if (isset($this->states[$initState])) {
            /** @var StateTransition $transition */
            $transition = $this->states[$initState];

            if ($prevState !== null) {
                $transition->addPrev($prevState);
            }

            if (Collections::isNotEmpty($transition->getNext())) {
                $nextStates = $transition->getNext();
                foreach ($nextStates as $state) {
                    $this->buildState($state, $initState);
                }
            }

        } else {
            throw new StateMachineException('No transition for state: ' . $initState);
        }
    }
}