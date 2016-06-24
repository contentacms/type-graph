<?php

namespace Drupal\Component\TypeGraph;

use Drupal\Core\TypedData\ComplexDataDefinitionInterface;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\ListDataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;

class GraphBuilder {
  /**
   * @var TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * GraphBuilder constructor.
   */
  public function __construct(TypedDataManagerInterface $typedDataManager) {
    $this->typedDataManager = $typedDataManager;
  }

  /**
   * @return ComplexNode
   */
  public function build() {
    $properties = array_map(function ($item) {
      return $this->buildNode($item);
    }, $this->typedDataManager->getDefinitions());

    return new ComplexNode(NULL, $properties);
  }

  /**
   * @param DataDefinitionInterface $definition
   * @return ComplexNode|ListNode|Node
   */
  public function buildNode(DataDefinitionInterface $definition) {
    if ($definition instanceof ListDataDefinitionInterface) {
      return new ListNode($definition, $this->buildNode($definition->getItemDefinition()));
    }

    if ($definition instanceof ComplexDataDefinitionInterface) {
      $nodes = array_map(function ($item) {
        return $this->buildNode($item);
      }, $definition->getPropertyDefinitions());

      return new ComplexNode($definition, $nodes);
    }

    return new Node($definition);
  }
}
