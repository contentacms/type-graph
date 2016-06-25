<?php

namespace Drupal\Tests\Component\TypeGraph;
use Drupal\Component\TypeGraph\Node;
use Drupal\Component\TypeGraph\NodeInterface;

/**
 * @coversDefaultClass \Drupal\Component\TypeGraph\Node
 */
class NodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::setValue
     * @dataProvider valueProvider
     */
    public function testSetValue($value) {
      $node = $this->getNode()->setValue($value);
      $this->assertInstanceOf(NodeInterface::class, $node);
      $this->assertSame($value, $node->getValue());
    }

    /**
     * @covers ::getValue
     * @dataProvider valueProvider
     */
    public function testGetValue($value) {
      $node = $this->getNode($value);
      $this->assertSame($value, $node->getValue());
    }

    /**
     * Data provider for the setValue and getValue test.
     *
     * @return array
     *   The data collection.
     */
    public function valueProvider() {
      return [[1], [NULL], ['1'], [''], [['foo']], [[]]];
    }

    /**
     * Build an empty node.
     *
     * @param mixed $value
     *   Initial value.
     *
     * @return \Drupal\Component\TypeGraph\NodeInterface
     */
    protected function getNode($value = NULL) {
      return new Node($value);
    }

}
