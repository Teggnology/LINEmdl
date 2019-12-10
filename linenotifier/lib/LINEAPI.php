<?php
function getoken($ID = null, $Secret = null){ //getting access token
    global $CFG;
    if(isset($CFG->LINEAccessToken) && $CFG->LINEAccessToken != null && json_decode($CFG->LINEAccessToken)->expires >= time()){
        return json_decode($CFG->LINEAccessToken);
    }
    if($ID != null){
        $id = $ID;
    }else{
        $id = $CFG->ChannelID;
    }
    if($Secret != null){
        $secret = $Secret;
    }else{
        $secret = $CFG->ChannelSecret;
    }
    $curl = curl_init("https://api.line.me/v2/oauth/accessToken");
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST   => "POST",
        CURLOPT_HTTPHEADER      =>  [
            "Content-type: application/x-www-form-urlencoded"
        ],
        CURLOPT_POSTFIELDS      =>  'grant_type='.urlencode('client_credentials').
                                    '&'.
                                    'client_id='.urlencode($id).
                                    '&'.
                                    'client_secret='.urlencode($secret),
        CURLOPT_RETURNTRANSFER  =>  true
    ]);
    $res = json_decode(curl_exec($curl));
    curl_close($curl);
    if(isset($res->access_token)){
        $res->expires = time() + $res->expires_in;
        set_config("LINEAccessToken", json_encode($res));
        return $res;
    }else{
        error_log("Getting Access Token was failed.");
        return false;
    }
}
function push($body){
    global $CFG;

    $token = getoken();
    if(!$token){
        return false;
    }

    $curl = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST   => "POST",
        CURLOPT_HTTPHEADER      =>  [
            "Content-type: application/json",
            "Authorization:". $token->token_type. " ". $token->access_token,
        ],
        CURLOPT_POSTFIELDS      =>  json_encode($body),
        CURLOPT_RETURNTRANSFER  =>  true
    ]);
    $res = curl_exec($curl);
    curl_close($curl);
    if($res == "{}"){
        return true;
    }else{
        return false;
    }
}
function welcome($ID){
    global $CFG;

    $token = getoken();
    $curl = curl_init("https://api.line.me/v2/bot/user/$ID/linkToken");
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST   => "POST",
        CURLOPT_HTTPHEADER      =>  [
            "Content-type: application/json",
            "Authorization:". $token->token_type. " ". $token->access_token,
        ],
        CURLOPT_RETURNTRANSFER  =>  true
    ]);
    $res = curl_exec($curl);
    curl_close($curl);
    if($res != '{"message":"Not found"}'){
        return json_decode($res)->linkToken;
    }else{
        return false;
    }
}