<?php
define('INCLUDE_DIR', 'include/');

require_once(INCLUDE_DIR . 'libs.php');

/* Get captcha image
 * <img src="/captcha/captcha.php" id="cap" alt="" width="148"><br>
 * <a href="" onClick="$('#cap').attr('src','/captcha/captcha.php?1='+Math.random());return false;">обновить</a>
 * */

$data = new ArrayObject();
$setting = new ArrayObject();
$response = new ArrayObject();
$some_error = new ArrayObject();

//parse incoming data
$post_data = $_POST['data'];
$post_setting = $_POST['setting'];
foreach($post_data as $name=>$value){
    $data[$name] = trim($value);
}
foreach($post_setting as $name=>$value){
    $setting[$name] = $value;
}

//if captcha on - verify
if($setting['useCaptcha'] === 'true'){
    if(captureData($setting['captcha_code'])){

	    //captcha right
        $some_error['captcha'] = false;
    }else{

	    //captcha wrong
        $some_error['captcha'] = true;
    }
}else{

	//captcha off
	$some_error['captcha'] = false;
}


if(!$some_error['captcha']){

    //create setting for template engine
    require_once(INCLUDE_DIR . 'template.php');
    $tpl_setting = array(
        'tpl_dir' => $setting['templateDir']
    );

    $tpl = new Template($tpl_setting);

    if(!$tpl->setTemplate($setting['templateName'])){
        //file don't open
        $response['status'] = 'error';
        $response['error'] = array(
            'title' => "Template file open error",
            'description' => 'Check the path to template file or file name'
        );
        sendResponse($response);
    }

    //append form data to template
    foreach($data as $name=>$value){
        $tpl->set($name, $value);
    }

    /*
     * Message
     * */
    require_once(INCLUDE_DIR . 'PHPMailerAutoload.php');
    $mail = new PHPMailer;

    //set locale message
    if(isset($setting['locale']) && isset($setting['localeDir']))
        $mail->setLanguage($setting['locale'], $setting['localeDir']);

    //set from area
    if(isset($setting['messageSetting']['mailFrom']['email']) && isset($setting['messageSetting']['mailFrom']['name']))
        $mail->setFrom($setting['messageSetting']['mailFrom']['email'], $setting['messageSetting']['mailFrom']['name']);

    //set to area
    if(isset($setting['messageSetting']['mailTo']['email']) && isset($setting['messageSetting']['mailTo']['name']))
        $mail->addAddress($setting['messageSetting']['mailTo']['email'], $setting['messageSetting']['mailTo']['name']);

    //set subject area
    if(isset($setting['messageSetting']['subject']))
        $mail->Subject = $setting['messageSetting']['subject'];

    $response['debug'] = $setting;

        //message type is html
    $mail->isHTML(true);

    //get compiled message
    $mail->Body = $tpl->get();

    //send message
    if(!$mail->send()){

        $response['status'] = 'error';
        $response['error'] = array(
            'title' => 'Error send message',
            'description' => $mail->ErrorInfo
        );
        sendResponse($response);

    }else{

        $response['status'] = 'success';
        $response['message'] = 'Email success send';
        sendResponse($response);

    }

}else{
    //captcha error
    $response['status'] = 'error';
    $response['error'] = array(
        'title' => "Captcha don't not match",
        'description' => 'You input invalid captcha code'
    );
    sendResponse($response);
}