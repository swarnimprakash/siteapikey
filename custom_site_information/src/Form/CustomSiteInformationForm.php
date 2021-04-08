<?php
namespace Drupal\custom_site_information\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


/**
 * Implements an example form.
 */
class CustomSiteInformationForm extends SiteInformationForm {

  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('system.site');
		$default_value = !empty($config->get('siteapikey')) ? $config->get('siteapikey') : 'No API Key yet';
		$form =  parent::buildForm($form, $form_state);
		
		$form['api_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Set Site API Key'),
    ];
		
		$form['api_fieldset']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $default_value,
			'#description' => t("Field to set Site API Key"),
		];
		
		return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->config('system.site')
		  ->set('siteapikey', $form_state->getValue('siteapikey'))
		  ->save();
			
		// Add message to show Site API Key updated
		$msg = !empty($form_state->getValue('siteapikey')) ? 'Successfully updated site API key - '.$form_state->getValue('siteapikey') : 'Successfully updated site API key';
    drupal_set_message( $msg );
		
		parent::submitForm($form, $form_state);
	}
}