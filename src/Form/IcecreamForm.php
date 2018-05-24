<?php


namespace Drupal\thomas_more_icecream\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IcecreamForm extends FormBase {

  protected $state;

  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }

  public function getFormId() {
    return "thomas_more_icecream_icecream_form";
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $typeoptions = [
      'ijs' => t('Ijs'),
      'wafel' => t('wafel'),
    ];
    $form['type'] = [
      "#type" => "radios",
      "#title" => "Keuze van desert",
      '#options' => $typeoptions,
      '#description' => t('Welke keuze wil je nemen'),
    ];

    $smaakoptions = [
      'vanille' => t('Vanille'),
      'aardbij' => t('Aardbij'),
      'chocolade' => t('Chocolade'),
    ];
    $form['smaak'] = [
      "#type" => "radios",
      "#title" => "Keuze van smaak",
      '#options' => $smaakoptions,
      '#description' => t('Welke smaak wil je nemen'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => [
            'value' => 'ijs',
          ],
        ],
      ],
    ];

    $toppingoptions = [
      'slagroom' => t('Slagroom'),
      'suiker' => t('Suiker'),
    ];
    $form['topping'] = [
      "#type" => "checkboxes",
      "#title" => "Keuze van topping",
      '#options' => $toppingoptions,
      '#description' => t('Welke topping wil je nemen'),
      '#states' => [
        'visible' => [
          ':input[name="type"]' => [
            'value' => 'wafel',
          ],
        ],
      ],
    ];


    $form['submit'] = [
      "#type" => "submit",
      "#value" => "Opslaan",
      "#button_type" => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->state->set('thomas_more_icecream_counter.type', $form_state->get('type'));
    if($form_state->get('type') == "ijs"){
      $this->state->set('thomas_more_icecream_counter.taste', $form_state->get('smaak'));
      $this->state->set('thomas_more_icecream_counter.topping', 'geen');
    }
    if($form_state->get('type') == "wafel"){
      $this->state->set('thomas_more_icecream_counter.topping', $form_state->get('topping'));
      $this->state->set('thomas_more_icecream_counter.taste', 'geen');

    }

    /*$this->connection->insert('thomas_more_icecream_counter')
      ->fields([
        'type' => $this->state->get('thomas_more_icecream_counter.type'),
        'taste' => $this->state->get('thomas_more_icecream_counter.taste'),
        'topping' => $this->state->get('thomas_more_icecream_counter.topping'),
      ])->execute();*/
  }
}