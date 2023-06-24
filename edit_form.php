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
 * Form for editing Campaign Manager block instances.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing Campaign Manager block instances.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_campaign_manager_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG, $DB, $USER;

        // Fields for editing block contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_shownumentries', get_string('shownumentrieslabel', 'block_campaign_manager'),
                array('size' => 5));
        $mform->setType('config_shownumentries', PARAM_INT);
        $mform->addRule('config_shownumentries', null, 'numeric', null, 'client');

        if (!empty($CFG->block_campaign_manager_num_entries)) {
            $mform->setDefault('config_shownumentries', $CFG->block_campaign_manager_num_entries);
        } else {
            $mform->setDefault('config_shownumentries', 5);
        }
    }

    public function set_data($defaults) {
        $defaults->config_shownumentries = $this->block->config->config_shownumentries;
        parent::set_data($defaults);
    }
}
