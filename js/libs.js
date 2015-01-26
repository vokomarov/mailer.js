/*
* Сообщение после отправки 3-х форм на главной + в шапке
* */
var MESSAGE_SUCCESS_1 = "Спасибо за заявку. <br>Менеджер свяжется с Вами в ближайшее время.";

/*
 * Сообщение после отправки формы со страницы "Напишите нам"
 * */
var MESSAGE_SUCCESS_2 = "Ваше сообщение отправлено. <br>Мы свяжемся с Вами вближайшее время.";



function smessage(str, reload){

	$().toasty({
		message: str,
		position: "tr",
		autoHide: 3000,
		afterDestroy: function(toastObject, eventObject) {
			if(reload){
				location.reload();
			}
		}
	});

	return true;
}
var hasError = false;
function state(obj, state){
	if(state){
		$(obj).removeClass("error");
		hasError = false;
	}else{
		$(obj).addClass("error");
		if(hasError == false){
			$(obj).focus();
			hasError = true;

		}
	}
	return true;
}

function validate(obj){
	var mode = $(obj).attr("data-validate");
	var val = $(obj).val();
	var require = false;
	if($(obj).hasClass("require"))
		require = true;

	if(val == ""){
		if(require){
			state(obj, false);
			return false;
		}
	}else{
		var regexp = "";
		if(mode == "isPhone"){
			if(val == ""){
				state(obj, false);
				return false;
			}else{
				state(obj, true);
				return val;
			}
		}else if(mode == "isString"){
			regexp=/[0-9a-zA-Zа-яА-Яії.,!-=]+/i;
			if(!regexp.test(val)){
				state(obj, false);
				return false;
			}else{
				state(obj, true);
				return val;
			}
		}else if(mode == "isDigit"){
			regexp=/[0-9]+/i;
			if(!regexp.test(val)){
				state(obj, false);
				return false;
			}else{
				state(obj, true);
				return val;
			}
		}else if(mode == "isEmail"){
			regexp=/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/i;
			if(!regexp.test(val)){
				state(obj, false);
				return false;
			}else{
				state(obj, true);
				return val;
			}
		}

	}

	return false;

}


$(document).ready(function(){
	//+7 (904) 394 90 13
	$('#phone, #phone2, #phone3, #phone4, #phone5').mask("+7 (999) 999 99 99");
	$('.return-top').click(function() {
		$('body,html').animate({scrollTop:0},800);
			return false;
	});
	$('.services img').parent().css({'backgroundColor':'#fff'});


	$("#send_form_1").click(function(e){

		var phone = validate($(".phone-input"));
		var captcha = "";

		if(!hasError){

			var msg = "Введите код с картинки<br>" +
				"<img src=\"/mail/captcha/captcha.php\" id=\"cap\" alt=\"\" width=\"80\">"+
				"<a href=\"\" onClick=\"$('#cap').attr('src','/mail/captcha/captcha.php?1='+Math.random());return false;\">обновить</a><br>"+
				"<input type='text' id='captcha'/> "+
				"<input type='button' value='OK' class='closeToast'/>";

			$().toasty({
				message: msg,
				modal: true,
				position: "tc",
				afterHide: function (toast, eventObj){
					if(eventObj.currentTarget.value === 'OK' && $('#captcha').val()!=""){
						captcha = $('#captcha').val();
						$.ajax({
							url: 'mail/mail.php',
							type: 'POST',
							data: {
								"formid" : 1,
								"phone"  : phone,
								"captcha": captcha
							},
							error: function(e){
								smessage(e, false);
							},
							success: function(retrn){
								if(retrn == "2")
									smessage("Код с картинки не подходит", false);
								else if(retrn == "1")
									smessage(MESSAGE_SUCCESS_1, true);
							}
						});

					}
				}
			});


		}else{
			smessage("Проверьте правильность заполнения полей", false);
		}

		e.preventDefault();
		return false;
	});


	$("#send_form_2").click(function(e){

		var org_name = validate($("#name-org"));
		var fio = validate($("#fio"));
		var phone = validate($("#phone2"));
		var email = validate($("#email"));
		var veight = validate($("#size"));
		var adress = validate($("#address"));
		var date = validate($("#date-arrive"));
		var pay_method = $(".setcalc1:checked").val();
		var message = validate($("#add-info"));

		var captcha = "";


		if(!hasError){

			var msg = "Введите код с картинки<br>" +
				"<img src=\"/mail/captcha/captcha.php\" id=\"cap\" alt=\"\" width=\"80\">"+
				"<a href=\"\" onClick=\"$('#cap').attr('src','/mail/captcha/captcha.php?1='+Math.random());return false;\">обновить</a><br>"+
				"<input type='text' id='captcha'/> "+
				"<input type='button' value='OK' class='closeToast'/>";

			$().toasty({
				message: msg,
				modal: true,
				position: "tc",
				afterHide: function (toast, eventObj){
					if(eventObj.currentTarget.value === 'OK' && $('#captcha').val()!=""){
						captcha = $('#captcha').val();
						$.ajax({
							url: 'mail/mail.php',
							type: 'POST',
							data: {
								"formid" : 2,
								"captcha": captcha,
								"org_name" : org_name,
								"fio" : fio,
								"phone" : phone,
								"email" : email,
								"veight" : veight,
								"adress" : adress,
								"date" : date,
								"pay_method" : pay_method,
								"message" : message
							},
							error: function(e){
								smessage(e, false);
							},
							success: function(retrn){
								if(retrn == "2")
									smessage("Код с картинки не подходит", false);
								else if(retrn == "1")
									smessage(MESSAGE_SUCCESS_1, true);
							}
						});

					}
				}
			});


		}else{
			smessage("Проверьте правильность заполнения полей", false);
		}

		e.preventDefault();
		return false;
	});

	$("#send_form_3").click(function(e){

		var org_name = validate($("#name-org2"));
		var fio = validate($("#fio2"));
		var phone = validate($("#phone3"));
		var email = validate($("#email2"));
		var veight = validate($("#size2"));
		var adress = validate($("#address2"));
		var date = validate($("#date-arrive2"));
		var pay_method = $(".setcalc2:checked").val();
		var message = validate($("#add-info2"));

		var captcha = "";


		if(!hasError){

			var msg = "Введите код с картинки<br>" +
				"<img src=\"/mail/captcha/captcha.php\" id=\"cap\" alt=\"\" width=\"80\">"+
				"<a href=\"\" onClick=\"$('#cap').attr('src','/mail/captcha/captcha.php?1='+Math.random());return false;\">обновить</a><br>"+
				"<input type='text' id='captcha'/> "+
				"<input type='button' value='OK' class='closeToast'/>";

			$().toasty({
				message: msg,
				modal: true,
				position: "tc",
				afterHide: function (toast, eventObj){
					if(eventObj.currentTarget.value === 'OK' && $('#captcha').val()!=""){
						captcha = $('#captcha').val();
						$.ajax({
							url: 'mail/mail.php',
							type: 'POST',
							data: {
								"formid" : 3,
								"captcha": captcha,
								"org_name" : org_name,
								"fio" : fio,
								"phone" : phone,
								"email" : email,
								"veight" : veight,
								"adress" : adress,
								"date" : date,
								"pay_method" : pay_method,
								"message" : message
							},
							error: function(e){
								smessage(e, false);
							},
							success: function(retrn){
								if(retrn == "2")
									smessage("Код с картинки не подходит", false);
								else if(retrn == "1")
									smessage(MESSAGE_SUCCESS_1, true);
							}
						});

					}
				}
			});


		}else{
			smessage("Проверьте правильность заполнения полей", false);
		}

		e.preventDefault();
		return false;
	});

	$("#send_form_4").click(function(e){

		var org_name = validate($("#name-org3"));
		var fio = validate($("#fio3"));
		var phone = validate($("#phone4"));
		var email = validate($("#email3"));
		var pay_method = $(".setcalc3:checked").val();
		var machine = $(".setcalc3 option:selected").val();
		var date = validate($("#date-arrive3"));
		var message = validate($("#add-info3"));

		var captcha = "";


		if(!hasError){

			var msg = "Введите код с картинки<br>" +
				"<img src=\"/mail/captcha/captcha.php\" id=\"cap\" alt=\"\" width=\"80\">"+
				"<a href=\"\" onClick=\"$('#cap').attr('src','/mail/captcha/captcha.php?1='+Math.random());return false;\">обновить</a><br>"+
				"<input type='text' id='captcha'/> "+
				"<input type='button' value='OK' class='closeToast'/>";

			$().toasty({
				message: msg,
				modal: true,
				position: "tc",
				afterHide: function (toast, eventObj){
					if(eventObj.currentTarget.value === 'OK' && $('#captcha').val()!=""){
						captcha = $('#captcha').val();
						$.ajax({
							url: 'mail/mail.php',
							type: 'POST',
							data: {
								"formid" : 4,
								"captcha": captcha,
								"org_name" : org_name,
								"fio" : fio,
								"phone" : phone,
								"email" : email,
								"machine" : machine,
								"date" : date,
								"pay_method" : pay_method,
								"message" : message
							},
							error: function(e){
								smessage(e, false);
							},
							success: function(retrn){
								if(retrn == "2")
									smessage("Код с картинки не подходит", false);
								else if(retrn == "1")
									smessage(MESSAGE_SUCCESS_1, true);
							}
						});

					}
				}
			});


		}else{
			smessage("Проверьте правильность заполнения полей", false);
		}

		e.preventDefault();
		return false;
	});


	$("#send_form_5").click(function(e){

		var fio = validate($("#fio5"));
		var phone = validate($("#phone5"));
		var email = validate($("#email5"));
		var message = validate($("#message5"));

		var captcha = "";


		if(!hasError){

			var msg = "Введите код с картинки<br>" +
				"<img src=\"/mail/captcha/captcha.php\" id=\"cap\" alt=\"\" width=\"80\">"+
				"<a href=\"\" onClick=\"$('#cap').attr('src','/mail/captcha/captcha.php?1='+Math.random());return false;\">обновить</a><br>"+
				"<input type='text' id='captcha'/> "+
				"<input type='button' value='OK' class='closeToast'/>";

			$().toasty({
				message: msg,
				modal: true,
				position: "tc",
				afterHide: function (toast, eventObj){
					if(eventObj.currentTarget.value === 'OK' && $('#captcha').val()!=""){
						captcha = $('#captcha').val();
						$.ajax({
							url: 'mail/mail.php',
							type: 'POST',
							data: {
								"formid" : 5,
								"captcha": captcha,
								"fio" : fio,
								"phone" : phone,
								"email" : email,
								"message" : message
							},
							error: function(e){
								smessage(e, false);
							},
							success: function(retrn){
								if(retrn == "2")
									smessage("Код с картинки не подходит", false);
								else if(retrn == "1")
									smessage(MESSAGE_SUCCESS_2, true);
							}
						});

					}
				}
			});


		}else{
			smessage("Проверьте правильность заполнения полей", false);
		}

		e.preventDefault();
		return false;
	});

});