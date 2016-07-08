<?php

namespace Drupal\Component\TypeGraph;

interface NodeVisitorInterface {
  /**
   * Called before child nodes are visited.
   *
   * @param NodeInterface $node
   *  The node to visit
   *
   * @return NodeInterface
   *  The modified node
   */
  public function enterNode(NodeInterface $node);

  /**
   * Called after child nodes are visited.
   *
   * @param NodeInterface $node
   *  The node to visit
   *
   * @return NodeInterface|false The modified node or false if the node must be removed
   */
  public function leaveNode(NodeInterface $node);

}
