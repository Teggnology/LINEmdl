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
        "<img src='./media/LINEnotifier.png' style='width:75%'>".
    "<h2>". "How to use". "</h2>".
        "①You should access to notification preference page, and you will find \"LINE notifier⚙\".<br>".
        "<img src='./media/htu/1_1.png' style='border:solid 2px #cccccc;margin:10px;width:75%'><br>".
        "②When you click it, you can see QR code and green button.<br>".
        "<img src='./media/htu/2_1.png' style='border:solid 2px #cccccc;margin:10px;width:75%'><br>".
        "③Please add the account by these.<br>".
        "<img src='./media/htu/3_1.png' style='border:solid 2px #cccccc;margin:10px;width:75%'><br>".
        "④After adding, please follow the accounts instructions.<br>".
        "<img src='./media/htu/4_1.png' style='border:solid 2px #cccccc;margin:10px;width:75%'><br>".
    "<h2>". "About this application". "</h2>".
    "Author: たまご(twitter: @JJ1VNX)<br>".
    "Github: <a href='https://github.com/Teggnology/LINEmdl' target='_blank'>https://github.com/Teggnology/LINEmdl</a>";
echo $OUTPUT->footer();