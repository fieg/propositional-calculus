<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC\Proposition;

use Fieg\PC\CompoundProposition;
use Fieg\PC\Proposition;

class Implies extends CompoundProposition
{
    protected $head;
    protected $body;

    public function __construct(Proposition $p, Proposition $q)
    {
        $this->head = $p;
        $this->body = $q;

        $this->addProposition($p);
        $this->addProposition($q);
    }

    /**
     * @return \Fieg\PC\Proposition
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * @return \Fieg\PC\Proposition
     */
    public function getBody()
    {
        return $this->body;
    }

    public function __toString()
    {
        $head = (string) $this->head;
        $body = (string) $this->body;

        return sprintf('%s <- %s', $head, $body);
    }
}


