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
 * Campaign Form Class is defined here.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Form to get the campaign from the user.
 *
 * @package     block_campaign_manager
 * @category    admin
 */
class block_campaign_manager_campaign_form extends moodleform {

    /**
     *
     * Flag indicating if the action is an adding operation.
     *
     * @var bool
     */
    protected $isadding;
    /**
     *
     * The title of the item.
     *
     * @var string
     */
    protected $title = '';
    /**
     *
     * The description of the item.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Constructor for the class.
     *
     * @param string $actionurl The URL where the action will be performed.
     * @param bool $campaignid Indicates if the action is an adding operation.
     * @return void
     */
    public function __construct($actionurl, $campaignid) {
        $this->campaignid = $campaignid;
        parent::__construct($actionurl);
    }

    /**
     *
     * Defines the form elements for creating/editing a campaign.
     *
     * @return void
     */
    public function definition() {
        $mform =& $this->_form;

        // Then show the fields about where this block appears.
        $mform->addElement('header', 'editcampaignheader', get_string('campaign', 'block_campaign_manager'));

        $mform->addElement('hidden', 'campaignid');
        $mform->setDefault('campaignid', $this->campaignid);

        $mform->addElement('text', 'title', get_string('campaignname', 'block_campaign_manager'), array('size' => 120));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');

        $mform->addElement('textarea', 'description', get_string('displaydescriptionlabel', 'block_campaign_manager'),
                'wrap="virtual" rows="10" cols="50"');

        $mform->addElement('text', 'url', get_string('campaignurl', 'block_campaign_manager'), array('size' => 60));
        $mform->setType('url', PARAM_URL);

        $mform->addElement('filemanager', 'image', get_string('campaignimage', 'block_campaign_manager'), null,
                array('accepted_types' => array('.jpg', '.png', 'jpeg')));

        $mform->addRule('image', null, 'required');

        $mform->addElement('date_time_selector', 'startdate', get_string('startdate', 'block_campaign_manager'),
                array('optional' => true));
        $date = (new \DateTime())->setTimestamp(time());
        $mform->setDefault('startdate', $date->getTimestamp());
        $mform->addHelpButton('startdate', 'startdate');
        $mform->setAdvanced('startdate');

        $mform->addElement('date_time_selector', 'enddate', get_string('enddate', 'block_campaign_manager'),
                array('optional' => true));
        $date = (new \DateTime())->setTimestamp(usergetmidnight(time()));
        $date->modify('+30 day');
        $mform->setDefault('enddate', $date->getTimestamp());
        $mform->addHelpButton('enddate', 'enddate');
        $mform->setAdvanced('enddate');

        $this->add_action_buttons(true, get_string('savechanges'));
    }

    /**
     *
     * Validates the submitted form data and files for creating/editing a campaign.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @return array An array containing any validation errors encountered.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if ($data['enddate'] && $data['startdate'] && $data['startdate'] >= $data['enddate']) {
            $errors['startdate'] = get_string('invalidstartdate', 'block_campaign_manager');
        }

        return $errors;
    }

    /**
     *
     * Retrieves the submitted form data.
     *
     * @return array|null The submitted form data, or null if no data was submitted.
     */
    public function get_data() {
        $data = parent::get_data();
        return $data;
    }
}
