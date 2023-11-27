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
 * Script to let a user manage their Campaign Manager.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/lib.php');

$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$deletecampaignid = optional_param('deletecampaignid', 0, PARAM_INT);
$statuscampaignid = optional_param('statuscampaignid', 0, PARAM_INT);
$newstatus = optional_param('newstatus', 0, PARAM_INT);

require_login();

$context = context_system::instance();

$PAGE->set_context($context);

$managesharedfeeds = has_capability('block/campaign_manager:manageanycampaigns', $context);
if (!$managesharedfeeds) {
    require_capability('block/campaign_manager:manageanycampaigns', $context);
}

$urlparams = [];
$extraparams = '';

if ($returnurl) {
    $urlparams['returnurl'] = $returnurl;
    $extraparams = '&returnurl=' . $returnurl;
}
$baseurl = new moodle_url('/blocks/campaign_manager/managecampaigns.php', $urlparams);
$PAGE->set_url($baseurl);

// Process any actions.
if ($deletecampaignid && confirm_sesskey()) {
    $DB->delete_records('block_campaign_manager', array('id' => $deletecampaignid));

    redirect($PAGE->url, get_string('campaigndeleted', 'block_campaign_manager'));
}

// Process any actions.
if ($statuscampaignid && confirm_sesskey()) {
    $DB->set_field('block_campaign_manager', 'visible', $newstatus, ['id' => $statuscampaignid]);

    redirect($PAGE->url, get_string('campaignstatus', 'block_campaign_manager'));
}

// Display the list of campaigns.
$campaigns = $DB->get_records('block_campaign_manager', [], 'startdate');

$strmanage = get_string('managecampaigns', 'block_campaign_manager');

$PAGE->set_pagelayout('admin');
$PAGE->set_title($strmanage);
$PAGE->set_heading($strmanage);

$managecampaigns = new moodle_url('/blocks/campaign_manager/managecampaigns.php', $urlparams);
echo $OUTPUT->header();

$table = new flexible_table('display-campaigns');

$table->define_columns(array('campaign', 'startdate', 'actions'));
$table->define_headers(array(get_string('campaign', 'block_campaign_manager'), get_string('startdate', 'block_campaign_manager'),
        get_string('enddate', 'block_campaign_manager'), get_string('actions', 'moodle')));
$table->define_baseurl($baseurl);

$table->set_attribute('cellspacing', '0');
$table->set_attribute('id', 'campaigns');
$table->set_attribute('class', 'generaltable generalbox');
$table->column_class('campaign', 'campaign');
$table->column_class('startdate', 'startdate');
$table->column_class('actions', 'actions');

$table->setup();

foreach ($campaigns as $campaign) {
    $viewlink = html_writer::link($CFG->wwwroot . '/blocks/campaign_manager/editcampaign.php?campaignid=' . $campaign->id .
            $extraparams, $campaign->title);

    $campaigninfo = '<div class="title">' . $viewlink . '</div>' .
            '<div class="description">' . $campaign->description . '</div>';

    $editurl = new moodle_url('/blocks/campaign_manager/editcampaign.php?campaignid=' . $campaign->id . $extraparams);
    $editaction = $OUTPUT->action_icon($editurl, new pix_icon('t/edit', get_string('edit')));

    $deleteurl = new moodle_url('/blocks/campaign_manager/managecampaigns.php?deletecampaignid=' . $campaign->id . '&sesskey=' .
            sesskey() . $extraparams);
    $deleteicon = new pix_icon('t/delete', get_string('delete'));
    $deleteaction = $OUTPUT->action_icon($deleteurl, $deleteicon,
            new confirm_action(get_string('deletecampaignconfirm', 'block_campaign_manager')));

    if ($campaign->visible) {
        $statusurl = new moodle_url('/blocks/campaign_manager/managecampaigns.php?newstatus=0&statuscampaignid=' . $campaign->id .
                '&sesskey=' . sesskey() . $extraparams);
        $statusicon = new pix_icon('t/hide', get_string('hide'));
        $statusaction = $OUTPUT->action_icon($statusurl, $statusicon);
    } else {
        $statusurl = new moodle_url('/blocks/campaign_manager/managecampaigns.php?newstatus=1&statuscampaignid=' . $campaign->id .
                '&sesskey=' . sesskey() . $extraparams);
        $statusicon = new pix_icon('t/show', get_string('show'));
        $statusaction = $OUTPUT->action_icon($statusurl, $statusicon);
    }

    $campaignicons = $editaction . ' ' . $deleteaction . ' ' . $statusaction;
    $campaign->startdate = date('d/m/Y H:i:s', $campaign->startdate);
    $campaign->enddate = date('d/m/Y H:i:s', $campaign->enddate);

    $table->add_data(array($campaigninfo, $campaign->startdate, $campaign->enddate, $campaignicons));
}

$table->print_html();

$url = $CFG->wwwroot . '/blocks/campaign_manager/editcampaign.php?' . substr($extraparams, 1);
echo '<div class="actionbuttons mt-3">' .
        $OUTPUT->single_button($url, get_string('addnewcampaign', 'block_campaign_manager'), 'get') . '</div>';

if ($returnurl) {
    echo '<div class="backlink">' . html_writer::link($returnurl, get_string('back')) . '</div>';
}

echo $OUTPUT->footer();
