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

class campaign_edit_form extends moodleform
{
    protected $isadding;
    protected $title = '';
    protected $description = '';

    public function __construct($actionurl, $isadding)
    {
        $this->isadding = $isadding;
        parent::__construct($actionurl);
    }

    public function definition()
    {
        $mform =& $this->_form;

        // Then show the fields about where this block appears.
        $mform->addElement('header', 'editcampaignheader', get_string('campaign', 'block_campaign_manager'));

        $mform->addElement('text', 'title', get_string('campaignname', 'block_campaign_manager'), array('size' => 120));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');

        $mform->addElement('textarea', 'description', get_string('displaydescriptionlabel', 'block_campaign_manager'), 'wrap="virtual" rows="10" cols="50"');

        $mform->addElement('text', 'url', get_string('campaignurl', 'block_campaign_manager'), array('size' => 60));
        $mform->setType('url', PARAM_URL);

        $mform->addElement('filemanager', 'image', get_string('campaignimage', 'block_campaign_manager'), null,
            array('accepted_types' => array('.jpg', '.png', 'jpeg')));

        $mform->addRule('image', null, 'required');

        $mform->addElement('date_time_selector', 'startdate', get_string('startdate', 'block_campaign_manager'));
        $mform->addHelpButton('startdate', 'startdate');
        $mform->addRule('startdate', null, 'required');
        $mform->setAdvanced('startdate');

        $mform->addElement('date_time_selector', 'enddate', get_string('enddate', 'block_campaign_manager'));
        $mform->addHelpButton('enddate', 'enddate');
        $mform->addRule('enddate', null, 'required');
        $mform->setAdvanced('enddate');

        $submitlabal = null; // Default
        if ($this->isadding) {
            $submitlabal = get_string('addnewcampaign', 'block_campaign_manager');
        }

        $this->add_action_buttons(true, $submitlabal);
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if ($data['enddate'] && $data['startdate'] && $data['startdate'] >= $data['enddate']) {
            $errors['startdate'] = get_string('invalidstartdate', 'block_campaign_manager');
        }

        return $errors;
    }

    public function get_data()
    {
        $data = parent::get_data();
        return $data;
    }
}

$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$campaignid = optional_param('campaignid', 0, PARAM_INT); // 0 mean create new.

$context = context_system::instance();
$PAGE->set_context($context);

$urlparams = array('campaignid' => $campaignid);
$managecampaigns = new moodle_url('/blocks/campaign_manager/managecampaigns.php', $urlparams);

$PAGE->set_url('/blocks/campaign_manager/editcampaign.php', $urlparams);
$PAGE->set_pagelayout('admin');

if ($campaignid) {
    $isadding = false;
    $campaignrecord = $DB->get_record('block_campaign_manager', array('id' => $campaignid), '*', MUST_EXIST);
} else {
    $isadding = true;
    $campaignrecord = new stdClass;
}

$mform = new campaign_edit_form($PAGE->url, $isadding);
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

    $PAGE->navbar->add(get_string('blocks'));
    $PAGE->navbar->add(get_string('pluginname', 'block_campaign_manager'));
    $PAGE->navbar->add(get_string('managecampaigns', 'block_campaign_manager'), $managecampaigns);
    $PAGE->navbar->add($strtitle);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($strtitle, 2);

    $mform->display();

    echo $OUTPUT->footer();
}
