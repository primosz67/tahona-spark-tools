<?php
/**
 * Date: 16.09.18
 * Time: 17:01
 */

namespace Spark\State;


class StateTransition {

    private $prev = [];
    private $next = [];

    private $state;

    public function __construct($state, $prev = null, $next = null) {
        $this->state = $state;

        if ($prev !== null) {
            $this->prev[$prev] = $prev;
        }
        if ($next !== null) {
            $this->next[$next] = $next;
        }
    }

    public function getPrev(): array {
        return $this->prev;
    }

    public function addPrev($prev): void {
        $this->prev[$prev] = $prev;
    }

    public function getNext(): array {
        return $this->next;
    }

    public function addNext($next): void {
        $this->next[$next] = $next;
    }

    public function getState() {
        return $this->state;
    }

    public function changeTo($state) {
        if ($this->canChangeTo($state)) {
            return $state;
        }
        throw  new StateMachineException("Cannot change state from $this->state to $state");
    }

    public function rollbackTo($state): object {
        if ($this->canRollbackTo($state)) {
            return $state;
        }
        throw  new StateMachineException("Cannot rollback state from $this->state to $state");
    }

    public function canChangeTo($newState): bool {
        return \in_array($newState, $this->next, true);
    }

    public function canRollbackTo($newState): bool {
        return \in_array($newState, $this->prev, true);
    }


}