<?php

namespace Drupal\forum_notifications_subscription\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the subscription frequency entity.
 *
 * @ingroup forum_notification
 *
 * @ContentEntityType(
 *   id = "forum_notification_frequency",
 *   label = @Translation("Forum notification frequency"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" =
 *   "Drupal\forum_notifications_subscription\ForumNotificationFrequencyListBuilder",
 *     "views_data" =
 *   "Drupal\forum_notifications_subscription\Entity\ForumNotificationFrequencyViewsData",
 *
 *     "form" = {
 *       "default" =
 *   "Drupal\forum_notifications_subscription\Form\ForumNotificationFrequencyForm",
 *       "add" =
 *   "Drupal\forum_notifications_subscription\Form\ForumNotificationFrequencyForm",
 *       "edit" =
 *   "Drupal\forum_notifications_subscription\Form\ForumNotificationFrequencyForm",
 *       "delete" =
 *   "Drupal\forum_notifications_subscription\Form\ForumNotificationFrequencyDeleteForm",
 *     },
 *     "access" =
 *   "Drupal\forum_notifications_subscription\ForumNotificationFrequencyAccessControlHandler",
 *     "route_provider" = {
 *       "html" =
 *   "Drupal\forum_notifications_subscription\ForumNotificationFrequencyHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "forum_notification_frequency",
 *   admin_permission = "administer forum notification frequency entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" =
 *   "/admin/structure/forum_notification_frequency/{forum_notification_frequency}",
 *     "add-form" = "/admin/structure/forum_notification_frequency/add",
 *     "edit-form" =
 *   "/admin/structure/forum_notification_frequency/{forum_notification_frequency}/edit",
 *     "delete-form" =
 *   "/admin/structure/forum_notification_frequency/{forum_notification_frequency}/delete",
 *     "collection" = "/admin/structure/forum_notification_frequency",
 *   },
 *   field_ui_base_route = "forum_notification_frequency.settings"
 * )
 */
class Frequency extends ContentEntityBase implements FrequencyInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Frequency entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Notifier entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type of the subscription (Forum or Forum topic).'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['entity_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Subscribed Entity Id'))
      ->setDescription(t('The subscribed entity id.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'integer',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setReadOnly(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['entity_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity Name'))
      ->setDescription(t('The subscribed entity name.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setReadOnly(TRUE)
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    $fields['frequency'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Frequency'))
      ->setDescription(t('The frequency of the notification (Daily Digest Emails or Single Emails).'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('Daily Digest Emails')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Frequency is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  public function setFrequency($frequency) {
    $this->set('frequency', $frequency);
    return $this;
  }

  public function getFrequency() {
    return $this->get('frequency')->value;
  }

  public function setSubscriptionType($frequency) {
    $this->set('type', $frequency);
    return $this;
  }

  public function getSubscriptionType() {
    return $this->get('type')->value;
  }

  public function setSubscribedEntityId($entity_id) {
    $this->set('entity_id', $entity_id);
    return $this;
  }

  public function getSubscribedEntityId() {
    return $this->get('entity_id')->value;
  }

  public function setSubscribedEntityName($entity_name) {
    $this->set('entity_name', $entity_name);
    return $this;
  }

  public function getSubscribedEntityName() {
    return $this->get('entity_name')->value;
  }
}
