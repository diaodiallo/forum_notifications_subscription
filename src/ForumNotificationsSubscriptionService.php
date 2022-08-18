<?php

namespace Drupal\forum_notifications_subscription;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class ForumNotificationsSubscriptionService implements ForumNotificationsSubscriptionServiceInterface {

  private $currentUser;

  private $entityTypeManager;

  private $settings;

  /**
   * Constructs a new ForumNotificationsSubscriptionService object.
   */
  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->settings = \Drupal::config('forum_notifications_subscription.settings')
      ->get('settings');
  }

  public function checkNotificationFrequencyByEntity($entity) {
    try {
      $frequency = $this->entityTypeManager->getStorage('forum_notification_frequency')
        ->loadByProperties([
          'entity_id' => $entity->id(),
          'user_id' => $this->currentUser->id(),
        ]);
      $frequency = reset($frequency);
    } catch (\Exception $e) {
      \Drupal::logger('forum_notifications_subscription')
        ->alert('Cant get entities with id: ' . $entity->id() . ' it do not exist');
      return NULL;
    }

    return $frequency;
  }

  public function createNotificationFrequencyByEntity($entity_id, $entity_type_id) {
    if ($entity_type_id == 'node') {
      $entity = $this->entityTypeManager->getStorage($entity_type_id)
        ->load($entity_id);
      $type = 'Forum topic';
      $default_frequency = $this->settings['topic_default_frequency'];
    }
    elseif ($entity_type_id == 'taxonomy_term') {
      $entity = $this->entityTypeManager->getStorage($entity_type_id)
        ->load($entity_id);
      $type = 'Forum';
      $default_frequency = $this->settings['forum_default_frequency'];
    }
    $frequency = $this->entityTypeManager->getStorage('forum_notification_frequency')
      ->create([
        'name' => $this->currentUser->getAccountName(),
        'user_id' => $this->currentUser->id(),
        'entity_id' => $entity->id(),
        'entity_name' => $entity->label(),
        'type' => $type,
        'frequency' => $default_frequency,
      ]);

    $frequency->save();
  }

  public function createNotificationFrequencyForUser($entity, $user) {
    if ($entity->getEntityTypeId() == 'node') {
      $type = 'Forum topic';
      $default_frequency = $this->settings['topic_default_frequency'];
    }
    elseif ($entity->getEntityTypeId() == 'taxonomy_term') {
      $type = 'Forum';
      $default_frequency = $this->settings['forum_default_frequency'];
    }
    $frequency = $this->entityTypeManager->getStorage('forum_notification_frequency')
      ->create([
        'name' => $user->getAccountName(),
        'user_id' => $user->id(),
        'entity_id' => $entity->id(),
        'entity_name' => $entity->label(),
        'type' => $type,
        'frequency' => $default_frequency,
      ]);

    $frequency->save();
  }

  public function deleteNotificationFrequencyByEntity($entity_id) {
    try {
      $frequency = $this->entityTypeManager->getStorage('forum_notification_frequency')
        ->loadByProperties([
          'entity_id' => $entity_id,
          'user_id' => $this->currentUser->id(),
        ]);
      $frequency = reset($frequency);

      $frequency->delete();
    } catch (\Exception $e) {
      \Drupal::logger('forum_notifications_subscription')
        ->alert('Cant delete entity with id: ' . $entity_id . ' it do not exist');
    }
  }

  // Get notifications by entity_id
  public function getNotificationFrequencyByEntityAndType($entity_id, $entity_type_id) {
    $type = $entity_type_id == 'node' ? 'Forum topic' : 'Forum';
    try {
      $frequencies = $this->entityTypeManager->getStorage('forum_notification_frequency')
        ->loadByProperties([
          'entity_id' => $entity_id,
          'type' => $type,
        ]);
    } catch (\Exception $e) {
      \Drupal::logger('forum_notifications_subscription')
        ->alert('Cant get entities with id: ' . $entity_id . ' it do not exist');
      return NULL;
    }

    return $frequencies;

  }

  public function getUserNotificationEntities($type) {
    try {
      $frequencies = $this->entityTypeManager->getStorage('forum_notification_frequency')
        ->loadByProperties([
          'user_id' => $this->currentUser->id(),
          'type' => $type,
        ]);
    } catch (\Exception $e) {
      \Drupal::logger('forum_notifications_subscription')
        ->alert('Cant get entities for user with id: ' . $this->currentUser->id());
      return NULL;
    }

    return $frequencies;
  }

  public function getNotificationById($id) {
    try {
      $frequency = $this->entityTypeManager->getStorage('forum_notification_frequency')
        ->loadByProperties([
          'id' => $id,
        ]);
      $frequency = reset($frequency);
    } catch (\Exception $e) {
      \Drupal::logger('forum_notifications_subscription')
        ->alert('Cant get entity with id: ' . $id . ' it do not exist');
      return NULL;
    }

    return $frequency;
  }

}