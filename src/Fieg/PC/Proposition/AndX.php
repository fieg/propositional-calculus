<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proposition;

use Fieg\PC\CompoundProposition;
use Fieg\PC\Proposition;

class AndX extends CompoundProposition
{
    public function __toString()
    {
        $propositions = array_map('strval', $this->propositions);

        $retval = implode(' ^ ', $propositions);

        return '(' . $retval . ')';
    }

    public function isDefiniteClauseBody()
    {
        foreach($this->getPropositions() as $proposition) {
            if (!$proposition instanceof Atomic) {
                return false;
            }
        }

        return true;
    }
}


