<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

use Fieg\PC\Proposition\Atomic;
use Fieg\PC\Proof\DeductionBottomUpStrategy;

class KnowledgeBaseTest extends \PHPUnit_Framework_TestCase
{
    public function testBuilder()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $a = new Atomic('a');
        $b = new Atomic('b');
        $c = new Atomic('c');
        $d = new Atomic('d');
        $e = new Atomic('e');
        $f = new Atomic('f');
        $g = new Atomic('g');

        $kb = new \Fieg\PC\KnowledgeBase();
        $kb->addProposition($pb->implies($a, $pb->andX($b, $c)));
        $kb->addProposition($b);
        $kb->addProposition($pb->andX($b, $c));
        $kb->addProposition(
            $pb->implies(
                $pb->orX(
                    $pb->notX($a),
                    $pb->andX($b, $c)
                ),
                $pb->orX(
                    $pb->andX(
                        $d,
                        $pb->notX($e)
                    ),
                    $f
                )
            )
        );

        $expected = array(
            'a <- (b ^ c)',
            'b',
            '(b ^ c)',
            '(¬(a) v (b ^ c)) <- ((d ^ ¬(e)) v f)',
        );

        $this->assertEquals($expected, array_map('strval', $kb->getPropositions()));
    }

    public function testProof()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $live_l1 = new Atomic('live_l1');
        $live_w0 = new Atomic('live_w0');
        $live_w1 = new Atomic('live_w1');
        $live_w2 = new Atomic('live_w2');
        $live_w3 = new Atomic('live_w3');
        $up_s1 = new Atomic('up_s1');
        $up_s2 = new Atomic('up_s2');
        $down_s1 = new Atomic('down_s1');
        $down_s2 = new Atomic('down_s2');

        $kb = new \Fieg\PC\KnowledgeBase();

        $props = array();
        $props[] = $pb->implies($live_l1, $live_w0);
        $props[] = $pb->implies($live_w0, $pb->andX($live_w1, $up_s2));
        $props[] = $pb->implies($live_w0, $pb->andX($live_w2, $down_s2));
        $props[] = $pb->implies($live_w1, $pb->andX($live_w3, $up_s1));
        $props[] = $pb->implies($live_w2, $pb->andX($live_w3, $down_s1));

        $props[] = $live_l1;
        $props[] = $down_s1;
        $props[] = $live_w3;

        $kb->addPropositions($props);

        $conclusion = $kb->proof(new DeductionBottomUpStrategy());

        $expected = array(
            $live_l1,
            $down_s1,
            $live_w3,
            $live_w2,
        );

        $this->assertEquals($expected, $conclusion);
    }

    public function testQuery()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $kb = new \Fieg\PC\KnowledgeBase();

        // atomic clauses
        $kb->addProposition($pb->atom('light_l1'));
        $kb->addProposition($pb->atom('light_l2'));
        $kb->addProposition($pb->atom('ok_l1'));
        $kb->addProposition($pb->atom('ok_l2'));
        $kb->addProposition($pb->atom('ok_cb1'));
        $kb->addProposition($pb->atom('ok_cb2'));
        $kb->addProposition($pb->atom('live_outside'));

        // rules
        $kb->addProposition($pb->implies($pb->atom('live_l1'), $pb->atom('live_w0')));
        $kb->addProposition($pb->implies($pb->atom('live_w0'), $pb->andX($pb->atom('live_w1'), $pb->atom('up_s2'))));
        $kb->addProposition($pb->implies($pb->atom('live_w0'), $pb->andX($pb->atom('live_w2'), $pb->atom('down_s2'))));
        $kb->addProposition($pb->implies($pb->atom('live_w1'), $pb->andX($pb->atom('live_w3'), $pb->atom('up_s1'))));
        $kb->addProposition($pb->implies($pb->atom('live_w2'), $pb->andX($pb->atom('live_w3'), $pb->atom('down_s1'))));
        $kb->addProposition($pb->implies($pb->atom('live_l2'), $pb->atom('live_w4')));
        $kb->addProposition($pb->implies($pb->atom('live_w4'), $pb->andX($pb->atom('live_w3'), $pb->atom('up_s3'))));
        $kb->addProposition($pb->implies($pb->atom('live_p1'), $pb->atom('live_w3')));
        $kb->addProposition($pb->implies($pb->atom('live_w3'), $pb->andX($pb->atom('live_w5'), $pb->atom('ok_cb1'))));
        $kb->addProposition($pb->implies($pb->atom('live_p2'), $pb->atom('live_w6')));
        $kb->addProposition($pb->implies($pb->atom('live_w6'), $pb->andX($pb->atom('live_w5'), $pb->atom('ok_cb2'))));
        $kb->addProposition($pb->implies($pb->atom('live_w5'), $pb->atom('live_outside')));
        $kb->addProposition($pb->implies($pb->atom('lit_l1'), $pb->andX($pb->atom('light_l1'), $pb->atom('live_l1'), $pb->atom('ok_l1'))));
        $kb->addProposition($pb->implies($pb->atom('lit_l2'), $pb->andX($pb->atom('light_l2'), $pb->atom('live_l2'), $pb->atom('ok_l2'))));

        // observations
        $kb->addProposition($pb->atom('down_s1'));
        $kb->addProposition($pb->atom('up_s2'));
        $kb->addProposition($pb->atom('up_s3'));

        extract($pb->getAtoms());

        /** @var $light_l1 \Fieg\PC\Proposition */
        $this->assertTrue($kb->query($light_l1));
        $this->assertFalse($kb->query($pb->atom('light_l6'))); // light_l6 is not in KB
        $this->assertTrue($kb->query($lit_l2));
    }

    public function testGetAtomByName()
    {
        $pb = new \Fieg\PC\PropositionBuilder();

        $kb = new \Fieg\PC\KnowledgeBase();
        $kb->addProposition($pb->atom('ok_cb1'));
        $kb->addProposition($pb->atom('ok_cb2'));
        $kb->addProposition($pb->implies($pb->atom('live_l1'), $pb->atom('live_w0')));
        $kb->addProposition($pb->implies($pb->atom('live_w0'), $pb->andX($pb->atom('live_w1'), $pb->atom('up_s2'))));
        $kb->addProposition($pb->implies($pb->atom('live_w0'), $pb->andX($pb->atom('live_w2'), $pb->atom('down_s2'))));

        $this->assertSame($pb->atom('live_w0'), $kb->getAtomByName('live_w0'));
        $this->assertSame($pb->atom('ok_cb1'), $kb->getAtomByName('ok_cb1'));
        $this->assertNull($kb->getAtomByName('atom_does_not_exist'));
    }
}
