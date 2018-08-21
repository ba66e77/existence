<?php

namespace Drupal\existence\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Daily log entities.
 *
 * @ingroup existence
 */
interface DailyLogInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Daily log name.
   *
   * @return string
   *   Name of the Daily log.
   */
  public function getName();

  /**
   * Sets the Daily log name.
   *
   * @param string $name
   *   The Daily log name.
   *
   * @return \Drupal\existence\Entity\DailyLogInterface
   *   The called Daily log entity.
   */
  public function setName($name);

  /**
   * Gets the Daily log creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Daily log.
   */
  public function getCreatedTime();

  /**
   * Sets the Daily log creation timestamp.
   *
   * @param int $timestamp
   *   The Daily log creation timestamp.
   *
   * @return \Drupal\existence\Entity\DailyLogInterface
   *   The called Daily log entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Daily log published status indicator.
   *
   * Unpublished Daily log are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Daily log is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Daily log.
   *
   * @param bool $published
   *   TRUE to set this Daily log to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\existence\Entity\DailyLogInterface
   *   The called Daily log entity.
   */
  public function setPublished($published);

  /**
   * Gets the Daily log revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Daily log revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\existence\Entity\DailyLogInterface
   *   The called Daily log entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Daily log revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Daily log revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\existence\Entity\DailyLogInterface
   *   The called Daily log entity.
   */
  public function setRevisionUserId($uid);

}
