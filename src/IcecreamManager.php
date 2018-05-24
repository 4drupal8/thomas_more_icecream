<?php

namespace Drupal\thomas_more_icecream;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;

class IcecreamManagerManager {

  protected $connection;

  protected $time;

  public function __construct(Connection $connection, TimeInterface $time) {
    $this->connection = $connection;
    $this->time = $time;
  }

  public function addOption(string $type, string $taste, string $topping) {
    $this->connection->insert('thomas_more_icecream_counter')
      ->fields([
        'type' => $type,
        'taste' => $taste,
        'topping' => $topping,
        'time_clicked' => $this->time->getRequestTime(),
      ])->execute();
  }

  public function getAll(string $type) {
    $query = $this->connection->select('thomas_more_icecream_counter', 't');
    $query->condition('t.type', $type);
    return (int) $query->countQuery()->execute()->fetchField();
  }

}
