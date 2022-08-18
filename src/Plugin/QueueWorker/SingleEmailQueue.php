<?php

namespace Drupal\forum_notifications_subscription\Plugin\QueueWorker;

use Drupal\Core\Mail\MailManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @QueueWorker(
 * id = "single_email_queue",
 * title = "Forum subscription single email queue processor",
 * cron = {"time" = 90}
 * )
 */
class SingleEmailQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  private $mail;

  public function __construct(MailManager $mail) {
    $this->mail = $mail;
  }

  public function processItem($data) {
    $params = $data;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $this->mail->mail('forum_notifications_subscription', 'single_email_queue', $data['to'], $langcode, $params, NULL, TRUE);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('plugin.manager.mail')
    );
  }
}