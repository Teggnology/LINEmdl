<?php
require_once('../../../../config.php');
require_once($CFG->dirroot.'/message/output/linenotifier/lib/LINEAPI.php');
$req = file_get_contents('php://input');
if($req == ""){
    exit("Bad Request");
}
$json = json_decode($req);

//validation of Webhook Packet.
$hash = hash_hmac('sha256', $req, $CFG->ChannelSecret, true);
$signature = base64_encode($hash);
if(!isset(getallheaders()["X-Line-Signature"]) || $signature !== getallheaders()["X-Line-Signature"]){
    exit("Bad Request");
}


switch($json->events[0]->type){
    case "follow":
        $body["to"] = $json->events[0]->source->userId;
        if($body["to"] != null){
            $linkToken = welcome($body["to"]);
            if(!$linkToken){
                error_log("Getting linkToken was failed.");
                exit;
            }
            $body["messages"][0] = [
                "type"      => "text",
                "text"      => get_string('welcomeMessage', 'message_linenotifier').
                                "$CFG->wwwroot/message/output/linenotifier/src/accountLink.php?token=$linkToken". "\n\n".
                                get_string('linkingMessage', 'message_linenotifier')
            ];
            if(!push($body)){
                error_log("Sending welcome message was failed.");
            }
        }
        break;
    case "message":
        $body["to"] = $json->events[0]->source->userId;
        if($json->events[0]->message->text == "Regen the URL" || $json->events[0]->message->text == "連携用URL再発行"){
            if($body["to"] != null){
                $linkToken = welcome($body["to"]);
                if(!$linkToken){
                    error_log("Getting linkToken was failed.");
                    exit;
                }
                $body["messages"][0] = [
                    "type"      => "text",
                    "text"      => get_string('welcomeMessage', 'message_linenotifier').
                                    "$CFG->wwwroot/message/output/linenotifier/src/accountLink.php?token=$linkToken". "\n\n".
                                    get_string('linkingMessage', 'message_linenotifier')
                ];
                if(!push($body)){
                    error_log("Sending welcome message was failed.");
                }
            }
        }
        break;
    case "accountLink":
        $key = urlencode($json->events[0]->link->nonce);
        $nonces = json_decode($CFG->LINEnonces);
        if($json->events[0]->link->result == "ok" && isset($nonces)){
            set_user_preference("LINEID", $json->events[0]->source->userId, $nonces->$key);
            $body["to"] = $json->events[0]->source->userId;
            if($body["to"] != null){
                $body["messages"][0] = [
                    "type"      => "text",
                    "text"      => get_string('linkSuccess', 'message_linenotifier')
                ];
                if($body["to"] != null){
                    if(!push($body)){
                        error_log("Sending linked message was failed.");
                    }
                }
            }
        }
        break;
    default:
        $body = [
            "to"    => "",
            "messages"  => []
        ];
        $body["to"] = "U2333f4b460b94d5b03ab32e07d1060e1";
        $body["messages"][0] = [
            "type"      => "text",
            "text"      => $json->events[0]->type
        ];
        if($body["to"] != null){
            push($body);
        }
        break;
}
