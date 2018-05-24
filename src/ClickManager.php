<?php

namespace Drupal\thomas_more_social_media;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;

class ClickManager {

  protected $connection;

  protected $time;

  public function __construct(Connection $connection, TimeInterface $time) {
    $this->connection = $connection;
    $this->time = $time;
  }

  public function addClick(string $network) {
    $this->connection->insert('thomas_more_icecream_counter')
      ->fields([
        'network' => $network,
        'time_clicked' => $this->time->getRequestTime(),
      ])->execute();
  }

  public function getClicks(string $network) {
    $query = $this->connection->select('thomas_more_icecream_counter', 't');
    $query->condition('t.network', $network);
    return (int) $query->countQuery()->execute()->fetchField();
  }

  public function removeClicks(string $network) {
    $query = $this->connection->delete('thomas_more_icecream_counter');
    $query->condition('network', $network);
    return $query->execute();
  }

}
