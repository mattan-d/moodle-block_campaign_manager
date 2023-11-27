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
     * Add dummy Campaign Manager.
     */
    private function add_campaign() {
        global $DB;

        $data = array(
                'title' => 'Some Campagin',
                'description' => 'Description here',
                'image' => 1,
                'startdate' => time(),
                'enddate' => time(),
                'visible' => 1,
                'url' => 'https://centricapp.co.il',
        );

        $DB->insert_record('block_campaign_manager', $data);
    }
}
