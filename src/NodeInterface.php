<?php

namespace Drupal\TypeGraph;

interface NodeInterface {

  /**
   * @param $value
   * @return static
   */
  public function setValue($value);

  /**
   * @return mixed
   */
  public function getValue();
}
