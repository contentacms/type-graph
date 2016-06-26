<?php

namespace Drupal\Tests\Component\TypeGraph;

use Drupal\Component\TypeGraph\GraphBuilder;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;
use Drupal\Core\TypedData\TypedDataManagerInterface;

/**
 * @coversDefaultClass \Drupal\Component\TypeGraph\GraphBuilder
 */
class GraphBuilderTest extends \PHPUnit_Framework_TestCase {

  protected function getGraphBuilder() {
    $typedDataManager = $this->prophesize(TypedDataManagerInterface::class);
    $graphBuilder = new GraphBuilder($typedDataManager->reveal());
    return $graphBuilder;
  }

  /**
   * @covers ::buildNode
   */
  public function testString() {
    $definition = new DataDefinition([
      'type' => 'string',
    ]);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($definition);

    /** @var \Drupal\Core\TypedData\DataDefinition $value */
    $value = $tree->getValue();
    $this->assertEquals('string', $value->getDataType());
  }

  /**
   * @covers ::buildNode
   */
  public function testFloat() {
    $definition = new DataDefinition([
      'type' => 'float',
    ]);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($definition);

    /** @var \Drupal\Core\TypedData\DataDefinition $value */
    $value = $tree->getValue();
    $this->assertEquals('float', $value->getDataType());
  }

  /**
   * @covers ::buildNode
   */
  public function testListOfFloats() {
    $sub_definition = new DataDefinition([
      'type' => 'string',
    ]);
    $definition = new ListDataDefinition([
      'type' => 'list',
    ], $sub_definition);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($definition);

    /** @var \Drupal\Core\TypedData\DataDefinition $value */
    $value = $tree->getValue();
    $this->assertEquals('list', $value->getDataType());

    $value = $tree->getItem();
    $this->assertEquals('string', $value->getValue()->getDataType());
  }

  /**
   * @covers ::buildNode
   */
  public function testMapFloatAndString() {
    $string_definition = new DataDefinition([
      'type' => 'string',
    ]);
    $float_definition = new DataDefinition([
      'type' => 'float',
    ]);
    $definition = new MapDataDefinition([
      'type' => 'map',
    ], $string_definition);
    $definition->setPropertyDefinition('float_key', $float_definition);
    $definition->setPropertyDefinition('string_key', $string_definition);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($definition);

    /** @var \Drupal\Core\TypedData\DataDefinition $value */
    $value = $tree->getValue();
    $this->assertEquals('map', $value->getDataType());

    $children = $tree->getNodes();
    $this->assertCount(2, $children);

    $child = $tree->getNode('float_key');
    $this->assertEquals('float', $child->getValue()->getDataType());

    $child = $tree->getNode('string_key');
    $this->assertEquals('string', $child->getValue()->getDataType());
  }

  /**
   * @covers ::buildNode
   */
  public function testDamnComplexAndNeverRealisticExample() {
    $entity_definition = MapDataDefinition::createFromDataType('map');
    $field_list_definition = ListDataDefinition::createFromDataType('list');
    $string_definition = DataDefinition::createFromDataType('string');

    $field_list_definition->setItemDefinition($string_definition);
    $entity_definition->setPropertyDefinition('field_name', $field_list_definition);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($entity_definition);

    $this->assertEquals($entity_definition, $tree->getValue());
    $this->assertEquals($field_list_definition, $tree->getNode('field_name')->getValue());
    $this->assertEquals($string_definition, $tree->getNode('field_name')->getItem()->getValue());
  }

}
