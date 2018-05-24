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

    if (1 == 1) {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = "thomas_more_icecream";
      $key = 'send_mail';
      $to = 'neil2223@hotmail.com';
      $objecten = $this->getAllBestellingen('ijs');
      $tekst = "<p>ijsjes</p>";
      if (count($objecten) > 1) {
        foreach ($objecten as $object) {
          $tekst .= "<p>" . $object->taste . "</p>";
        }
      }
      $params['message'] = $tekst;
      $send = TRUE;
      var_dump($this->getAllBestellingen('ijs'));

      $result = $mailManager->mail($module, $key, $to, 'nl', $params, NULL, $send);
      if ($result['result'] !== TRUE) {
        drupal_set_message(t('propblem'), 'error');
      }
    }
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

}
