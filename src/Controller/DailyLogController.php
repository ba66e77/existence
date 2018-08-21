<?php

namespace Drupal\existence\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\existence\Entity\DailyLogInterface;

/**
 * Class DailyLogController.
 *
 *  Returns responses for Daily log routes.
 */
class DailyLogController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Daily log  revision.
   *
   * @param int $daily_log_revision
   *   The Daily log  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($daily_log_revision) {
    $daily_log = $this->entityManager()->getStorage('daily_log')->loadRevision($daily_log_revision);
    $view_builder = $this->entityManager()->getViewBuilder('daily_log');

    return $view_builder->view($daily_log);
  }

  /**
   * Page title callback for a Daily log  revision.
   *
   * @param int $daily_log_revision
   *   The Daily log  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($daily_log_revision) {
    $daily_log = $this->entityManager()->getStorage('daily_log')->loadRevision($daily_log_revision);
    return $this->t('Revision of %title from %date', ['%title' => $daily_log->label(), '%date' => format_date($daily_log->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Daily log .
   *
   * @param \Drupal\existence\Entity\DailyLogInterface $daily_log
   *   A Daily log  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(DailyLogInterface $daily_log) {
    $account = $this->currentUser();
    $langcode = $daily_log->language()->getId();
    $langname = $daily_log->language()->getName();
    $languages = $daily_log->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $daily_log_storage = $this->entityManager()->getStorage('daily_log');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $daily_log->label()]) : $this->t('Revisions for %title', ['%title' => $daily_log->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all daily log revisions") || $account->hasPermission('administer daily log entities')));
    $delete_permission = (($account->hasPermission("delete all daily log revisions") || $account->hasPermission('administer daily log entities')));

    $rows = [];

    $vids = $daily_log_storage->revisionIds($daily_log);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\existence\DailyLogInterface $revision */
      $revision = $daily_log_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $daily_log->getRevisionId()) {
          $link = $this->l($date, new Url('entity.daily_log.revision', ['daily_log' => $daily_log->id(), 'daily_log_revision' => $vid]));
        }
        else {
          $link = $daily_log->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.daily_log.translation_revert', ['daily_log' => $daily_log->id(), 'daily_log_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.daily_log.revision_revert', ['daily_log' => $daily_log->id(), 'daily_log_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.daily_log.revision_delete', ['daily_log' => $daily_log->id(), 'daily_log_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['daily_log_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
