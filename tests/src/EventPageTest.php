<?php

namespace Drupal\Tests\localgov_events\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests LocalGov Event page.
 *
 * @group localgov_campaigns
 */
class EventPageTest extends BrowserTestBase {

  /**
   * Test breadcrumbs in the Standard profile.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * A user with permission to bypass content access checks.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field_ui',
    'localgov_events',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser([
      'bypass node access',
      'administer nodes',
      'administer node fields',
    ]);
  }

  /**
   * Verifies basic functionality with all modules.
   */
  public function testEventFields() {
    $this->drupalLogin($this->adminUser);

    // Check standard fields.
    $this->drupalGet('/admin/structure/types/manage/localgov_event/fields');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('body');
    $this->assertSession()->pageTextContains('localgov_event_call_to_action');
    $this->assertSession()->pageTextContains('localgov_event_categories');
    $this->assertSession()->pageTextContains('localgov_event_date');
    $this->assertSession()->pageTextContains('localgov_event_image');
    $this->assertSession()->pageTextContains('localgov_event_locality');
    $this->assertSession()->pageTextContains('localgov_event_price');
    $this->assertSession()->pageTextNotContains('localgov_event_provider');
    $this->assertSession()->pageTextNotContains('localgov_event_venue');

    // Check optional fields.
    \Drupal::service('module_installer')->install(['localgov_directories_page', 'localgov_directories_venue']);
    $this->drupalGet('/admin/structure/types/manage/localgov_event/fields');
    $this->assertSession()->pageTextContains('localgov_event_provider');
    $this->assertSession()->pageTextContains('localgov_event_venue');
  }

}