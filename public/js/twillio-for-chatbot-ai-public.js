(function( $ ) {
	'use strict';

	$(function() {

	});

})( jQuery );


userDataCollectedFuncs.push("send_admin_whatsapp_sms")



function send_admin_whatsapp_sms(){
	let all_msgs=serializeMyData(allMsgs);
	let user_data=serializeMyData(UserPersonalData);

	console.log("User data", JSON.stringify(user_data))

	// SEND REQYES FOR SMS AND WHATSAPP

    $.post({
        "url": restRouteUrl + "ai_chat_bot/send-alerts-to-admin",
        "data": {
        	"msgs": (all_msgs),
        	"user_data": (user_data)
        },
        "success": function (data) {

            console.log("alert result", (data))
        },
        "fail": function (data) {
            console.log("failed", data)
            return false;
        },
        "error": function (xhr, status, error) {
            // var err = eval("(" + xhr.responseText + ")");
            console.log("error", error)
        }
    })
}
