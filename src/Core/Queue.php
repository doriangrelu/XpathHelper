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

    /**
     * Cue constructor.
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        $this->_cue = array_reverse($array);
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

    /**
     * @return array
     */
    public function getQueue():array
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
        $element = $this->_queue[$this->_index] ?? null;
        if (isset($this->_queue[$this->_index])) {
            $this->_queue[$this->_index] = null;
            $this->_index++;
        }
        return $element;
    }

}