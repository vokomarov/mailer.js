/**
 * Created by VovanMS on 26.01.15.
 */
(function($){

	/*
	* Mailer Application Constructor
	* @var    int id     - Identifier for this form
	* @method getId      - Get form identifier
	* @method state      - Set field state (error or valid)
	* @method validate   - Validate field data
	* @method log        - Special console log with identifier
	* @method clean      - Clean data before again build
	* @method send       - Send data to main mail file
	* @method notify     - Show notify for user
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
				url: '../mail.php',
				messageSetting: {
					subject: 'mailer.js works!',
					mailFrom: {
						email: 'no-reply@' + location.hostname,
						name: 'Mailer Js'
					},
					mailTo: {
						email: 'example@example.com',
						name: 'First Name'
					}
				}

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
			this.config = $.extend(true, globalConfig, myConfig);
			/*
			* Extend individual param
			* */
			this.config = $.extend(true,{
				hasError: false,
				data: {}
			}, this.config);
		};

		/*
		* Method Get form id
		* @return int - this form id
		* */
		NewMailerApp.prototype.getId = function(){
			return id;
		};

		/*
		* Method State Element
		* @param  object   - Object for apply state
		* @param  boolean  - State value
		* @return object   - This object
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
		* @param  object obj - Object for input tag
		* @return string     - Value input tag, if pass
		* @return boolean    - False, if not pass
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
		* @param  string message - message for log
		* @param  object message - object for log
		* @return string         - instanse of log
		* */
		NewMailerApp.prototype.log = function(message){
			if(typeof message === 'string'){
				return console.log('mailer.js[form-'+id+']:', message);
			}else if(typeof message === 'object'){
				console.log('mailer.js[form-'+id+']: object log');
				return console.log(message);
			}
			return false;
		};

		/*
		* Method Clean - Clean previous data
		* @return object - this instance object
		* */
		NewMailerApp.prototype.clean = function(){
			this.config.data = {};
			return this;
		};

		/*
		* Method Send - send user data to server
		* @return object - response from the server (JSON)
		* */
		NewMailerApp.prototype.send = function(){
			var
				//setting for php script
				setting = {
					formId : this.getId(),
					useCaptcha: this.config.useCaptcha,
					templateDir: this.config.templateDir,
					templateName: this.config.templateName,
					messageSetting: this.config.messageSetting
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


		NewMailerApp.prototype.notify = function(message){
			console.log(typeof $.toasty);

			if(typeof $.toasty === 'undefined'){
				this.log('Toasty library not defined. For user notify toasty required.');
				return false;
			}
			if(typeof message === 'object'){

			}else{

			}
			return true;

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

			mailer.notify('alala');

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

