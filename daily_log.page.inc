<?php

/**
 * @file
 * Contains daily_log.page.inc.
 *
 * Page callback for Daily log entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Daily log templates.
 *
 * Default template: daily_log.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_daily_log(array &$variables) {
  // Fetch DailyLog Entity Object.
  $daily_log = $variables['elements']['#daily_log'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
