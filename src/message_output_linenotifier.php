<?php
require_once($CFG->dirroot.'/message/output/lib.php');
class message_output_linenotifier extends message_output {
    function send_message($eventdata) {
        global $CFG;
        require_once($CFG->dirroot.'/message/output/linenotifier/lib/LINEAPI.php');
        $LINE = get_user_preferences('LINEID', null, $eventdata->userto->id);
        $body = [
            "to"    => $LINE,
            "messages"  => [
                [
                    "type"      => "flex",
                    "altText"   => "件名: ". $eventdata->subject. "\n".
                                    "\n".
                                    $eventdata->fullmessagehtml,
                    "contents"  => [
                        "type"      => "bubble",
                        "header" => [
                            "type"      => "box",
                            "layout"    => "horizontal",
                            "contents"  =>[
                                [
                                    "type"  => "text",
                                    "wrap"  => true,
                                    "text"  => $eventdata->subject
                                ]
                            ]
                        ],
                        "body" => [
                            "type"      => "box",
                            "layout"    => "horizontal",
                            "contents"  =>[
                                [
                                    "type"  => "text",
                                    "wrap"  => true,
                                    "text"  => $eventdata->fullmessage
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        if(push($body)){
            error_log("LINE Notifier log:");
            error_log("Notification was failed.");
            return false;
        }
        return true;
    }

    function config_form($preferences){ //{moodlehome}/message/notificationpreferences.php
        global $USER, $OUTPUT, $CFG;

        if(get_user_preferences('LINEID', false, $USER->id) === false){
            $string = '<a href="'. $CFG->AccountID.'">'.
                    '<img src="https://scdn.line-apps.com/n/line_add_friends/btn/ja.png" alt="友だち追加" height="36" border="0"><br>'.
                    '<img src="https://qr-official.line.me/sid/M/842wflgy.png">'.
                "</a>";
        }else{
            $string = get_string('userId', 'message_linenotifier'). " :<br>".
            get_user_preferences('LINEID', $USER->id). "<br>".
            html_writer::link($CFG->wwwroot. '/message/output/linenotifier/src/unlink.php', get_string('unlink', 'message_linenotifier'));
        }

        return $string;
    }
    function process_form($form, &$preferences){
        global $CFG;

        if (isset($form->linenotifier)) {
            $preferences['message_processor_linenotifier'] = clean_param($form->linenotifier, PARAM_EMAIL);
        }
        if (isset($form->userid)) {
            require_once($CFG->dirroot.'/user/lib.php');

            $user = core_user::get_user($form->userid, '*', MUST_EXIST);
            user_update_user($user, false, false);
        }
    }
    public function get_default_messaging_settings() {
        return MESSAGE_PERMITTED + MESSAGE_DEFAULT_LOGGEDIN + MESSAGE_DEFAULT_LOGGEDOFF;
    }
    function load_data(&$preferences, $userid){
        $preferences->linenotifier = get_user_preferences( 'message_processor_linenotifier', '', $userid);
    }
    public function can_send_to_any_users() {
        return true;
    }
}
