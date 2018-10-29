<?php
/**
 * Created by PhpStorm.
 * User: Dorian
 * Date: 29/10/2018
 * Time: 17:45
 */

namespace Dorian\XPathHelper;


use Dorian\XPathHelper\Exception\PredicateException;
use Dorian\XPathHelper\Interfaces\PredicateInterface;
use Dorian\XPathHelper\Interfaces\XPathAxesInterface;

/**
 * Class PredicateHelper
 * @package Dorian\XPathHelper
 */
class PredicateHelper implements PredicateInterface
{
    /**
     * @var array
     */
    private $_predicate;

    private $_nbExpressions = 0;
    private $_nbOperator = 0;
    private $_nbConds = 0;

    const P_OPERATOR = 'po';
    const P_EXPRESSION = 'pe';
    const P_CONDITION = "pc";

    private $_counters = [
        self::P_OPERATOR => 0,
        self::P_CONDITION => 0,
        self::P_EXPRESSION => 0,
    ];

    private $_queueTypes=[];

    private $_lastInserted = null;


    /**
     * @var string[]
     */
    private $_acceptsOperators = [
        '>',
        '<',
        '>=',
        '<=',
        '=',
    ];

    /**
     * @var string[]
     */
    private $_acceptsConditions = [
        'and',
        'or',
    ];

    /**
     * PredicateHelper constructor.
     */
    public function __construct()
    {
        $this->_predicate = [];
    }

    /**
     * @param XPathAxesInterface $XPathExpression
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function expression(XPathAxesInterface $XPathExpression): self
    {
        $this->_predicate[] = $XPathExpression->__toString();
        $this->_correctPredicateExpression();
        return $this;
    }

    /**
     * @param string $operator
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function operator(string $operator): self
    {
        if (!in_array($operator, $this->_acceptsOperators)) {
            throw new PredicateException("Illegal operator: $operator");
        }

        $this->_predicate[] = $operator;
        $this->_lastInserted = self::P_OPERATOR;
        $this->_correctPredicateExpression();
        return $this;
    }

    /**
     * @param bool $first
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function position(bool $first = false): self
    {
        if ($first) {
            $this->_predicate[] = 'position()=1';
            return $this;
        }
        $this->_predicate[] = 'position()';
        $this->_lastInserted = self::P_EXPRESSION;
        $this->_correctPredicateExpression();
        return $this;
    }

    /**
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function and(): self
    {
        $this->_predicate[] = ' and ';
        $this->_lastInserted = self::P_CONDITION;
        $this->_correctPredicateExpression();
        return $this;
    }


    /**
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function or(): self
    {
        $this->_predicate[] = ' or ';
        $this->_lastInserted = self::P_CONDITION;
        $this->_correctPredicateExpression();
        return $this;
    }

    /**
     * @param $value
     * @return PredicateHelper
     * @throws PredicateException
     */
    public function value($value): self
    {
        $this->_predicate[] = $value;
        $this->_lastInserted = self::P_EXPRESSION;
        $this->_correctPredicateExpression();
        return $this;
    }

    /**
     */
    private function _correctPredicateExpression()
    {

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '[' . implode('', $this->_predicate) . ']';
    }
}