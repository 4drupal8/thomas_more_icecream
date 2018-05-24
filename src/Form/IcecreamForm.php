<?php


namespace Drupal\thomas_more_icecream\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\thomas_more_icecream\IcecreamManager;

class IcecreamForm extends FormBase {

  protected $state;
  protected $IcecreamManager;

  public function __construct(StateInterface $state, IcecreamManager $IcecreamManager) {
    $this->state = $state;
    $this->IcecreamManager = $IcecreamManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
      $container->get('thomas_more_icecream.icecream_manager')
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
    $type = $form_state->getValue('type');
    if($type == "ijs"){
      $taste = $form_state->getValue('smaak');
      $topping = 'geen';
    }

    if($type == "wafel"){
      $topping = "";
      foreach($form_state->getValue('topping') as $top){
        $topping += $top;
      }
      $taste ='geen';
    }
    $this->IcecreamManager->addOption($type,$taste,$topping);
  }
}