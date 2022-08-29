<?php

namespace Drupal\forum_notifications_subscription;

use Drupal\Core\Utility\Token;

class ForumNotificationsSubscriptionTokenService {

  private $tokenService;

  /**
   * Constructs a new ForumNotificationsSubscriptionTokenService object.
   */
  public function __construct(Token $tokenService) {
    $this->tokenService = $tokenService;
  }

  public function replacePlain(string $plain, array $data) {

    return $this->tokenService->replacePlain($plain, $data);
  }

}