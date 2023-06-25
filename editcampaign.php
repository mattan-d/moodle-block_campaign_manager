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

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/formslib.php');

$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$campaignid = optional_param('campaignid', 0, PARAM_INT); // 0 mean create new.

require_login();

$context = context_system::instance();
$PAGE->set_context($context);

$managesharedfeeds = has_capability('block/campaign_manager:manageanycampaigns', $context);
if (!$managesharedfeeds) {
    require_capability('block/campaign_manager:manageanycampaigns', $context);
}

$urlparams = array('campaignid' => $campaignid);
$managecampaigns = new moodle_url('/blocks/campaign_manager/managecampaigns.php', $urlparams);

$PAGE->set_url('/blocks/campaign_manager/editcampaign.php');
$PAGE->set_pagelayout('admin');

if ($campaignid) {
    $isadding = false;
    $campaignrecord = $DB->get_record('block_campaign_manager', array('id' => $campaignid), '*', MUST_EXIST);
} else {
    $isadding = true;
    $campaignrecord = new stdClass;
}

$mform = new block_campaign_manager_campaign_form($PAGE->url, $isadding);
$mform->set_data($campaignrecord);

if ($mform->is_cancelled()) {
    redirect($managecampaigns);

} else if ($data = $mform->get_data()) {
    $data->userid = $USER->id;

    if ($isadding) {
        $data->id = $DB->insert_record('block_campaign_manager', $data);
    } else {
        $data->id = $campaignid;
    }

    // Initialise file picker for image.
    $draftitemid = file_get_submitted_draft_itemid('image');
    file_save_draft_area_files($draftitemid, $context->id, 'block_campaign_manager', 'content', $data->id,
            array('subdirs' => true));
    $data->image = $draftitemid;

    $DB->update_record('block_campaign_manager', $data);

    redirect($managecampaigns);

} else {
    if ($isadding) {
        $strtitle = get_string('addnewcampaign', 'block_campaign_manager');
    } else {
        $strtitle = get_string('editacampaign', 'block_campaign_manager');
    }

    $PAGE->set_title($strtitle);
    $PAGE->set_heading($strtitle);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($strtitle, 2);

    $mform->display();

    echo $OUTPUT->footer();
}
