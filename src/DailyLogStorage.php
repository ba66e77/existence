<?php

namespace Drupal\existence;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\existence\Entity\DailyLogInterface;

/**
 * Defines the storage handler class for Daily log entities.
 *
 * This extends the base storage class, adding required special handling for
 * Daily log entities.
 *
 * @ingroup existence
 */
class DailyLogStorage extends SqlContentEntityStorage implements DailyLogStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(DailyLogInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {daily_log_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {daily_log_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(DailyLogInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {daily_log_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('daily_log_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
