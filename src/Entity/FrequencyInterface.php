<?php
/**
 * Created by PhpStorm.
 * User: ddiallo
 * Date: 23/09/2021
 * Time: 15:21
 */

namespace Drupal\forum_notifications_subscription\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Frequency entities.
 *
 * @ingroup forum_notification
 */
interface FrequencyInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Frequency name.
   *
   * @return string
   *   Name of the Frequency.
   */
  public function getName();

  /**
   * Sets the Frequency name.
   *
   * @param string $name
   *   The Frequency name.
   *
   * @return \Drupal\forum_notifications_subscription\Entity\FrequencyInterface
   *   The called Frequency entity.
   */
  public function setName($name);

  /**
   * Gets the Frequency creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Frequency.
   */
  public function getCreatedTime();

  /**
   * Sets the Frequency creation timestamp.
   *
   * @param int $timestamp
   *   The Frequency creation timestamp.
   *
   * @return \Drupal\forum_notifications_subscription\Entity\FrequencyInterface
   *   The called Frequency entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Frequency published status indicator.
   *
   * Unpublished Frequency are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Frequency is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Frequency.
   *
   * @param bool $published
   *   TRUE to set this Frequency to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\forum_notifications_subscription\Entity\FrequencyInterface
   *   The called Frequency entity.
   */
  public function setPublished($published);

}
