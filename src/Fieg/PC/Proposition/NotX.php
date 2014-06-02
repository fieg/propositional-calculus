<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proposition;

use Fieg\PC\CompoundProposition;
use Fieg\PC\Proposition;

class NotX extends CompoundProposition
{
    public function __construct(Proposition $p)
    {
        $this->propositions = array($p);
    }

    public function __toString()
    {
        $propositions = array_map('strval', $this->propositions);

        $retval = implode('', $propositions);

        return '¬(' . $retval . ')';
    }
}


