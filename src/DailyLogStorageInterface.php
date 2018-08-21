<?php

namespace Drupal\existence;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface DailyLogStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Daily log revision IDs for a specific Daily log.
   *
   * @param \Drupal\existence\Entity\DailyLogInterface $entity
   *   The Daily log entity.
   *
   * @return int[]
   *   Daily log revision IDs (in ascending order).
   */
  public function revisionIds(DailyLogInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Daily log author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Daily log revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\existence\Entity\DailyLogInterface $entity
   *   The Daily log entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(DailyLogInterface $entity);

  /**
   * Unsets the language for all Daily log with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
