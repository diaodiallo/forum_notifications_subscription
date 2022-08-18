<?php

namespace Drupal\forum_notifications_subscription\Plugin\QueueWorker;

use Drupal\Core\Mail\MailManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\DelayedRequeueException;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @QueueWorker(
 * id = "daily_digest_email_queue",
 * title = "Forum subscription daily digest email queue processor",
 * cron = {"time" = 100}
 * )
 */
class DailyDigestEmailQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  private $mail;

  public function __construct(MailManager $mail) {
    $this->mail = $mail;
  }

  public function processItem($data) {
    $automated_cron_settings = \Drupal::config('automated_cron.settings');
    $cron_interval = $automated_cron_settings->get('interval') / 3600;
    $from_middle_night = $this->getInterval($data['user_id']);
    if ($from_middle_night <= $cron_interval) {
      $params['to'] = $data['to'];
      $params['title'] = $data['title'];
      $params['message'] = $data['message'];
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $this->mail->mail('forum_notifications_subscription', 'daily_digest_email_queue', $data['to'], $langcode, $params, NULL, TRUE);
    }
    else {
      throw new DelayedRequeueException;
    }
  }

  /**
   * Get the number of hours before middle night of the user time zone.
   * @param $user_id
   *
   * @return string
   * @throws \Exception
   */
  private function getInterval($user_id) {
    $user = User::load($user_id);
    $time_zone = $user->getTimeZone();
    $date = new \DateTime("now", new \DateTimeZone($time_zone));
    $tomorrow_middle_night = new \DateTime('tomorrow midnight', new \DateTimeZone($time_zone));
    $diff = date_diff($date, $tomorrow_middle_night);

    return $diff->format('%h');
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('plugin.manager.mail')
    );
  }
}