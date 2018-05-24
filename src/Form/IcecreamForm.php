<?php


namespace Drupal\thomas_more_icecream\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\State\StateInterface;
use Drupal\thomas_more_icecream\IcecreamManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IcecreamForm extends FormBase {

  protected $state;

  protected $IcecreamManager;

  protected $mailManager;

  public function __construct(StateInterface $state, IcecreamManager $IcecreamManager, MailManager $mailmanager) {
    $this->state = $state;
    $this->IcecreamManager = $IcecreamManager;
    $this->mailManager = $mailmanager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
      $container->get('thomas_more_icecream.icecream_manager'),
      $container->get('plugin.manager.mail')
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
      '#required' => TRUE,
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
      '#default_value' => 'vanille',
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
    $doorgaan = NULL;

    //Als ijs geselecteerd
    if ($type == "ijs") {
      $taste = $form_state->getValue('smaak');
      $topping = 'geen';
      if ($this->state->get('ijsTeller') != NULL) {
        $this->state->set('ijsTeller', $this->state->get('ijsTeller') + 1);
      }
      else {
        $this->state->set('ijsTeller', 1);
      }

      if ($this->state->get('ijsTeller') == $this->state->get('thomas_more_icecream.icecream_treshold')) {
        drupal_set_message('Maximum aantal ijsjes bereikt');
        $this->state->set('ijsTeller', 0);
        $doorgaan = "ijs";
      }
      else {
        drupal_set_message('Nieuw ijsje toegevoegd, aantal ijsjes ' . $this->state->get('ijsTeller') . '/' . $this->state->get('thomas_more_icecream.icecream_treshold'));
      }


    }

    //Als wafel geselecteerd
    if ($type == "wafel") {
      $topping = "";
      foreach ($form_state->getValue('topping') as $top) {
        $topping = $top . ',' . $topping;
      }
      $taste = 'geen';
      if ($this->state->get('wafelTeller') != NULL) {
        $this->state->set('wafelTeller', $this->state->get('wafelTeller') + 1);
      }
      else {
        $this->state->set('wafelTeller', 1);
      }

      if ($this->state->get('wafelTeller') == $this->state->get('thomas_more_icecream.waffles_treshold')) {
        drupal_set_message('Maximum aantal wafels bereikt');
        $this->state->set('wafelTeller', 0);
        $doorgaan = "wafels";
      }
      else {
        drupal_set_message('Nieuwe wafel toegevoegd, aantal wafels ' . $this->state->get('wafelTeller') . '/' . $this->state->get('thomas_more_icecream.waffles_treshold'));

      }


    }
    $this->IcecreamManager->addOption($type, $taste, $topping);

    if (isset($doorgaan)) {
      $tekst = "";
      switch ($doorgaan) {
        case("wafel"):
          $objecten = $this->IcecreamManager->getAllBestellingen('wafel');
          $tekst = "<p>Wafels</p>";
          if (count($objecten) > 1) {
            foreach ($objecten as $object) {
              $tekst .= "<p>" . $object->topping . "</p>";
            }
          }
          $this->IcecreamManager->deleteAll('wafel');
          break;
        case("ijs"):
          $objecten = $this->IcecreamManager->getAllBestellingen('ijs');
          $tekst = "<p>ijsjes</p>";
          if (count($objecten) > 1) {
            foreach ($objecten as $object) {
              $tekst .= "<p>" . $object->taste . "</p>";
            }
          }
          $this->IcecreamManager->deleteAll('ijs');
          break;
      }
      $module = "thomas_more_icecream";
      $key = 'send_mail';
      $to = 'jeroen.tubex@interacto.com';

      $params['message'] = $tekst;
      $send = TRUE;

      $this->mailManager->mail($module, $key, $to, 'nl', $params, NULL, $send);
      $doorgaan = NULL;
    }
  }
}