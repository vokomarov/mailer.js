<?php
/**
 * Created by PhpStorm.
 * User: VovanMS
 * Date: 13.01.15
 * Time: 17:09
 * Description: Обробник данних для повідомлень
 */

require_once('config.php');
require_once('libs.php');

/* Get captcha image
 * <img src="/captcha/captcha.php" id="cap" alt="" width="148"><br>
 * <a href="" onClick="$('#cap').attr('src','/captcha/captcha.php?1='+Math.random());return false;">обновить</a>
 * */

function captureData($s){
	if (strlen($s) == 4 && preg_match('/\d{4}/',$s)) {
		$d4 = (abs(($s[0]+$s[1]-$s[2]+10)*($s[0]/$s[1]+$s[2]))*date('j')*date('n'))%10;
		if ($d4 == $s[3]) $capOk = 1;
		else $capOk = 0;
	} else $capOk = 0;
	return	$capOk;
}
$err = true;
if(isset($_POST['captcha'])){
	if(captureData($_POST['captcha'])){
		//captcha ok
		$err = false;
	}else{
		$err = true;
	}
}


if(!$err){
	if(isset($_POST['formid'])){

		//get form id
		$form_id = 0;
		if(trim($_POST['formid']) != ''){
			$form_id = intval(trim($_POST['formid']));
		}

		switch($form_id){
			case 1:
				//From number 1 - "Не дозвонились"

				send_form_1();

				break;
			case 2:
				//From number 2 - "Вызов ТБО"

				send_form_2();

				break;
			case 3:
				//From number 2 - "Вызов КГМ"

				send_form_3();

				break;
			case 4:
				//From number 2 - "Аренда техники"

				send_form_4();

				break;
			case 5:
				//From number 2 - "Напишите нам"

				send_form_5();

				break;
			default:
				break;
		}


	}
}else{
	//Captcha error
	echo 2;
	exit;
}

function send_form_1(){

	GLOBAL $config;

	$phone = "";
	if(trim($_POST['phone']) != ''){
		$phone = trim($_POST['phone']);
	}

	$mail = new multi_mail();

	$mail->set_subject($config[1]["subject"]);
	$mail->set_to($config[1]["to"]);
	$mail->set_from($config[1]["from_mail"]);
	$mail->set_from_name($config[1]["from_name"]);
	$mail->write_body("<h1>Запрос с сайта «Перезвоните мне»</h1>");
	$mail->write_body("<p>Перезвоните мне на номер <b>" . $phone . "</b></p>");
	echo $mail->send();
	exit;
}

function send_form_2(){

	GLOBAL $config;
	$mail = new multi_mail();

	$mail->set_subject($config[2]["subject"]);
	$mail->set_to($config[2]["to"]);
	$mail->write_body("<h1>Заказ с сайта на вывоз ТБО</h1>");

/*
	Название организации:               $org_name
	ФИО:                                $fio
	Телефон:                            $phone
	E-mail:                             $email
	Обьем контейнера:                   $veight
	Адрес контейнерной площадки:        $adress
	Желаемая дата вывоза/поставки:      $date
	Способ оплаты:                      $pay_method
	Дополнительная информация:          $message
*/


	$org_name = "<b>Название организации:</b> ";
	if(isset($_POST['org_name']) && trim($_POST['org_name']) != '' && trim($_POST['org_name']) != 'false'){
		$org_name .= trim($_POST['org_name']);
	}else{
		$org_name .= "не указано";
	}
	$org_name  .= "<br>";
	$mail->write_body($org_name);


	$fio = "<b>ФИО:</b> ";
	if(isset($_POST['fio']) && trim($_POST['fio']) != ''){
		$fio .= trim($_POST['fio']);
		$mail->set_from_name(trim($_POST['fio']));
	}else{
		$fio .= "не указано";
		$mail->set_from_name("не указано");
	}
	$fio  .= "<br>";
	$mail->write_body($fio);


	$phone = "<b>Телефон:</b> ";
	if(isset($_POST['phone']) && trim($_POST['phone']) != ''){
		$phone .= trim($_POST['phone']);
	}else{
		$phone .= "не указано";
	}
	$phone  .= "<br>";
	$mail->write_body($phone);


	$email = "<b>E-mail:</b> ";
	if(isset($_POST['email']) && trim($_POST['email']) != '' && trim($_POST['email']) != 'false'){
		$email .= trim($_POST['email']);
		$mail->set_from(trim($_POST['email']));
	}else{
		$email .= "не указано";
		$mail->set_from($config[2]["from_mail"]);
	}
	$email  .= "<br>";
	$mail->write_body($email);


	$veight = "<b>Обьем контейнера:</b> ";
	if(isset($_POST['veight']) && trim($_POST['veight']) != ''){
		$veight .= trim($_POST['veight']);
	}else{
		$veight .= "не указано";
	}
	$veight  .= "<br>";
	$mail->write_body($veight);


	$adress = "<b>Адрес контейнерной площадки:</b> ";
	if(isset($_POST['adress']) && trim($_POST['adress']) != ''){
		$adress .= trim($_POST['adress']);
	}else{
		$adress .= "не указано";
	}
	$adress  .= "<br>";
	$mail->write_body($adress);


	$date = "<b>Желаемая дата вывоза/поставки:</b> ";
	if(isset($_POST['date']) && trim($_POST['date']) != ''){
		$date .= trim($_POST['date']);
	}else{
		$date .= "не указано";
	}
	$date  .= "<br>";
	$mail->write_body($date);


	$pay_method = "<b>Способ оплаты:</b> ";
	if(isset($_POST['pay_method']) && trim($_POST['pay_method']) != ''){
		$pay_method .= trim($_POST['pay_method']);
	}else{
		$pay_method .= "не указано";
	}
	$pay_method  .= "<br>";
	$mail->write_body($pay_method);


	$message = "<b>Дополнительная информация:</b><br><p>";
	if(isset($_POST['message']) && trim($_POST['message']) != '' && trim($_POST['message']) != 'false'){
		$message .= trim($_POST['message']);
	}else{
		$message .= "не указано";
	}
	$message  .= "</p><br>";
	$mail->write_body($message);

	echo $mail->send();
	exit;

}


function send_form_3(){

	GLOBAL $config;
	$mail = new multi_mail();

	$mail->set_subject($config[3]["subject"]);
	$mail->set_to($config[3]["to"]);
	$mail->write_body("<h1>Заказ с сайта на вывоз КГМ</h1>");

	/*
		Название организации:               $org_name
		ФИО:                                $fio
		Телефон:                            $phone
		E-mail:                             $email
		Обьем контейнера:                   $veight
		Адрес контейнерной площадки:        $adress
		Желаемая дата вывоза/поставки:      $date
		Способ оплаты:                      $pay_method
		Дополнительная информация:          $message
	*/


	$org_name = "<b>Название организации:</b> ";
	if(isset($_POST['org_name']) && trim($_POST['org_name']) != '' && trim($_POST['org_name']) != 'false'){
		$org_name .= trim($_POST['org_name']);
	}else{
		$org_name .= "не указано";
	}
	$org_name  .= "<br>";
	$mail->write_body($org_name);


	$fio = "<b>ФИО:</b> ";
	if(isset($_POST['fio']) && trim($_POST['fio']) != ''){
		$fio .= trim($_POST['fio']);
		$mail->set_from_name(trim($_POST['fio']));
	}else{
		$fio .= "не указано";
		$mail->set_from_name("не указано");
	}
	$fio  .= "<br>";
	$mail->write_body($fio);


	$phone = "<b>Телефон:</b> ";
	if(isset($_POST['phone']) && trim($_POST['phone']) != ''){
		$phone .= trim($_POST['phone']);
	}else{
		$phone .= "не указано";
	}
	$phone  .= "<br>";
	$mail->write_body($phone);


	$email = "<b>E-mail:</b> ";
	if(isset($_POST['email']) && trim($_POST['email']) != '' && trim($_POST['email']) != 'false'){
		$email .= trim($_POST['email']);
		$mail->set_from(trim($_POST['email']));
	}else{
		$email .= "не указано";
		$mail->set_from($config[3]["from_mail"]);
	}
	$email  .= "<br>";
	$mail->write_body($email);


	$veight = "<b>Обьем контейнера:</b> ";
	if(isset($_POST['veight']) && trim($_POST['veight']) != ''){
		$veight .= trim($_POST['veight']);
	}else{
		$veight .= "не указано";
	}
	$veight  .= "<br>";
	$mail->write_body($veight);


	$adress = "<b>Адрес контейнерной площадки:</b> ";
	if(isset($_POST['adress']) && trim($_POST['adress']) != ''){
		$adress .= trim($_POST['adress']);
	}else{
		$adress .= "не указано";
	}
	$adress  .= "<br>";
	$mail->write_body($adress);


	$date = "<b>Желаемая дата вывоза/поставки:</b> ";
	if(isset($_POST['date']) && trim($_POST['date']) != ''){
		$date .= trim($_POST['date']);
	}else{
		$date .= "не указано";
	}
	$date  .= "<br>";
	$mail->write_body($date);


	$pay_method = "<b>Способ оплаты:</b> ";
	if(isset($_POST['pay_method']) && trim($_POST['pay_method']) != ''){
		$pay_method .= trim($_POST['pay_method']);
	}else{
		$pay_method .= "не указано";
	}
	$pay_method  .= "<br>";
	$mail->write_body($pay_method);


	$message = "<b>Дополнительная информация:</b><br><p>";
	if(isset($_POST['message']) && trim($_POST['message']) != '' && trim($_POST['message']) != 'false'){
		$message .= trim($_POST['message']);
	}else{
		$message .= "не указано";
	}
	$message  .= "</p><br>";
	$mail->write_body($message);

	echo $mail->send();
	exit;

}




function send_form_4(){

	GLOBAL $config;
	$mail = new multi_mail();

	$mail->set_subject($config[4]["subject"]);
	$mail->set_to($config[4]["to"]);
	$mail->write_body("<h1>Заказ с сайта на аренду техники</h1>");

	/*
		Название организации:               $org_name
		ФИО:                                $fio
		Телефон:                            $phone
		E-mail:                             $email
		Вид техники:                        $machine
		Желаемая дата:                      $date
		Способ оплаты:                      $pay_method
		Дополнительная информация:          $message
	*/


	$org_name = "<b>Название организации:</b> ";
	if(isset($_POST['org_name']) && trim($_POST['org_name']) != '' && trim($_POST['org_name']) != 'false'){
		$org_name .= trim($_POST['org_name']);
	}else{
		$org_name .= "не указано";
	}
	$org_name  .= "<br>";
	$mail->write_body($org_name);


	$fio = "<b>ФИО:</b> ";
	if(isset($_POST['fio']) && trim($_POST['fio']) != ''){
		$fio .= trim($_POST['fio']);
		$mail->set_from_name(trim($_POST['fio']));
	}else{
		$fio .= "не указано";
		$mail->set_from_name("не указано");
	}
	$fio  .= "<br>";
	$mail->write_body($fio);


	$phone = "<b>Телефон:</b> ";
	if(isset($_POST['phone']) && trim($_POST['phone']) != ''){
		$phone .= trim($_POST['phone']);
	}else{
		$phone .= "не указано";
	}
	$phone  .= "<br>";
	$mail->write_body($phone);


	$email = "<b>E-mail:</b> ";
	if(isset($_POST['email']) && trim($_POST['email']) != '' && trim($_POST['email']) != 'false'){
		$email .= trim($_POST['email']);
		$mail->set_from(trim($_POST['email']));
	}else{
		$email .= "не указано";
		$mail->set_from($config[4]["from_mail"]);
	}
	$email  .= "<br>";
	$mail->write_body($email);


	$machine = "<b>Вид техники:</b> ";
	if(isset($_POST['machine']) && trim($_POST['machine']) != ''){
		$machine .= trim($_POST['machine']);
	}else{
		$machine .= "не указано";
	}
	$machine  .= "<br>";
	$mail->write_body($machine);


	$date = "<b>Желаемая дата: </b> ";
	if(isset($_POST['date']) && trim($_POST['date']) != ''){
		$date .= trim($_POST['date']);
	}else{
		$date .= "не указано";
	}
	$date  .= "<br>";
	$mail->write_body($date);


	$pay_method = "<b>Способ оплаты:</b> ";
	if(isset($_POST['pay_method']) && trim($_POST['pay_method']) != ''){
		$pay_method .= trim($_POST['pay_method']);
	}else{
		$pay_method .= "не указано";
	}
	$pay_method  .= "<br>";
	$mail->write_body($pay_method);


	$message = "<b>Дополнительная информация:</b><br><p>";
	if(isset($_POST['message']) && trim($_POST['message']) != '' && trim($_POST['message']) != 'false'){
		$message .= trim($_POST['message']);
	}else{
		$message .= "не указано";
	}
	$message  .= "</p><br>";
	$mail->write_body($message);

	echo $mail->send();
	exit;

}


function send_form_5(){

	GLOBAL $config;
	$mail = new multi_mail();

	$mail->set_subject($config[5]["subject"]);
	$mail->set_to($config[5]["to"]);
	$mail->write_body("<h1>Сообщение с сайта</h1>");

	/*
		ФИО:                                $fio
		Телефон:                            $phone
		E-mail:                             $email
		Сообщение:                          $message
	*/

	$fio = "<b>ФИО:</b> ";
	if(isset($_POST['fio']) && trim($_POST['fio']) != ''){
		$fio .= trim($_POST['fio']);
		$mail->set_from_name(trim($_POST['fio']));
	}else{
		$fio .= "не указано";
		$mail->set_from_name("не указано");
	}
	$fio  .= "<br>";
	$mail->write_body($fio);


	$phone = "<b>Телефон:</b> ";
	if(isset($_POST['phone']) && trim($_POST['phone']) != ''){
		$phone .= trim($_POST['phone']);
	}else{
		$phone .= "не указано";
	}
	$phone  .= "<br>";
	$mail->write_body($phone);


	$email = "<b>E-mail:</b> ";
	if(isset($_POST['email']) && trim($_POST['email']) != '' && trim($_POST['email']) != 'false'){
		$email .= trim($_POST['email']);
		$mail->set_from(trim($_POST['email']));
	}else{
		$email .= "не указано";
		$mail->set_from($config[5]["from_mail"]);
	}
	$email  .= "<br>";
	$mail->write_body($email);


	$message = "<b>Сообщение:</b><br><p>";
	if(isset($_POST['message']) && trim($_POST['message']) != ''){
		$message .= trim($_POST['message']);
	}else{
		$message .= "не указано";
	}
	$message  .= "</p><br>";
	$mail->write_body($message);

	echo $mail->send();
	exit;

}