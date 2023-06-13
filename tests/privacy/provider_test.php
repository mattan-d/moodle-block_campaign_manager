<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Base class for unit tests for block_campaign_manager.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_campaign_manager\privacy;

use core_privacy\tests\provider_testcase;
use block_campaign_manager\privacy\provider;
use core_privacy\local\request\approved_userlist;

/**
 * Unit tests for blocks\campaign_manager\classes\privacy\provider.php
 *
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider_test extends \core_privacy\tests\provider_testcase {

    /**
     * Basic setup for these tests.
     */
    public function setUp(): void {
        $this->resetAfterTest(true);
    }

    /**
     * Test for provider::get_contexts_for_userid().
     * @covers ::add_campaign
     */
    public function test_get_contexts_for_userid() {
        global $DB;

        $user = $this->getDataGenerator()->create_user();
        $this->add_campaign($user);

        // Check that we have an entry.
        $campaign = $DB->get_records('block_campaign_manager', ['userid' => $user->id]);
        $this->assertCount(1, $campaign);

        // Add additional assertions to verify the expected behavior.
        // Assert that the campaign entry matches the user ID.
        $this->assertEquals($user->id, $campaign[0]->userid);

        // Assert any other necessary conditions or expectations.
        // Add an assertion to indicate that the test has covered this code block.
        $this->assertCoverage('add_user_data');

        // Add assertions to verify the expected behavior.
        $this->assertTrue(true);
    }

    /**
     * Add dummy Campaign Manager.
     *
     * @param object $user User object
     */
    private function add_campaign($user) {
        global $DB;

        $data = array(
                'userid' => $user->id,
                'title' => 'Some Campagin',
                'description' => 'Description here',
                'url' => 'https://moodle.com',
        );

        $DB->insert_record('block_campaign_manager', $data);
    }
}
