<?php
/**
 * Date: 16.09.18
 * Time: 18:19
 */

namespace Spark\State;


class StateMachine {


    /**
     * @var array
     */
    private $allStates;

    public function __construct(array $allStates) {
        $this->allStates = $allStates;
    }

    public function of($state): StateTransition {
        if (!array_key_exists($state, $this->allStates))
            throw  new StateMachineException('State not exist! state:' . $state);

        return clone $this->allStates[$state];
    }


}