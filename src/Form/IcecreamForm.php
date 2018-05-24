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
    $typeoptions = array(
      'ijs' => t('Ijs'),
      'wafel' => t('wafel'),
    );
    $form['type'] = [
      "#type" => "radios",
      "#title" => "Keuze van desert",
      '#options' => $typeoptions,
      '#description' => t('Welke keuze wil je nemen'),
    ];

    $smaakoptions = array(
      'vanille' => t('Vanille'),
      'aardbij' => t('Aardbij'),
      'chocolade' => t('Chocolade'),
    );
    $form['smaak'] = [
      "#type" => "radios",
      "#title" => "Keuze van smaak",
      '#options' => $smaakoptions,
      '#description' => t('Welke smaak wil je nemen'),
    ];

    $toppingsoptions = array(
      'slagroom' => t('Slagroom'),
      'suiker' => t('Suiker'),
    );
    $form['toppings'] = [
      "#type" => "checkboxes",
      "#title" => "Keuze van topping",
      '#options' => $toppingsoptions,
      '#description' => t('Welke topping wil je nemen'),
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
    }

    /*$this->state->set('thomas_more_icecream.taste', $form_state->get('smaak'));
    $this->state->set('thomas_more_icecream.topping', $form_state->get('toppings'));*/

  }

}