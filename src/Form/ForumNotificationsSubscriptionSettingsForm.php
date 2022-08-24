<?php

namespace Drupal\forum_notifications_subscription\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
Use Drupal\taxonomy\Entity\Vocabulary;

class ForumNotificationsSubscriptionSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'forum_notifications_subscription_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['forum_notifications_subscription_settings'];
  }

  /*
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('forum_notifications_subscription.settings');
    $settings = $config->get('settings');
    $dropdown_array = [
      'Single Emails' => 'Single Emails',
      'Daily Digest Emails' => 'Daily Digest Emails',
    ];

    $form['forum_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Forum options settings'),
    ];
    $form['forum_settings']['forum_label_on'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the label of the forum subscription button.'),
      '#default_value' => $settings['forum_label_on'] ?? 'Subscribe to this forum',
      '#required' => TRUE,
    ];
    $form['forum_settings']['forum_label_off'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the label of the forum unsubscription button.'),
      '#default_value' => $settings['forum_label_off'] ?? 'Unsubscribe from this forum',
      '#required' => TRUE,
    ];
    $form['forum_settings']['email_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Email settings'),
    ];
    $form['forum_settings']['email_settings']['info'] = [
      '#type' => 'markup',
      '#markup' => $this->t("<div>Allowed replacement tokens are: 
            <ul><li>name</li><li>name_of_poster</li><li>forum_topic</li><li>forum</li><li>link_to_post</li><li>link_to_account</li></ul>
            Usage: Using [name] or [name_of_poster] in the subject or message, the tokens will be replaced by the user account name and the 
            post owner name respectively.</div>"),
    ];
    $form['forum_settings']['email_settings']['post_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#description' => $this->t('The email subject.'),
      '#default_value' => $settings['post_subject'] ?? 'New discussion added on the "Site name" website',
      '#required' => TRUE,
    ];
    $form['forum_settings']['email_settings']['post_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('The email body.'),
      '#default_value' => $settings['post_message'] ?? NULL,
      '#required' => TRUE,
      '#resizeable' => TRUE,
    ];
    $form['forum_settings']['email_settings']['forum_default_frequency'] = [
      '#type' => 'select',
      '#title' => $this->t('Forum email default frequency'),
      '#description' => $this->t('The default frequency for forum email notification. * Single Emails (To send directly the notification when a forum topic is added), * Daily Digest Emails (This option will send emails at the middle night of each user time zone)'),
      '#default_value' => $settings['forum_default_frequency'] ?? NULL,
      '#options' => $dropdown_array,
      '#required' => TRUE,
    ];

    $form['topic_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Forum topic options settings'),
    ];
    $form['topic_settings']['topic_label_on'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the label of the topic subscription button.'),
      '#default_value' => $settings['topic_label_on'] ?? 'Subscribe to this forum topic',
      '#required' => TRUE,
    ];
    $form['topic_settings']['topic_label_off'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter the label of the topic unsubscription button.'),
      '#default_value' => $settings['topic_label_off'] ?? 'Unsubscribe from this forum topic',
      '#required' => TRUE,
    ];
    $form['topic_settings']['email_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Email settings'),
    ];
    $form['topic_settings']['email_settings']['info'] = [
      '#type' => 'markup',
      '#markup' => $this->t("<div>Allowed replacement tokens are: 
            <ul><li>name</li><li>name_of_poster</li><li>forum_topic</li><li>link_to_topic</li><li>comment_body</li><li>comment_link</li>
            <li>topic_hyperlink</li><li>comment_hyperlink</li><li>system_email</li><li>link_to_account</li></ul>
            Usage: Using [name] or [name_of_poster] in the subject or message, the tokens will be replaced by the user account name and the 
            post owner name respectively.</div>"),
    ];
    $form['topic_settings']['email_settings']['comment_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#description' => $this->t('The email subject.'),
      '#default_value' => $settings['comment_subject'] ?? 'New comment on the "Site name" website',
      '#required' => TRUE,
    ];
    $form['topic_settings']['email_settings']['comment_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('The email body.'),
      '#default_value' => $settings['comment_message'] ?? NULL,
      '#required' => TRUE,
      '#resizeable' => TRUE,
    ];
    $form['topic_settings']['email_settings']['topic_default_frequency'] = [
      '#type' => 'select',
      '#title' => $this->t('Forum topic email default frequency'),
      '#description' => $this->t('The default frequency for forum topic email notification. * Single Emails (To send directly the notification when a forum topic is commented), * Daily Digest Emails (This option will send emails at the middle night of each user time zone)'),
      '#default_value' => $settings['topic_default_frequency'] ?? NULL,
      '#options' => $dropdown_array,
      '#required' => TRUE,
    ];
    $form['global'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Global settings'),
    ];
    $form['global']['cron'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Send single emails when cron run'),
      '#description' => $this->t('Send single emails when cron run, this is useful when you want to avoid slow page saving.'),
      '#default_value' => $settings['cron'] ?? FALSE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /*
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory()
      ->getEditable('forum_notifications_subscription.settings');
    $config->set('settings.forum_label_on', $form_state->getValue('forum_label_on'));
    $config->set('settings.forum_label_off', $form_state->getValue('forum_label_off'));
    $config->set('settings.post_subject', $form_state->getValue('post_subject'));
    $config->set('settings.post_message', $form_state->getValue('post_message'));
    $config->set('settings.topic_label_on', $form_state->getValue('topic_label_on'));
    $config->set('settings.topic_label_off', $form_state->getValue('topic_label_off'));
    $config->set('settings.comment_subject', $form_state->getValue('comment_subject'));
    $config->set('settings.comment_message', $form_state->getValue('comment_message'));
    $config->set('settings.forum_default_frequency', $form_state->getValue('forum_default_frequency'));
    $config->set('settings.topic_default_frequency', $form_state->getValue('topic_default_frequency'));
    $config->set('settings.cron', $form_state->getValue('cron'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}