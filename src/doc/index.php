<?php
require_once('../../../../config.php');
require_once($CFG->dirroot.'/lib/moodlelib.php');
require_once($CFG->dirroot.'/message/output/linenotifier/lib/LINEAPI.php');
require_login();
$PAGE->set_url($CFG->wwwroot."/message/output/linenotifier/src/unlink.php");
$PAGE->set_context(null);
echo $OUTPUT->header();
echo "<h1>". get_string('pluginname', 'message_linenotifier'). "</h1>". 
    "<h2>". "Waht's this?". "</h2>". 
        "This moodle plugin makes moodle can send notifications to user by LINE.<br>". 
    "<h2>". "How to use". "</h2>". 
        "You should access to user preference page, and you will find \"LINE notifier⚙\".<br>".
        "When you click it, you can see QR code and green button.<br>". 
        "Please add the account by these.<br>".
        "After adding, please follow the accounts instructions.<br>";
echo $OUTPUT->footer();