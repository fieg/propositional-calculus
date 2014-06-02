<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC;

use Fieg\PC\Proposition\AndX;
use Fieg\PC\Proposition\Atomic;
use Fieg\PC\Proposition\Implies;
use Fieg\PC\Proposition\NotX;
use Fieg\PC\Proposition\OrX;

class PropositionBuilder
{
    /**
     * Atom index
     *
     * Contains atoms that were created using this builder
     *
     * @var Atomic[]
     */
    protected $atoms = array();

    /**
     * Returns an atom. When called twice with the same name
     * the first atom that was created is returned.
     *
     * @param string $name
     * @return Atomic
     */
    public function atom($name)
    {
        if (false === isset($this->atoms[$name])) {
            $this->atoms[$name] = new Atomic($name);
        }

        return $this->atoms[$name];
    }

    /**
     * @return Atomic[]
     */
    public function getAtoms()
    {
        return $this->atoms;
    }

    /**
     * @param $p
     * @param $q
     * @return Implies
     */
    public function implies($p, $q)
    {
        return new Implies($p, $q);
    }

    /**
     * @param $args
     * @return AndX
     */
    public function andX($args)
    {
        $args = func_get_args();

        return new AndX($args);
    }

    /**
     * @param $args
     * @return OrX
     */
    public function orX($args)
    {
        $args = func_get_args();

        return new OrX($args);
    }

    /**
     * @param $p
     * @return NotX
     */
    public function notX($p)
    {
        return new NotX($p);
    }
}


