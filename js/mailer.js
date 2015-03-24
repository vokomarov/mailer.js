/**
 * Created by VovanMS on 26.01.15.
 */
var id_counter = 0;
(function($){
	$.fn.mailer = function(my_param){

		// Стандартний конфіг
		var config = $.extend({
			formId: 0,
			showMessages: true,
			validation: false,
			errorClass: 'error',
			requireClass: 'require',
			validationAttr: 'data-validate',
			url: '../mail.php',
			hasError: false,
			data: {}
		}, my_param);

		return this.each(function(){

			//save form handle
			var $this = $(this);

			//set form id
			config.formId = id_counter;
			id_counter++;

			log("registered form", config);

			var s_button = $this.find("[type='submit']");
			if(!s_button){
				s_button = $this.find("[name='submit']");
			}
			if(s_button){
				//button find

				s_button.click(function(){

					//clear data and previous errors
					config.data = {};
					$this.find('.'+config.errorClass).removeClass(config.errorClass);

					$this.find("[name]").each(function(){
						if(!$(this).is("[name='submit']")){
							//all inputs find there - this

							//build form data to config.data
							var name = $(this).attr("name");
							if(config.validation){
								var value = validate($(this), config);
								if(value)
									config.data[name] = value;
							}else{
								config.data[name] = $(this).val();
							}


						}
					});


					if(!config.hasError){
						log("Validation pass", config);
						console.log(config.data);

						var setting = {},
							post = {};

						setting['formId'] = config.formId;

						post['setting'] = setting;
						post['data'] = config.data;

						//send to php module
						$.ajax({
							url: config.url,
							method: 'POST',
							data: post,
							error: function(response){},
							success: function(response){
								console.log(response);
							}
						});



					}else{
						//error, show message
						log("Validation error.", config);
					}


					//reset has error
					config.hasError = false;

					return false;
				});

				//don't send form
				if($this.is('form')){
					$this.submit(function(){
						return false;
					});
				}else{
					$this.find("form").submit(function(){});
				}



			}else{
				log("Error. Submit button not found. Add attribute type='submit' to button tag.");
			}
		});
	};


	function state(obj, state, config){
		if(state){
			$(obj).removeClass(config.errorClass);
			if(!config.hasError)
				config.hasError = false;
		}else{
			$(obj).addClass(config.errorClass);
			if(!config.hasError){
				$(obj).focus();
				config.hasError = true;
			}
		}
		return true;
	}

	/* Validate data
	*  @return value if pass
	*  @return false if not pass
	* */
	function validate(obj, config){

		var mode = $(obj).attr(config.validationAttr);
		var val = $(obj).val();
		var require = false;
		if($(obj).hasClass(config.requireClass))
			require = true;
		if(val === ""){
			if(require){
				state(obj, false, config);
				return false;
			}
		}else{

			//phone are using masked
			var regexp = "";
			if(mode === "isPhone"){
				if(val == ""){
					state(obj, false, config);
					return false;
				}else{
					state(obj, true, config);
					return val;
				}
			}else if(mode === "isString"){
				regexp=/^[a-zA-ZА-Яа-я0-9_.+-іїь]+/i;
				if(!regexp.test(val)){
					state(obj, false, config);
					return false;
				}else{
					state(obj, true, config);
					return val;
				}
			}else if(mode === "isDigit"){
				regexp=/[0-9]+/i;
				if(!regexp.test(val)){
					state(obj, false, config);
					return false;
				}else{
					state(obj, true, config);
					return val;
				}
			}else if(mode === "isEmail"){
				regexp=/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/i;
				if(!regexp.test(val)){
					state(obj, false, config);
					return false;
				}else{
					state(obj, true, config);
					return val;
				}
			}
		}
		return false;
	}

	function log(text, config){
		console.log("mailer.js[form " + config.formId + "]: " + text);
	}

})(jQuery);

