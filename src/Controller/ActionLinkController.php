<?php

namespace Drupal\forum_notifications_subscription\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Controller responses subscribe and unsubsribe action links.
 *
 * The response is a set of AJAX commands to update the
 * link in the page.
 */
class ActionLinkController extends ControllerBase implements ContainerInjectionInterface {

  public function subscribe(String $entity_type_id = NULL, String $entity_id = NULL) {
    \Drupal::service('forum_notifications_subscription.frequency')
      ->createNotificationFrequencyByEntity($entity_id, $entity_type_id);
    $config = $this->config('forum_notifications_subscription.settings');
    $settings = $config->get('settings');
    $selector = "#forum-subscription";
    if ($entity_type_id == 'node') {
      $label = $settings['topic_label_off'];
    }
    elseif ($entity_type_id == 'taxonomy_term') {
      $label = $settings['forum_label_off'];
    }
    $content = "<p><a href=\"/forum/unsubscription/" . $entity_type_id . "/" . $entity_id . "\" id=\"forum-subscription\" class=\"use-ajax btn btn-primary\">" . $label . "</a></p>";
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand($selector, $content));
    return $response;
  }

  public function unsubscribe(String $entity_type_id = NULL, String $entity_id = NULL) {
    \Drupal::service('forum_notifications_subscription.frequency')
      ->deleteNotificationFrequencyByEntity($entity_id);
    $config = $this->config('forum_notifications_subscription.settings');
    $settings = $config->get('settings');
    $selector = "#forum-subscription";
    if ($entity_type_id == 'node') {
      $label = $settings['topic_label_on'];
    }
    elseif ($entity_type_id == 'taxonomy_term') {
      $label = $settings['forum_label_on'];
    }
    $content = "<p><a href=\"/forum/subscription/" . $entity_type_id . "/" . $entity_id . "\" id=\"forum-subscription\" class=\"use-ajax btn btn-primary\">" . $label . "</a></p>";
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand($selector, $content));
    return $response;
  }
}
