<?php

add_action('rest_api_init', function () {

    register_rest_route('ai_chat_bot', '/send-alerts-to-admin', array(
        'methods' => 'POST',
        'callback' =>  'ai_chat_bot_send_alerts_to_admin',
        'permission_callback' => ''
    ));

    register_rest_route('ai_chat_bot', '/send-to-whatsapp', array(
        'methods' => 'POST',
        'callback' =>  'ai_chat_bot_send_to_whatsapp',
        'permission_callback' => ''
    ));
});



function ai_chat_bot_send_alerts_to_admin(WP_REST_Request $req)
{
    // $req= $_POST;
    $chatBotDB = getChatBotDB();
    $whatsApp_enable = $chatBotDB->getValue("enable_whatsapp_alerts");
    $whatsApp_enable = $whatsApp_enable['code'] == 200 ? $whatsApp_enable['data']->meta_value : "off";

    $sms_enable = $chatBotDB->getValue("enable_sms_alerts");
    $sms_enable = $sms_enable['code'] == 200 ? $sms_enable['data']->meta_value : "off";


    $enable_customer_details_send_sms = $chatBotDB->getValue("enable_customer_details_send_sms");
    $enable_customer_details_send_sms = $enable_customer_details_send_sms['code'] == 200 ? $enable_customer_details_send_sms['data']->meta_value : "off";

    $enable_customer_chat_logs_send_sms = $chatBotDB->getValue("enable_customer_chat_logs_send_sms");
    $enable_customer_chat_logs_send_sms = $enable_customer_chat_logs_send_sms['code'] == 200 ? $enable_customer_chat_logs_send_sms['data']->meta_value : "off";

 
    $enable_customer_chat_logs_send_whatsapp = $chatBotDB->getValue("enable_customer_chat_logs_send_whatsapp");
    $enable_customer_chat_logs_send_whatsapp = $enable_customer_chat_logs_send_whatsapp['code'] == 200 ? $enable_customer_chat_logs_send_whatsapp['data']->meta_value : "off";

    $enable_customer_details_send_whatsapp = $chatBotDB->getValue("enable_customer_details_send_whatsapp");
    $enable_customer_details_send_whatsapp = $enable_customer_details_send_whatsapp['code'] == 200 ? $enable_customer_details_send_whatsapp['data']->meta_value : "off";

 









    $msgs_to_text = "\n  \nChat Log";
    foreach ($req['msgs'] as $key => $value) {
        $msgs_to_text .= $key . " : " . $value . " \n";
    }
  

    $user_data_to_text = "User Details: \n ";
    
    foreach ($req['user_data'] as $key => $value) {
        $user_data_to_text .= ucwords($key) . " : " . $value . " \n";
    }


    $req = [];
    $req['user_data_to_text'] = $user_data_to_text;
    $req['msgs_to_text'] = $msgs_to_text;




    /**
     * 
     * CHECK WHICH ONE IS ENABLED
     * 
     */

    $res=[];
    if ($whatsApp_enable == "on") {
        $wa_req= $req;

        if($enable_customer_chat_logs_send_whatsapp=="on"){
            $wa_req['msgs_to_text']="\n";
        }


        if($enable_customer_details_send_whatsapp=="on"){
            $wa_req['user_data_to_text']="\n";
        }


         $res[]= ai_chat_bot_send_to_whatsapp($wa_req);
    }

    if ($sms_enable == "on") {

        $sms_req= $req;

        if($enable_customer_chat_logs_send_sms=="on"){
            $sms_req['msgs_to_text']="\n";
        }


        if($enable_customer_details_send_sms=="on"){
            $sms_req['user_data_to_text']="\n";
        }

        $res[]=  ai_chat_bot_send_to_sms($sms_req);
    }



    return json_encode($res);
}



/**
 * 
 * 
 * SEND WHATSAPP
 * 
 * 
 */


function ai_chat_bot_send_to_whatsapp($req)
{

    require_once TwilioChatBotPath . "vendor/autoload.php";

    // INITTIATE DATABASE
    $chatBotDB = getChatBotDB();

    $account_SID = $chatBotDB->getValue("twilio_account_SID");
    $account_SID = $account_SID['code'] == 200 ? $account_SID['data']->meta_value : "";

    $auth_token = $chatBotDB->getValue("twilio_auth_token");
    $auth_token = $auth_token['code'] == 200 ? $auth_token['data']->meta_value : "";

    $whatsapp_number = $chatBotDB->getValue("twilio_alert_whatsapp_number");
    $whatsapp_number = $whatsapp_number['code'] == 200 ? $whatsapp_number['data']->meta_value : "";

    $twilio_phone_number = $chatBotDB->getValue("twilio_phone_number");
    $twilio_phone_number = $twilio_phone_number['code'] == 200 ? $twilio_phone_number['data']->meta_value : "";

    $whatsapp_header_text = $chatBotDB->getValue("whatsapp_alert_header_text");
    $whatsapp_header_text = $whatsapp_header_text['code'] == 200 ? $whatsapp_header_text['data']->meta_value : "";

    $send_to = $whatsapp_number;
    $send_from = $twilio_phone_number;


    // $msg= $req['message'];


    if ($send_from == "" || $send_to == "") {

        return (
            [
                'code' => 400,
                "message" => "Please provide both Sender and Receiver's Phone Number"
            ]
        );
    }




    if ($auth_token == "" || $account_SID == "") {

        return (
            [
                'code' => 400,
                "message" => "Please add Keys from Twillio first!"
            ]
        );
    }


    $sid    =   $account_SID;
    $token  = $auth_token;

    $twilio = new Twilio\Rest\Client($sid, $token);

    try {

        $message = $twilio->messages
            ->create(
                "whatsapp:+" . $send_to, // to 
                array(
                    "from" => "whatsapp:+" . $send_from,
                    "body" => $whatsapp_header_text . "\n  \n \n \n"  . $req['msgs_to_text'] . " \n \n" . $req['user_data_to_text'] . " \n \n Message from Maven Chatbot"
                )
            );
    } catch (Exception $e) {
        return (
            [
                'code' => 400,
                "message" => $e->getMessage()
            ]
        );
    }


    //  SUCCESSFULL 
    return (
        [
            'code' => 200,
            "message" => "Whatsapp Sent Successfully!!!"
        ]
    );
}








/**
 * 
 * 
 * 
 * SEND SMSSSSSSSS
 * 
 */



function ai_chat_bot_send_to_sms($req)
{
    require_once TwilioChatBotPath . "vendor/autoload.php";

    // INITTIATE DATABASE
    $chatBotDB = getChatBotDB();

    $account_SID = $chatBotDB->getValue("twilio_account_SID");
    $account_SID = $account_SID['code'] == 200 ? $account_SID['data']->meta_value : "";

    $auth_token = $chatBotDB->getValue("twilio_auth_token");
    $auth_token = $auth_token['code'] == 200 ? $auth_token['data']->meta_value : "";

    $sms_phone_number = $chatBotDB->getValue("twilio_sms_alert_phone_number");
    $sms_phone_number = $sms_phone_number['code'] == 200 ? $sms_phone_number['data']->meta_value : "";

    $twilio_phone_number = $chatBotDB->getValue("twilio_phone_number");
    $twilio_phone_number = $twilio_phone_number['code'] == 200 ? $twilio_phone_number['data']->meta_value : "";

    $sms_header_text = $chatBotDB->getValue("sms_alert_header_text");
    $sms_header_text = $sms_header_text['code'] == 200 ? $sms_header_text['data']->meta_value : "";

    $twilio_sms_service_id = $chatBotDB->getValue("twilio_sms_service_id");
    $twilio_sms_service_id = $twilio_sms_service_id['code'] == 200 ? $twilio_sms_service_id['data']->meta_value : "";


    $send_to = $sms_phone_number;
    $send_from = $twilio_phone_number;

    // $msg= $req['message'];


    if ($send_from == ""  || $send_to == "") {

        return (
            [
                'code' => 400,
                "message" => "Please provide both Sender and Receiver's Phone Number"
            ]
        );
    }

  


    if ($auth_token == "" || $account_SID == "" || $twilio_sms_service_id == "") {

        return (
            [
                'code' => 400,
                "message" => "Please add Keys from Twillio first!"
            ]
        );
    }

    $sid    =   $account_SID;
    $token  = $auth_token;

    $twilio = new Twilio\Rest\Client($sid, $token);

    try {
        $message = $twilio->messages
            ->create(
                "+" . $send_to, // to 
                array(
                    "messagingServiceSid" => $twilio_sms_service_id,
                    "body" => $sms_alert_header_text . "\n  \n \n \n" . $req['msgs_to_text'] . " \n \n" . $req['user_data_to_text'] . " \n \n Message from Maven Chatbot"
                )
            );
    } catch (Exception $e) {
        return (
            [
                'code' => 400,
                "message" => $e->getMessage()
            ]
        );
    }

    return (
        [
            'code' => 200,
            "message" => "SMS Sent Successfully!!!"
        ]
    );
}
