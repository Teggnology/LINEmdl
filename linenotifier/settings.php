<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // The processor should be enabled by the same enable mobile setting.
    $settings->add(new admin_setting_configtext('AccountID',
                    get_string('AccountID', 'message_linenotifier'),
                    get_string('configAccountID', 'message_linenotifier'), null, PARAM_TEXT));
    $settings->add(new admin_setting_configtext('ChannelID',
                    get_string('ChannelID', 'message_linenotifier'),
                    get_string('configChannelID', 'message_linenotifier'), null, PARAM_INT));
    $settings->add(new admin_setting_configtext('ChannelSecret',
                    get_string('ChannelSecret', 'message_linenotifier'),
                    get_string('configChannelSecret', 'message_linenotifier'), null, PARAM_TEXT));

    $url = new moodle_url('https://manager.line.biz/', array('sesskey' => sesskey()));
    $link = html_writer::link($url, get_string('link', 'message_linenotifier'));
    $settings->add(new admin_setting_heading('requestaccesskey', '', $link));
}
