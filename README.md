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
$(document).ready(function(){
    $("#form-box").mailer({
        validate: true
    });
});
```

### Параметри

| Tables        | Are           | Cool  |
| ------------- |:-------------:| -----:|
| col 3 is      | right-aligned | $1600 |
| col 2 is      | centered      |   $12 |
| zebra stripes | are neat      |    $1 |


## Типи данних для валідації
+ isPhone
+ isString
+ isDigit
+ isEmail