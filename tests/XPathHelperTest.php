<?php
/**
 * Created by PhpStorm.
 * User: doria
 * Date: 29/10/2018
 * Time: 15:27
 */

namespace Dorian\Tests;


use Dorian\XPathHelper\Exception\XPathHelperException;
use Dorian\XPathHelper\PredicateHelper;
use Dorian\XPathHelper\XPathHelper;
use PHPUnit\Framework\TestCase;

class XPathHelperTest extends TestCase
{
    /**
     * @throws XPathHelperException
     */
    public function testMakeXPathExpression()
    {
        $helper = new XPathHelper('');
        $helper2 = new XPathHelper('', false);
        $helper2->children('tom');
        $helper->fromExpression($helper2);
        $this->assertEquals("/child::tom/child::sam/descendant-or-self::test", $helper->children('sam')->descendantOrSelf('test')->__toString());
    }

    /**
     * @throws XPathHelperException
     */
    public function testSimpleExpressionRelative()
    {
        $helper = new XPathHelper('', true);
        $helper->children('sam')
            ->descendantOrSelf('dorian');
        $this->assertEquals("./child::sam/descendant-or-self::dorian", $helper->__toString());
    }

    /**
     * @throws XPathHelperException
     */
    public function testNamespaceMecanism()
    {
        $helper = new XPathHelper('', true);
        $helper->setDefaultNamespace('p', 'http://test.fr')
            ->children('sam')
            ->descendantOrSelf('dorian');
        $this->assertEquals("./child::p:sam/descendant-or-self::p:dorian", $helper->__toString());
    }

    /**
     * @throws XPathHelperException
     */
    public function testAbbreviations()
    {
        $helper = new XPathHelper('', false, false);
        $helper->setDefaultNamespace('p', 'http://test.fr')
            ->children('sam')
            ->descendantOrSelf('dorian');
        $this->assertEquals("/p:sam//p:dorian", $helper->__toString());
    }

    /**
     * @throws XPathHelperException
     */
    public function testEmptyExpressionReaction()
    {
        $this->expectException(XPathHelperException::class);
        $helper = new XPathHelper('');
        $helper->__toString();
    }

    /**
     * @throws XPathHelperException
     * @throws \Dorian\XPathHelper\Exception\PredicateException
     */
    public function testWithPredicate()
    {
        $helper = new XPathHelper('', false, false);
        $helper->setDefaultNamespace('p', 'http://test.fr')
            ->children('sam')
            ->descendantOrSelf('dorian')
            ->addPredicate((new PredicateHelper())->position(true));
        $this->assertEquals('/p:sam//p:dorian[1]',$helper->__toString());

        $helper = new XPathHelper('', false, true);
        $helper->setDefaultNamespace('p', 'http://test.fr')
            ->children('sam')
            ->descendantOrSelf('dorian')
            ->addPredicate((new PredicateHelper())->position(true));
        $this->assertEquals('/child::p:sam/descendant-or-self::p:dorian[position()=1]',$helper->__toString());

    }

}