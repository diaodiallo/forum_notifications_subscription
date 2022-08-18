<?php

namespace Drupal\forum_notifications_subscription\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\ckeditor_mentions\Events\CKEditorEvents;
use Drupal\ckeditor_mentions\Events\CKEditorMentionsEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\custom_events\EventSubscriber
 */
class MentionEventsSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    $settings = \Drupal::config('forum_notifications_subscription.settings')
      ->get('settings');
    if (\Drupal::moduleHandler()
        ->moduleExists('ckeditor_mentions') && $settings['mentions']) {
      return [
        CKEditorEvents::MENTION_FIRST => 'ckeditor_mentionsMention',
      ];
    }

    return [];
  }

  public function ckeditor_mentionsMention(CKEditorMentionsEvent $event) {
    $settings = \Drupal::config('forum_notifications_subscription.settings')
      ->get('settings');
    $mention_entity_type_id = $event->getEntity()->getEntityTypeId();
    $base_url = Request::createFromGlobals()
      ->getSchemeAndHttpHost();
    if ($mention_entity_type_id === 'comment') {
      $nid = $event->getEntity()->get('entity_id')->getValue()[0]['target_id'];
      $link = $base_url . '/node/' . $nid . '#comment-' . $event->getEntity()
          ->id();
      $subject = $settings['comment_tag_subject'];
      $message = $settings['comment_tag_message'];
    }
    elseif ($mention_entity_type_id === 'node') {
      $link = $base_url . '/node/' . $event->getEntity()->id();
      $subject = $settings['post_tag_subject'];
      $message = $settings['post_tag_message'];
    }
    $email = $event->getMentionedEntity()->getEmail();
    $name = $event->getMentionedEntity()->label();
    $systemEmail = \Drupal::config('system.site')->get('mail');
    $find = [
      "[name]",
      "[link]",
      "[system_email]",
    ];
    $replace = [
      $name,
      $link,
      $systemEmail,
    ];
    $params['title'] = str_replace($find, $replace, $subject);
    $params['message'] = str_replace($find, $replace, $message);
    $mailManager = \Drupal::service('plugin.manager.mail');
    $langcode = \Drupal::currentUser()->getPreferredLangcode();

    $result = $mailManager->mail('forum_notifications_subscription', 'single_email_queue', $email, $langcode, $params, NULL, TRUE);
  }

}