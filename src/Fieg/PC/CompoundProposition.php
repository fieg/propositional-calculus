<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC;

abstract class CompoundProposition extends Proposition
{
    protected $propositions;

    public function __construct(array $args)
    {
        foreach($args as $arg) {
            if (!$arg instanceof Proposition) {
                throw new \InvalidArgumentException('arg is not a valid proposition');
            }

            $this->addProposition($arg);
        }
    }

    /**
     * @param Proposition $proposition
     * @return $this
     */
    public function addProposition(Proposition $proposition)
    {
        $this->propositions[] = $proposition;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPropositions()
    {
        return $this->propositions;
    }
}
