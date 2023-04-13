<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php

// INITTIATE DATABASE
$chatBotDB = getChatBotDB();


// SAVE GENERAL SETTINGS TO DATABASE
if (isset($_POST['submit'])) {

    try {
        foreach ($_POST as $field => $value) {
            $chatBotDB->insertOrUpdate($field, $value);
        }


?>
        <script>
            swal("Good job!", "Settings Updated Successfully", "success");
        </script>

    <?php
    } catch (Exception $e) {
    ?>
        <script>
            swal("Opps!", "<?php $e->getMessage(); ?>", "error");
        </script>

<?php
    }
}
    

  




/**

                *   iF KEYS ARE SUBMITTED

*/


if(isset($_POST['submit_keys'])){

    require_once TwilioChatBotPath . "vendor/autoload.php";

    $twilio_auth_token=$_POST["twilio_auth_token"];
    $twilio_account_SID= $_POST['twilio_account_SID'];
 
    try{
     $twilio = new \Twilio\Rest\Client($twilio_account_SID, $twilio_auth_token); 
 
 
     $accounts=$twilio->api->v2010->accounts->read([], 20);
 
 
     foreach ($_POST as $field => $value) {
         $chatBotDB->insertOrUpdate($field, $value);
     }
     
     ?>
     <script>
         swal("Good job!", "Keys Updated Successfully", "success");
     </script>
 
 <?php
    }
    catch(Exception $e){
     ?>
     <script>
         swal("Opps!", "The Keys you Provided are not valid", "error");
     </script>
 
 <?php
    }


}
// ALL DATA

$allData = $chatBotDB->getAll()['data'];


?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />



<div class="" style="padding:0px; min-height:100vh">
    <div class="h2 mt-3 text-center font-weight-bold">Twilio Integration</div>

    <div class="">

        <div class="col-12">
            <div class="card col-12">
                <!-- Default card contents -->



                <div class="alert alert-danger <?php echo ($allData['twilio_account_SID']!="" && $allData['twilio_auth_token']!="" )? 'd-none' : '' ?>"> Please Add Keys from Twilio First!</div>


                <div class="row px-2">

                    <div class="col-md-2 col-12">
                        <ul class=" card nav nav-pills  card-header-tabs nav-fill flex-column px-2" role="tablist">
                            <li class="nav-item mb-4">
                                <a class="nav-link active" data-bs-toggle="tab" href="#sms" role="tab" aria-current="true" href="#"> SMS</a>
                            </li>

                            <li class="nav-item mb-4">
                                <a class="nav-link" data-bs-toggle="tab" href="#whatsapp" role="tab" href="#">WhatsApp</a>
                            </li>

                            <li class="nav-item mb-4">
                                <a class="nav-link" data-bs-toggle="tab" href="#keys" role="tab" href="#">Twilio Configurations</a>
                            </li>
                        </ul>

                    </div>

                    <div class="tab-content card col-md-10 col-12">

                        <!-- // GENERATL SETTINGS FORM  -->
                        <form class="card-body px-3 tab-pane fade show active" method="post" id="sms" role="tabpanel">

                            <h4 class="mb-4">SMS</h4>

                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Enable SMS Alerts:

                                    <div class="text-muted f12">Twilio Charges Will Apply</div>
                                </label>

                                <input type="text" hidden name="enable_sms_alerts" value="off">
                                <input class="switch enable_sms_alerts" value="off" name="enable_sms_alerts" id="enable_sms_alerts" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_sms_alerts" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">

                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Send Customer's Details <span class="text-muted f12">( to admin number ) </span>:</label>

                                <input type="text" hidden name="enable_customer_details_send_sms" value="off">
                                <input class="switch enable_customer_details_send_sms" value="off" name="enable_customer_details_send_sms" id="enable_customer_details_send_sms" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_customer_details_send_sms" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">



                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Send Customer's Chat Logs <span class="text-muted f12">( to admin number ) </span>:</label>

                                <input type="text" hidden name="enable_customer_chat_logs_send_sms" value="off">
                                <input class="switch enable_customer_chat_logs_send_sms" value="off" name="enable_customer_chat_logs_send_sms" id="enable_customer_chat_logs_send_sms" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_customer_chat_logs_send_sms" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">

                            <div class="d-flex flex-wrap mb-2">

                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Phone Number:
                                        <span class="text-muted f12">| to receive logs. | e.g 19495727041 ( without + )</span>
                                    </label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-phone"></i></span>
                                        <input value="<?php echo isset($allData['twilio_sms_alert_phone_number']) ? $allData['twilio_sms_alert_phone_number'] : ''; ?>" placeholder="Phone Number to Receive SMSs" type="number" class="form-control" name="twilio_sms_alert_phone_number">
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex flex-wrap">


                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Chatbot Customer Details Alert's Header Text:</label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-comment"></i></span>
                                        <textarea value="" type="text" class="form-control" name="sms_alert_header_text"><?php echo isset($allData['sms_alert_header_text']) ? $allData['sms_alert_header_text'] : 'A Customer Submitted their Details through Chatbot. Details are Below.'; ?></textarea>
                                    </div>
                                </div>

                            </div>


                            <!-- Submit button -->
                            <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Save</button>
                        </form>

                        <!-- // GENERATL SETTINGS FORM  -->

                        <!-- 

                    *info
                            CUSTOMIZATION
                    *
                    *
                     -->


                        <form class="tab-pane card-body fade px-3" action="" method="POST" id="whatsapp" role="tabpanel">
                            <h4 class="mb-4">WhatsApp</h4>

                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Enable WhatsApp Alerts:
                                    <div class="text-muted f12">Twilio Charges Will Apply</div>
                                </label>

                                <input type="text" hidden name="enable_whatsapp_alerts" value="off">
                                <input class="switch enable_whatsapp_alerts" value="off" name="enable_whatsapp_alerts" id="enable_whatsapp_alerts" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_whatsapp_alerts" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">

                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Send Customer's Details <span class="text-muted f12">( to admin number ) </span>:</label>

                                <input type="text" hidden name="enable_customer_details_send_whatsapp" value="off">
                                <input class="switch enable_customer_details_send_whatsapp" value="off" name="enable_customer_details_send_whatsapp" id="enable_customer_details_send_whatsapp" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_customer_details_send_whatsapp" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">


                            <div class="material-switch d-flex justify-content-between align-items-center ">
                                <label class="form-label" for=""> Send Customer's Chat Logs <span class="text-muted f12">( to admin number ) </span>:</label>

                                <input type="text" hidden name="enable_customer_chat_logs_send_whatsapp" value="off">
                                <input class="switch enable_customer_chat_logs_send_whatsapp" value="off" name="enable_customer_chat_logs_send_whatsapp" id="enable_customer_chat_logs_send_whatsapp" name="someSwitchOption001" type="checkbox" autocomplete="off" />

                                <label for="enable_customer_chat_logs_send_whatsapp" class="label-primary label"></label>
                            </div>
                            <hr class="p-0 mx-1">



                            <div class="d-flex flex-wrap mb-2">

                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">WhatsApp Number:
                                        <span class="text-muted f12"> | to receive logs. | e.g 19495727041 ( without + )</span>
                                    </label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-brands fa-whatsapp"></i></span>
                                        <input value="<?php echo isset($allData['twilio_alert_whatsapp_number']) ? $allData['twilio_alert_whatsapp_number'] : ''; ?>" placeholder="WhatsApp Number to Receive Messages" type="number" class="form-control" name="twilio_alert_whatsapp_number">
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex flex-wrap">


                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Chatbot Customer Details Alert's Header Text:</label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-comment"></i></span>
                                        <textarea value="" type="text" class="form-control" name="whatsapp_alert_header_text"><?php echo isset($allData['whatsapp_alert_header_text']) ? $allData['whatsapp_alert_header_text'] : 'A Customer Submitted their Details through Chatbot. Details are Below.'; ?></textarea>
                                    </div>
                                </div>

                            </div>


                            <!-- Submit button -->
                            <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Save Settings</button>
                        </form>




                        <!-- 

                    *info
                            CUSTOMIZATION
                    *
                    *
                     -->


                        <form class="tab-pane card-body fade px-3" action="" method="POST" id="keys" role="tabpanel">
                            <h4 class="">Credentials</h4>
                            <h6 class=" mb-4 text-danger ">Find your Account SID and Auth Token at twilio.com/console</h6>

                            <div class="d-flex flex-wrap mb-2">

                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Account SID:</label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-key"></i></span>
                                        <input value="<?php echo isset($allData['twilio_account_SID']) ? $allData['twilio_account_SID'] : ''; ?>" placeholder="Account SID from Twilio" type="text" class="form-control" name="twilio_account_SID">
                                    </div>
                                </div>

                            </div>


                            <div class="d-flex flex-wrap mb-2">

                                <div class=" col-12 p-0">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Auth Token:</label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-key"></i></span>
                                        <input value="<?php echo isset($allData['twilio_auth_token']) ? $allData['twilio_auth_token'] : ''; ?>" placeholder="Auth Token from Twilio" type="text" class="form-control" name="twilio_auth_token">
                                    </div>
                                </div>

                            </div>


                           <div class="d-flex flex-wrap mb-2">

                                <div class=" col-12 col-md-6">
                                    <!-- Name input -->
                                    <label for="" class="form-label">WhatsApp Sender Number:
                                        <span class="text-muted f12"> ( without + )</span>
                                    </label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-phone"></i></span>
                                        <input value="<?php echo isset($allData['twilio_phone_number']) ? $allData['twilio_phone_number'] : ''; ?>" placeholder="Twilio Phone Number" type="text" class="form-control" name="twilio_phone_number">
                                    </div>
                                </div>


                                <div class=" col-12 col-md-6">
                                    <!-- Name input -->
                                    <label for="" class="form-label">Messaging Service SID:
                                    </label>
                                    <div class="input-group flex-nowrap mb-3">

                                        <span class="input-group-text" id="addon-wrapping"> <i class="fa-solid fa-key"></i></span>
                                        <input value="<?php echo isset($allData['twilio_sms_service_id']) ? $allData['twilio_sms_service_id'] : ''; ?>" placeholder="Messaging Service SID From Twilio" type="text" class="form-control" name="twilio_sms_service_id">
                                    </div>
                                </div>

                            </div>

                            <!-- Submit button -->
                            <button type="submit" name="submit_keys" class="btn btn-primary btn-block mb-4">Save Settings</button>
                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    jQuery(function($) {




        $(".switch").change(function() {
            var vall = $(this).val()

            if (vall == "on") {
                $(this).val("off")
            } else {
                $(this).val("on")
            }


            // console.log($(this), $(this).val())
        })

        /**@abstract
         * 
         * 
         * ON the TRUE SWITCHES HERE
         */


        <?php
        foreach ($allData as $key => $value) {

            if ($value == "on") {
        ?>
                var e = $(".switch[name='<?php echo $key ?>']")
                // console.log("forech ", e, e.val())
                e.click();
                // e.change();

        <?php
            }
        }

        ?>



        /**@abstract
         * 
         * 
         * 
         * MEDIA SELECTOR
         */

        var mediaUploader;

        $('#chatbot_logo_btn').click(function(e) {
            e.preventDefault();
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on('select', function() {
                attachment = mediaUploader.state().get('selection').first().toJSON();
                console.log("attachment", attachment)
                $('#chatbot_logo').val(attachment.url);
                $("#chatbot_logo_img").attr("src", attachment.url)
            });
            // Open the uploader dialog
            mediaUploader.open();
        });


    })
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>