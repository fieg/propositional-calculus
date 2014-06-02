<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proposition;

use Fieg\PC\CompoundProposition;

class OrX extends CompoundProposition
{
    public function __toString()
    {
        $propositions = array_map('strval', $this->propositions);

        $retval = implode(' v ', $propositions);

        return '(' . $retval . ')';
    }
}
