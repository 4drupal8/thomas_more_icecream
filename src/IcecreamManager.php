<?php

namespace Drupal\thomas_more_icecream;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;

class IcecreamManager {

  protected $connection;


  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function addOption(string $type, string $taste, string $topping) {
    $this->connection->insert('thomas_more_icecream_counter')
      ->fields([
        'type' => $type,
        'taste' => $taste,
        'topping' => $topping,
      ])->execute();
  }

  public function getAll(string $type) {
    $query = $this->connection->select('thomas_more_icecream_counter', 't');
    $query->condition('t.type', $type);
    return (int) $query->countQuery()->execute()->fetchField();
  }

}
