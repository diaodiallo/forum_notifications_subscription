<?php

namespace Drupal\forum_notifications_subscription;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the notifier frequency entity.
 *
 * @see \Drupal\forum_notifications_subscription\Entity\Frequency.
 */
class ForumNotificationFrequencyAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\forum_notifications_subscription\Entity\FrequencyInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished frequency entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published frequency entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit frequency entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete frequency entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add frequency entities');
  }

}
