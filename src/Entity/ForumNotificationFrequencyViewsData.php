<?php

namespace Drupal\forum_notifications_subscription\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Frequency entities.
 */
class ForumNotificationFrequencyViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}