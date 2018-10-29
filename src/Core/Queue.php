<?php
/**
 * Created by PhpStorm.
 * User: doria
 * Date: 29/10/2018
 * Time: 13:25
 */

namespace Dorian\XPathHelper\Core;


class Queue
{

    /**
     * @var int
     */
    private $_index = 0;

    /**
     * @var array
     */
    private $_queue = [];

    private $_reversePop;

    /**
     * Cue constructor.
     * @param array $array
     * @param bool $reversePop
     */
    public function __construct(array $array = [], $reversePop = false)
    {
        if ($reversePop) {
            $array = array_reverse($array);
        }
        $this->_queue = $array;
        $this->_reversePop = $reversePop;

    }

    /**
     * @return bool
     */
    public function notEmpty(): bool
    {
        return !empty($this->_queue);
    }

    /**
     * @param mixed $element
     * @return Queue
     */
    public function push($element): self
    {
        $this->_queue[] = $element;
        return $this;
    }

    public function pushAndMerge($element, $join = ''):self
    {
        $index = count($this->_queue) - 1;
        $this->_queue[$index] = $this->_queue[$index] . $join . $element;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueue(): array
    {
        return $this->_queue;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        if (empty($this->_queue)) {
            return null;
        }
        return ($this->_reversePop ? $this->_reversePop() : $this->_normalPop());
    }

    private function _reversePop()
    {
        $element = $this->_queue[$this->_index] ?? null;
        if (isset($this->_queue[$this->_index])) {
            $this->_queue[$this->_index] = null;
            $this->_index++;
        }
        return $element;
    }

    private function _normalPop()
    {
        return array_pop($this->_queue);
    }

}