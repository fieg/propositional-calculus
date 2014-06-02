<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\PC;

use Fieg\PC\Proof\DeductionBottomUpStrategy;
use Fieg\PC\Proof\ProofStrategyInterface;
use Fieg\PC\Proposition\AndX;
use Fieg\PC\Proposition\Atomic;

class KnowledgeBase
{
    protected $propositions;
    protected $proofStrategy;

    /**
     * Constructor.
     *
     * @param ProofStrategyInterface $proofStrategy a default proof strategy
     */
    public function __construct(ProofStrategyInterface $proofStrategy = null)
    {
        if (null === $proofStrategy) {
            $proofStrategy = new DeductionBottomUpStrategy();
        }

        $this->proofStrategy = $proofStrategy;
    }

    /**
     * @param Proposition $proposition
     */
    public function addProposition(Proposition $proposition)
    {
        $this->propositions[] = $proposition;
    }

    /**
     * @param Proposition[] $propositions
     */
    public function addPropositions(array $propositions)
    {
        foreach($propositions as $proposition) {
            $this->addProposition($proposition);
        }
    }

    /**
     * @return mixed
     */
    public function getPropositions()
    {
        return $this->propositions;
    }

    /**
     * @param string $name
     * @param null|Proposition[] $propositions
     * @return Atomic|null
     */
    public function getAtomByName($name, array $propositions = null)
    {
        if (null === $propositions) {
            $propositions = $this->getPropositions();
        }

        foreach($propositions as $proposition) {
            if ($proposition instanceof Atomic && $name === $proposition->getName()) {
                return $proposition;
            } elseif ($proposition instanceof CompoundProposition) {
                $result = $this->getAtomByName($name, $proposition->getPropositions());
                if (null !== $result) {
                    return $result;
                }
            }
        }

        return null;
    }

    /**
     * @param ProofStrategyInterface $strategy
     * @throws \InvalidArgumentException
     * @return Proposition[]
     */
    public function proof(ProofStrategyInterface $strategy = null)
    {
        if (null === $strategy) {
            $strategy = $this->proofStrategy;
        }

        return $strategy->proof($this->getPropositions());
    }

    /**
     * @param Proposition $body
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function query(Proposition $body)
    {
        if (!$body instanceof Atomic && !$body instanceof AndX) {
            throw new \InvalidArgumentException(sprintf('Body can only be an instance of Atomic or AndX, %s given', get_class($body)));
        }

        if ($body instanceof AndX) {
            if (false === $body->isDefiniteClauseBody()) {
                throw new \InvalidArgumentException(
                    'Given AndX is not a valid body because it contains compound propositions, '.
                    'it should only contain atomic propositions'
                );
            }
        }

        if ($body instanceof Atomic) {
            $proof = $this->proof();

            return in_array($body, $proof);
        } else if ($body instanceof AndX) {
            foreach($body->getPropositions() as $proposition) {
                if (false === $this->query($proposition)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
