<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

class PropositionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuilder()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $a = new \Fieg\PC\Proposition\Atomic('a');
        $b = new \Fieg\PC\Proposition\Atomic('b');

        $this->assertInstanceOf('Fieg\PC\Proposition\Implies', $pb->implies($a, $b));
        $this->assertInstanceOf('Fieg\PC\Proposition\AndX', $pb->andX($a, $b));
        $this->assertInstanceOf('Fieg\PC\Proposition\OrX', $pb->orX($a, $b));
        $this->assertInstanceOf('Fieg\PC\Proposition\Atomic', $pb->atom('thingy'));
        $this->assertInstanceOf('Fieg\PC\Proposition\NotX', $pb->notX($a));
    }

    public function testAtomUniquness()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $atom = $pb->atom('light_1');
        $atom2 = $pb->atom('light_1');
        $atom3 = $pb->atom('light_2');

        $this->assertSame($atom, $atom2);
        $this->assertNotSame($atom2, $atom3);
    }

    public function testExtract()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $pb->atom('light_1');
        $pb->atom('light_1');
        $pb->atom('light_2');

        $this->assertFalse(isset($light_1));

        extract($pb->getAtoms());

        $this->assertInstanceOf('Fieg\PC\Proposition\Atomic', $light_1);
        $this->assertFalse(isset($light_0));
    }
}
