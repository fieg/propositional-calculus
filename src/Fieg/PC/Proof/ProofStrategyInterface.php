<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proof;

interface ProofStrategyInterface 
{
    public function proof(array $propositions);
}
