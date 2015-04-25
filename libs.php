<?php
/**
 * Created by PhpStorm
 * User: VovanMS
 * Date: 13.01.15
 * Time: 16:28
 * Description: Бібліотека класів та функцій для відправки на пошту
 */

/**
 * Кодує заголовок відподідно заданої кодіровки
 * на виході закодована стрічка
 */
function mime_header_encode($str, $data_charset, $send_charset){
	if ($data_charset != $send_charset) {
		$str = iconv($data_charset, $send_charset, $str);
	}

	return $str;
}

//review captcha code
function captureData($s){
    if (strlen($s) == 4 && preg_match('/\d{4}/',$s)) {
        $d4 = (abs(($s[0]+$s[1]-$s[2]+10)*($s[0]/$s[1]+$s[2]))*date('j')*date('n'))%10;
        if ($d4 == $s[3]) $capOk = 1;
        else $capOk = 0;
    } else $capOk = 0;
    return	$capOk;
}

//send response and kill app
function sendResponse($response){
    echo json_encode($response);
    exit;
}


/**
 * Головний клас повідомлення
 * */

class multi_mail{

	//Відправник
	var $from;

	//Ім’я відправника
	var $name_from;

	//Отримувач
	var $to;

	//Заголовки повідомлення
	var $headers;

	//Тема листа
	var $subject;

	//Тіло повідомлення
	var $body;

	/*
	 * Конструктор класу
	 * ініціалізація змінних
	 *  - Відправник
	 *  - Отримувач
	 *  - Тіло
	 *  - Заголовок
	 *  - Тема
	 */
	function multi_mail(){
		$this->from = "";
		$this->name_from = "";
		$this->to = "";
		$this->body = "";
		$this->headers = Array();
		$this->subject = "";
	}

	//Записуємо відправника
	function set_from($from){
		$this->from = $from;
	}

	//Записуємо ім’я відправника
	function set_from_name($from_name){
		$this->name_from = $from_name;
	}

	//Записуємо отримувача
	function set_to($to){
		$this->to = $to;
	}

	//Записуємо тему
	function set_subject($subject){
		$this->subject = $subject;
	}

	function write_body($str){
		$this->body .= $str;
	}

	/*
	 * Встановлюємо заголовки повідомлення
	 * */
	function set_headers(){

		$this->headers = "MIME-Version: 1.0\r\n";
		$this->headers .= "Content-type: text/html; charset=utf-8\r\n";
		$this->headers .= "From: " . $this->name_from . " <" . $this->from . "> \r\n";
		$this->headers .= "Reply-To: ".$this->from." \r\n";

	}


	/*
	 * Відправляємо пошту
	 * */
	function send(){

		$this->set_headers();

		return mail($this->to, $this->subject, $this->body, $this->headers);

	}

}