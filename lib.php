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
 * Script to let a user edit the properties of a particular Campaign Manager.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing HTML block instances.
 *
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_campaign_manager
 * @category  files
 * @param stdClass $course course object
 * @param stdClass $birecord_or_cm block instance record
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function block_campaign_manager_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $DB, $CFG, $USER;

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }

    require_login();

    $itemid = clean_param(array_shift($args), PARAM_INT);
    if ($filearea !== 'content') {
        send_file_not_found();
    }

    $fs = get_file_storage();
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';
    $file = $fs->get_file($context->id, 'block_campaign_manager', 'content', $itemid, '/', $filename);

    if (!$file || $file->is_directory()) {
        send_file_not_found();
    }

    send_stored_file($file, null, 0, $forcedownload, $options);
}
