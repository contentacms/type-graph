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

  public function testMapFloatAndString() {
    $stringDefinition = new DataDefinition([
      'type' => 'string',
    ]);
    $float_definition = new DataDefinition([
      'type' => 'float',
    ]);
    $definition = new MapDataDefinition([
      'type' => 'map',
    ], $stringDefinition);
    $definition->setPropertyDefinition('float_key', $float_definition);
    $definition->setPropertyDefinition('string_key', $stringDefinition);

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

  public function testDamnComplexAndNeverRealisticExample() {
    $entityDefinition = MapDataDefinition::createFromDataType('map');
    $fieldListDefinition = ListDataDefinition::createFromDataType('list');
    $stringDefinition = DataDefinition::createFromDataType('string');

    $fieldListDefinition->setItemDefinition($stringDefinition);
    $entityDefinition->setPropertyDefinition('field_name', $fieldListDefinition);

    $graphBuilder = $this->getGraphBuilder();
    $tree = $graphBuilder->buildNode($entityDefinition);

    $this->assertEquals($entityDefinition, $tree->getValue());
    $this->assertEquals($fieldListDefinition, $tree->getNode('field_name')->getValue());
    $this->assertEquals($stringDefinition, $tree->getNode('field_name')->getItem()->getValue());
  }

}
