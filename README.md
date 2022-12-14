INTRODUCTION
------------

The Forum Notifications Subscription module helps site administrators to set up a notification process based on
the forum module. It gives then site users the possibility to subscribe/unsubscribe to forum posts and comments.

The forum module uses taxonomy for containers and forums, node for forum topics and comments for replying to forum
topics.

FEATURES
--------

* Subscription/unsubscription to forums.
* Subscription/unsubscription to forum topics.
* Email subject and body customization (with token replacement).
* Queues emails (for large notification lists)
* User notification frequency update (each user can update the notification frequencies of his subscriptions at the
  profile edit form).
* Notification frequency view (the module make available a view for the user to see his notification frequency:
  Frequency settings view; you can add it at the user profile display).

Module project page:
http://drupal.org/project/forum_notifications_subscription

* To submit bug reports and feature suggestions, or track changes:
  https://www.drupal.org/project/issues/forum_notifications_subscription

REQUIREMENTS
------------

This module requires the following modules:

* PHP 8
* Drupal 9.4.x and above
* Forum (https://www.drupal.org/project/forum)

INSTALLATION
------------

* Install as you would normally install a contributed Drupal module.

CONFIGURATION
-------------

* The module has a configuration form where we customize subscription buttons and email messages.
  You can find it at /admin/config/forum_notifications_subscription.
* Enable the "Subscription link" field (forum_notifications_subscription) to the view mode you are using to display
  forums and forum topics.
  You can also use this field in twig templates like {{ content.forum_notifications_subscription }}.

MAINTAINERS
-----------

Current maintainers:

* Mamadou Diao Diallo (diaodiallo) - https://www.drupal.org/u/diaodiallo
* Michael Mwebaze (mwebaze) - https://www.drupal.org/u/mwebaze
* Daniel Cothran (andileco) - https://www.drupal.org/u/andileco

Supporting organization:

* John Snow, Inc. (JSI) - https://www.drupal.org/john-snow-inc-jsi
