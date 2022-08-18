<?php

namespace Drupal\forum_notifications_subscription;

Interface ForumNotificationsSubscriptionServiceInterface {

  public function checkNotificationFrequencyByEntity($entity_id);

  public function createNotificationFrequencyByEntity($entity_id, $entity_type_id);

  public function deleteNotificationFrequencyByEntity($entity_id);

}

