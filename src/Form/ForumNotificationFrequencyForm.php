<?php

namespace Drupal\forum_notifications_subscription\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Frequency edit forms.
 *
 * @ingroup forum_notification
 */
class ForumNotificationFrequencyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\forum_notifications_subscription\Entity\Frequency */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Created the %label Frequency.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Saved the %label Frequency.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.forum_notification_frequency.canonical', ['forum_notification_frequency' => $entity->id()]);
  }

}
