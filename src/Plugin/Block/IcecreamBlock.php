<?php

namespace Drupal\thomas_more_icecream\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\thomas_more_icecream\ClickManager;
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

  protected $clickManager;

  protected $state;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, StateInterface $state, ClickManager $clickManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->clickManager = $clickManager;
    $this->state = $state;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state'),
      $container->get('thomas_more_icecream.click_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return;
  }
}
