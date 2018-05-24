<?php

namespace Drupal\thomas_more_icecream\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a Icecream menu block.
 *
 * @Block(
 *  id = "thomas_more_icecream_block",
 *  admin_label = @Translation("Ice Cream Block"),
 * )
 */
class IcecreamBlock extends BlockBase implements ContainerFactoryPluginInterface {


  protected $connection;
  protected $state;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->connection = $connection;
    $this->state = $state;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('state')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\thomas_more_icecream\Form\IcecreamForm');
    return $form;
  }
}
