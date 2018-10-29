<?php
/**
 * Created by PhpStorm.
 * User: Dorian
 * Date: 29/10/2018
 * Time: 18:10
 */

namespace Dorian\Tests;


use Dorian\XPathHelper\PredicateHelper;
use PHPUnit\Framework\TestCase;

class PredicateHelperTest extends TestCase
{
    /**
     * @throws \Dorian\XPathHelper\Exception\PredicateException
     */
    public function testSimplePredicatePosition()
    {
        $predicate = new PredicateHelper();
        $predicate->position(true);
        $this->assertEquals("[position()=1]", $predicate->__toString());
        $predicate = new PredicateHelper();
        $predicate->position()
            ->operator('<')
            ->value(150);
        $this->assertEquals("[position()<150]", $predicate->__toString());
    }

    /**
     * @throws \Dorian\XPathHelper\Exception\PredicateException
     */
    public function testOperators()
    {
        $predicate = new PredicateHelper();
        $predicate->position()
            ->operator('<')
            ->value(150)
            ->and()
            ->position()
            ->operator('>')
            ->value(150)
            ->and()
            ->position()
            ->operator('<=')
            ->value(150);
        $this->assertEquals('[position()<150 and position()>150 and position()<=150]', $predicate->__toString());
    }

}