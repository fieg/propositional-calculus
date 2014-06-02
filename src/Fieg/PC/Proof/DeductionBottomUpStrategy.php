<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proof;

use Fieg\PC\CompoundProposition;
use Fieg\PC\Proposition;

class DeductionBottomUpStrategy implements ProofStrategyInterface
{
    public function proof(array $propositions)
    {
        $propositions = array_filter($propositions, array($this, 'isDefiniteClause'));

        $c = array();

        while(false !== $h = $this->_proof($propositions, $c)) {
            $c[] = $h;
        }

        return $c;
    }

    /**
     * @param Proposition[] $clauses
     * @param array $c
     * @return bool|\Fieg\PC\Proposition
     */
    protected function _proof(array &$clauses, array $c)
    {
        foreach($clauses as $i => $clause) {
            $h = $clause;
            $b = array();

            if ($clause instanceof Proposition\Implies) {
                $h = $clause->getHead();
                $body = $clause->getBody();

                if ($body instanceof CompoundProposition) {
                    $b = $body->getPropositions();
                } else {
                    $b = array($body);
                }
            }

            if (!in_array($h, $c) && $this->all_in_array($b, $c)) {
                unset($clauses[$i]);

                return $h;
            }
        }

        return false;
    }

    /**
     * @param array $needles
     * @param array $haystack
     * @return bool
     */
    protected function all_in_array(array $needles, array $haystack)
    {
        foreach($needles as $needle) {
            if (false === in_array($needle, $haystack)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Indicates if a proposition is a definite clause
     *
     * @param Proposition $p
     * @return bool
     */
    public function isDefiniteClause(Proposition $p)
    {
        return (
            $p instanceof Proposition\Atomic
            || ($p instanceof Proposition\Implies && $p->getHead() instanceof Proposition\Atomic)
        );
    }
}
