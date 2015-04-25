/**
 * Created by VovanMS on 26.01.15.
 */
(function($){

	/*
	* Mailer Application Constructor
	* @var id - Identifier for each form
	* @method getId - Return this id
	* */
	var MailerApp = (function (myConfig){
		var id = 0,
			/*
			* Global config
			* */
			globalConfig = {
				showMessages: true,
				validation: false,
				useCaptcha: false,
				errorClass: 'error',
				requireClass: 'require',
				validationAttr: 'data-validate',
				templateName: 'mail.tpl',
				templateDir: 'template/',
				url: '../mail.php'
			},
			NewMailerApp;

		/*
		* Main Constructor
		* */
		NewMailerApp = function(myConfig){
			id += 1;
			/*
			 * Default config
			 * */
			this.config = $.extend(globalConfig, myConfig);
			this.config = $.extend(globalConfig,{
				hasError: false,
				data: {}
			});
		};

		/*
		* Method Get form id
		* @return int : current form id
		* */
		NewMailerApp.prototype.getId = function(){
			return id;
		};

		/*
		* Method State Element
		* @param Object : Object for apply state
		* @param Bool : State value
		* @return Object : This object
		* */
		NewMailerApp.prototype.state = function(obj, state){
			if(state){
				$(obj).removeClass(this.config.errorClass);
				if(!this.config.hasError)
					this.config.hasError = false;
			}else{
				$(obj).addClass(this.config.errorClass);
				if(!this.config.hasError){
					$(obj).focus();
					this.config.hasError = true;
				}
			}
			return this;
		};

		/*
		* Method Validate data
		* @param Object : Object for input tag
		* @return String : Value input tag, if pass
		* @return Bool : False, if not pass
		* */
		NewMailerApp.prototype.validate = function(obj){

			var mode = $(obj).attr(this.config.validationAttr),
				val = $(obj).val(),
				require = false,
				regexp = "";

			if($(obj).hasClass(this.config.requireClass))
				require = true;
			if(val === ""){
				if(require){
					this.state(obj, false);
					return false;
				}
			}else{

				//phone are using masked
				if(mode === "isPhone"){
					if(val == ""){
						this.state(obj, false);
						return false;
					}else{
						this.state(obj, true);
						return val;
					}
				}else if(mode === "isString"){
					regexp=/^[a-zA-ZА-Яа-я0-9_.+-іїь]+/i;
					if(!regexp.test(val)){
						this.state(obj, false);
						return false;
					}else{
						this.state(obj, true);
						return val;
					}
				}else if(mode === "isDigit"){
					regexp=/^[0-9]*$/i;
					if(!regexp.test(val)){
						this.state(obj, false);
						return false;
					}else{
						this.state(obj, true);
						return val;
					}
				}else if(mode === "isEmail"){
					regexp=/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/i;
					if(!regexp.test(val)){
						this.state(obj, false);
						return false;
					}else{
						this.state(obj, true);
						return val;
					}
				}
			}
			return false;
		};

		/*
		* Method Log message
		* @param string - message for log
		* @param object - object for log
		* @return string
		* */
		NewMailerApp.prototype.log = function(message){
			if(typeof message === 'string'){
				return console.log('mailer.js[form-'+id+']:', message);
			}else if(typeof message === 'object'){
				console.log('mailer.js[form-'+id+']: object log');
				return console.log(message);
			}

		};

		/*
		* Method Clean - Clean previous data
		* @return object : This object
		* */
		NewMailerApp.prototype.clean = function(){
			this.config.data = {};
			return this;
		};

		/*
		* Method Send - send user data to server
		* @param function : call function before send request
		* @param function : call function after send request
		* @return object : response from the server
		* */
		NewMailerApp.prototype.send = function(){
			var
				//setting for php script
				setting = {
					formId : this.getId(),
					useCaptcha: this.config.useCaptcha,
					templateDir: this.config.templateDir,
					templateName: this.config.templateName
				},

				//build post data
				post = {
					setting: setting,
					data: this.config.data
				},

				//parameters for post ajax
				param = {
					url: this.config.url,
					method: 'POST',
					data: post,
					error: function(response){
						return response;
					},
					success: function(response){
						console.log(JSON.parse(response));
					}

				};

			$.ajax(param);

			return false;
		};

		return NewMailerApp;
	}());


	$.fn.mailer = function(my_param){

		return this.each(function(){

			//save this instance
			var $this = $(this),
				s_button = $this.find("[type='submit']"),
				mailer = new MailerApp(my_param);

			mailer.log("registered form");

			if(!s_button){
				s_button = $this.find("[name='submit']");
			}
			if(s_button){
				//button find
				//start watch for mailer.js

				s_button.click(function(){

					//clear data and previous errors
					mailer.clean();
					$this.find('.'+mailer.config.errorClass).removeClass(mailer.config.errorClass);

					$this.find("[name]").each(function(){
						if(!$(this).is("[name='submit']")){
							//all inputs find there - this

							//build form data to config.data
							var name = $(this).attr("name");
							if(mailer.config.validation){
								var value = mailer.validate($(this));
								if(value)
									mailer.config.data[name] = value;
							}else{
								mailer.config.data[name] = $(this).val();
							}


						}
					});


					if(!mailer.config.hasError){
						mailer.log("Validation pass");
					//	mailer.log(mailer.config.data);

						mailer.send();



					}else{
						//error, show message
						mailer.log("Validation error.");
					}


					//reset has error
					mailer.config.hasError = false;

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
				mailer.log("Error. Submit button not found. Add attribute type='submit' to button tag.");
			}
		});
	};


})(jQuery);

