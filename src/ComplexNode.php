<?php

namespace Drupal\TypeGraph;

class ComplexNode extends Node {
  protected $nodes;

  /**
   * Node constructor.
   * @param $value
   * @param array $nodes
   */
  public function __construct($value, array $nodes = []) {
    parent::__construct($value);
    $this->nodes = $nodes;
  }

  /**
   * @return array
   */
  public function getNodes() {
    return $this->nodes;
  }

  /**
   * @param $key
   * @return mixed
   */
  public function getNode($key) {
    return $this->nodes[$key];
  }

  /**
   * @param $key
   * @param $item
   * @return static
   */
  public function addNode($key, $item) {
    if (!$item instanceof static) {
      $item = new Node($item);
    }

    return new static($this->getValue(), [$key => $item] + $this->getNodes());
  }

  /**
   * @param $value
   * @return static
   */
  public function setValue($value) {
    return new static($value, $this->getNodes());
  }
}
