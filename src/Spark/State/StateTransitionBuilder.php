<?php
/**
 * Date: 18.09.18
 * Time: 07:06
 */

namespace Spark\State;


class StateTransitionBuilder {

    /**
     * @var StateMachineBuilder
     */
    private $states;
    /**
     * @var StateTransition
     */
    private $state;

    public function __construct(array &$states, StateTransition $state) {
        $this->states = &$states;
        $this->state = $state;
    }

    public function to($newState): StateTransitionBuilder {
        $this->createNextRelation($newState);
        return new StateTransitionBuilder($this->states, $this->states[$newState]);

    }

    public function toAll(array $states): void {
        foreach ($states as $newState) {
            $this->createNextRelation($newState);
        }
    }

    /**
     * @param $newState
     */
    private function createNextRelation($newState): void {
        if (!isset($this->states[$newState])) {
            $this->states[$newState] = new StateTransition($newState, $this->state->getState());
        }

        $this->state->addNext($newState);
    }

}