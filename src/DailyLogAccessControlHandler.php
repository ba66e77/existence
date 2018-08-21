<?php

namespace Drupal\existence;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Daily log entity.
 *
 * @see \Drupal\existence\Entity\DailyLog.
 */
class DailyLogAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\existence\Entity\DailyLogInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished daily log entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published daily log entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit daily log entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete daily log entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add daily log entities');
  }

}
