<?php

namespace Drupal\forum_notifications_subscription;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Community notifier frequency entities.
 *
 * @ingroup forum_notification
 */
class ForumNotificationFrequencyListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Notification ID');
    $header['name'] = $this->t('Name');
    $header['type'] = $this->t('Type');
    $header['entity name'] = $this->t('entity_name');
    $header['frequency'] = $this->t('frequency');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\forum_notifications_subscription\Entity\Frequency */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.forum_notification_frequency.edit_form',
      ['forum_notification_frequency' => $entity->id()]
    );
    $row['type'] = $entity->getSubscriptionType();
    $row['entity_name'] = $entity->getSubscribedEntityName();
    $row['frequency'] = $entity->getFrequency();
    return $row + parent::buildRow($entity);
  }

}
