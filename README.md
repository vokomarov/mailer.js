# mailer.js
Простий фреймворк для перевірки та надсилання даних з HTML форм на email

## Особливості
+ необхідний jQuery (1.1.1+)
+ кроссбраузерність Chrome, Safari, Firefox, Opera 9+, IE 8+
+ валідація введених данних
+ одночасна робота кількох форм на одній сторінці
+ асинхронна передача даних на сервер
+ далі буде..



## Швидкий старт
Щоб почати використовувати плагін, необхідно завантажити папку плагіну **mailer.js** зі всім її вмістом.
Далі необхідно оголосити скріпт виклику бібліотеки в секції ***<head>*** вашої сторінки, або перед закриваючим тегом ***<body>***:

```html
<script src="jquery.js" type="text/javascript"></script>
<script src="mailer.js/mailer.js" type="text/javascript"></script>
```

Тепер можна ініціалізувати плагін:

```javascript
$(document).ready(function(){
    $("#form-box").mailer();
});
```

Зверніть увагу! Ідентифікатор **#form-box** вказує на контейнер, що містить у собі поля форми та одну кнопку з іменем *submit*
Приклади обов’язкової конфігурації:

```html
<form id="form-cont">
	<!-- ваші поля -->
	<input name="submit">
</form>
```
```html
<div class="foo">
	<!-- ваші поля -->
	<button name="submit">
</div>
```
## Конфігурація
Щоб інізіалізувати плагін зі своїми налаштуваннями необхідно передати у якості першого аргумента обєкт (літерал) параметрів.
Наприклад:

```javascript
var param = {
	validate: true
}
$(document).ready(function(){
    $("#form-box").mailer(param);
});
```

Або простіше:


```javascript
$(document).ready(function(){
    $("#form-box").mailer({
        validate: true
    });
});
```

### Параметри
Параметри, відмічені як (require) обовязкові до ініціалізації

| Параметр                                 | Тип           | Значення за замовчуванням  | Опис                                                          |
| ---------------------------------------- | ------------- | -------------------------- | ------------------------------------------------------------- |
| validation                               | boolean       | false                      | Вмикає перевірку даних полів                                  |
| showMessages                             | boolean       | true                       | Вмикає виведення повідомлень                                  |
| useCaptcha                               | boolean       | false                      | Вмикає захист від ботів (капчу)                               |
| errorClass                               | string        | 'error'                    | Клас для поля з невірними даними                              |
| requireClass                             | string        | 'require'                  | Клас для обовязкового поля                                    |
| validationAttr                           | string        | 'data-validate'            | Атрибут для типу даних поля                                   |
| templateName                             | string        | 'mail.tpl'                 | Імя файлу шаблона повідомлення                                |
| templateDir                              | string        | 'template/'                | Шлях до папки з шаблонами                                     |
| url                                      | string        | '../mail.php'              | Шлях до обробника та відправника email                        |
| messageSetting (require)                 | object        | object                     | Налаштування для відправки на email                           |
| messageSetting.subject                   | string        | 'mailer.js works!'         | Тема повідомлення                                             |
| messageSetting.mailFrom                  | object        | object                     | Налаштування відправника                                      |
| messageSetting.mailFrom.email            | string        | 'no-reply@[yourhostname]'  | Email відправника                                             |
| messageSetting.mailFrom.name             | string        | 'Mailer Js'                | Імя відправника                                               |
| messageSetting.mailTo                    | object        | object                     | Налаштування отримувача                                       |
| messageSetting.mailTo.email (require)    | string        | 'example@example.com'      | Email отримувача                                              |
| messageSetting.mailTo.name               | string        | 'First Name'               | Імя отримувача                                                |


## Типи данних для валідації
+ isPhone
+ isString
+ isDigit
+ isEmail