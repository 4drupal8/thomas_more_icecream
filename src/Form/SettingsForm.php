<?php


namespace Drupal\thomas_more_icecream\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsForm extends FormBase {

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
    return "thomas_more_icecream_settings_form";
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['icecream_treshold'] = [
      "#type" => "number",
      "#title" => "Icecream Treshold",
      "#default_value" => $this->state->get('thomas_more_icecream.icecream_treshold'),
    ];

    $form['waffles_treshold'] = [
      "#type" => "number",
      "#title" => "Waffle Treshold",
      "#default_value" => $this->state->get('thomas_more_icecream.waffles_treshold'),
    ];

    $form['submit'] = [
      "#type" => "submit",
      "#value" => "Opslaan",
      "#button_type" => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->state->set('thomas_more_icecream.icecream_treshold', $form_state->getValue('icecream_treshold'));
    $this->state->set('thomas_more_icecream.waffles_treshold', $form_state->getValue('waffles_treshold'));
    drupal_set_message("Is opgeslagen");
  }

}