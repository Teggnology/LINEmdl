<?php
require_once('../../../../config.php');
require_once($CFG->dirroot.'/lib/moodlelib.php');
require_login();
$PAGE->set_url($CFG->wwwroot."/message/output/linenotifier/src/accountLink.php");
$PAGE->set_context(null);

$nonce = base64_encode(openssl_random_pseudo_bytes(128));
$keynonce = urlencode($nonce);

echo $OUTPUT->header();
echo "リダイレクトします…";
if(isset($CFG->LINEnonces)){
    $nonces = json_decode($CFG->nonces);
}else{
    $nonces = new stdClass();
}
$nonces->$keynonce = $USER->id;
set_config("LINEnonces", json_encode($nonces));
echo $OUTPUT->footer();
echo "<script type=\"application/javascript\">location.href=\"https://access.line.me/dialog/bot/accountLink?linkToken=". $_GET["token"]. "&nonce=$keynonce\";</script>";