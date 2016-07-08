<?php

namespace Drupal\Component\TypeGraph;

class Node implements NodeInterface {
  /**
   * @var mixed
   */
  protected $value;

  /**
   * Node constructor.
   * @param $value
   */
  public function __construct($value) {
    $this->value = $value;
  }

  /**
   * @param $value
   * @return static
   */
  public function setValue($value) {
    return new static($value);
  }

  /**
   * @return mixed
   */
  public function getValue() {
    return $this->value;
  }
}
