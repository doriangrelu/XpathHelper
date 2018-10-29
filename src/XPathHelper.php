<?php
/**
 * Created by PhpStorm.
 * User: doria
 * Date: 29/10/2018
 * Time: 13:20
 */

namespace Dorian\XPathHelper;


use Dorian\XPathHelper\Core\Queue;
use Dorian\XPathHelper\Exception\XPathHelperException;
use Dorian\XPathHelper\Interfaces\PredicateInterface;
use Dorian\XPathHelper\Interfaces\XPathAxesInterface;

class XPathHelper implements XPathAxesInterface
{
    /**
     * @var bool
     */
    private $_fullExpression;

    /**
     * @var null|XPathHelper
     */
    private $_fromXPathExpression = null;

    /**
     * @var null|string
     */
    private $_alliasNamespace = null;

    /**
     * @var null|string
     */
    private $_uriNamesapce = null;

    /**
     * @var bool
     */
    private $_relativeExpression;

    /**
     * @var string
     */
    private $_xmlFile;

    /**
     * @var Queue
     */
    private $_queue;

    private $_abbreviations = [
        'descendant-or-self' => '/',
        'child' => '',
        '@attribute' => '@',
    ];

    /**
     * XPathHelper constructor.
     * @param string $xmlFile
     * @param bool $relativeExpression
     * @param bool $fullExpression
     */
    public function __construct(string $xmlFile, bool $relativeExpression = false, bool $fullExpression = true)
    {
        $this->_xmlFile = $xmlFile;
        $this->_queue = new Queue();
        $this->_fullExpression = $fullExpression;
        $this->_relativeExpression = $relativeExpression;
    }

    /**
     * @param string $allias
     * @param string|null $uri
     * @return XPathHelper
     */
    public function setDefaultNamespace(string $allias, string $uri = null): self
    {
        $this->_alliasNamespace = $allias;
        $this->_uriNamesapce = $uri;
        return $this;
    }

    /**
     * @param XPathHelper $XPathExpression
     * @return XPathHelper
     */
    public function fromExpression(XPathHelper $XPathExpression): self
    {
        $this->_fromXPathExpression = $XPathExpression;
        $this->_relativeExpression = false;
        return $this;
    }

    /**
     * @return string
     * @throws XPathHelperException
     */
    public function __toString()
    {
        if (empty($this->_queue->getQueue())) {
            throw new XPathHelperException("Illegal empty XPath expression");
        }
        return ($this->_fromXPathExpression !== null ? $this->_fromXPathExpression->__toString() : '')
            . ($this->_relativeExpression ? './' : '/')
            . implode('/', $this->_queue->getQueue());
    }

    /**
     * @param string $axe
     * @param string $filter
     * @return string
     * @throws XPathHelperException
     */
    private function _getExpression(string $axe, string $filter): string
    {
        if (!$this->_axesExists($axe)) {
            throw new XPathHelperException("Missing axe: $axe");
        }
        if ($this->_fullExpression) {
            return $axe . '::' . $this->_getFilterWithNamespace($filter);
        }

        return $this->_abbreviations[$axe] . $this->_getFilterWithNamespace($filter);
    }

    /**
     * @param string $filter
     * @return string
     */
    private function _getFilterWithNamespace(string $filter): string
    {
        if (strpos($filter, ':') !== false && !is_null($this->_getNamesapce())) {
            return $this->_getNamesapce() . ':' . $filter;
        }
        return $filter;
    }

    private function _getNamesapce()
    {
        if (is_null($this->_uriNamesapce) && is_null($this->_alliasNamespace)) {
            return null;
        }
        if (!is_null($this->_alliasNamespace)) {
            return $this->_alliasNamespace;
        }

        return $this->_uriNamesapce;
    }

    /**
     * @param string $axe
     * @param bool $exception
     * @return bool
     * @throws XPathHelperException
     */
    private function _axesExists(string $axe, bool $exception = false): bool
    {
        $exists = isset($this->_abbreviations[$axe]);
        if ($exception && !$exists) {
            throw new XPathHelperException("Missing axe: $axe");
        }
        return $exists;
    }


    /**
     * @return mixed
     */
    public function evaluate(): mixed
    {

    }

    /**
     * @param string $filter
     * @return XPathHelper
     */
    public function children(string $filter): self
    {
        $this->_queue->push($this->_getExpression('child', $filter));
        return $this;
    }

    /**
     * @param string $filter
     * @return XPathHelper
     */
    public function descendantOrSelf(string $filter): self
    {
        $this->_queue->push($this->_getExpression('descendant-or-self', $filter));
        return $this;
    }

    /**
     * @param string $filter
     * @return XPathHelper
     */
    public function parent(string $filter): self
    {
        return $this;
    }

    /**
     * @param PredicateInterface $predicate
     * @return XPathHelper
     */
    public function addPredicate(PredicateInterface $predicate): self
    {
        return $this;
    }
}