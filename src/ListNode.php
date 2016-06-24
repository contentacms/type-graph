<?php

namespace Drupal\TypeGraph;

class ListNode extends Node {
  /**
   * @var mixed
   */
  protected $item;

  /**
   * Node constructor.
   * @param $value
   * @param $item
   */
  public function __construct($value, $item) {
    parent::__construct($value);
    $this->item = $item;
  }

  /**
   * @return mixed
   */
  public function getItem() {
    return $this->item;
  }

  /**
   * @param $value
   * @return static
   */
  public function setValue($value) {
    return new static($value, $this->getItem());
  }
}
