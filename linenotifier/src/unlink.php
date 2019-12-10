<?php
require_once('../../../../config.php');
require_once($CFG->dirroot.'/lib/moodlelib.php');
require_once($CFG->dirroot.'/message/output/linenotifier/lib/LINEAPI.php');
require_login();
$PAGE->set_url($CFG->wwwroot."/message/output/linenotifier/src/unlink.php");
$PAGE->set_context(null);
echo $OUTPUT->header();
$body = [
    "to"    => "",
    "messages"  => []
];
$body["to"] = get_user_preferences('LINEID', $USER->id);
$body["messages"][0] = [
    "type"      => "text",
    "text"      => "連携解除は完了しましたが,LINEアプリからの当アカウントの削除が必要な場合は,このアカウントをブロックしたあとに\"設定→友だち→ブロックリスト\"より本アカウントを\"編集→削除\"してください。"
];
if($body["to"] != null){
    push($body);
}
unset_user_preference('LINEID', $USER->id);
echo "Moodle側での連携を解除しました</br>LINEアプリ側でのアカウントの削除は手動でお願いします";
echo $OUTPUT->footer();