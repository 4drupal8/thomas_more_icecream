<?php

namespace Drupal\thomas_more_icecream;

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

  public function getAllBestellingen(string $type) {
    $query = $this->connection->select('thomas_more_icecream_counter', 't');
    $query->condition('t.type', $type);
    $query->fields('t');
    return $query->execute()->fetchAll();
  }

  public function getAll(string $type) {
    $query = $this->connection->select('thomas_more_icecream_counter', 't');
    $query->condition('t.type', $type);
    return (int) $query->countQuery()->execute()->fetchField();
  }

  public function deleteAll(string $type) {
    $this->connection->delete('thomas_more_icecream_counter')
      ->condition('type', $type)
      ->execute();
  }

}
