<html>
  <head>
  <link rel="stylesheet" media="all" href="style.css"/>
  </head>
  <body>

  

<div class="container">
<?php
  if (!empty($_COOKIE['fio_error'])||!empty($_COOKIE['e_mail_error'])||!empty($_COOKIE['year_error'])||!empty($_COOKIE['biography_error'])) {
    print '<div style="text-align:center;"><a style="color:red;margin:0 auto;">&#10065;</a><a>Заполните обязательные поля</a></div>';
  }
  else{
    print('<div id="messages">');
  // Выводим все сообщения.
    foreach ($messages as $message) {
        print($message);
    }
    print('</div>');
  }

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>  
    <h2><strong>Форма</strong></h2>
    
    <a id="form"></a>
    <form action="index.php" method="POST">
        
        <label>
            Имя:<br/>
            <input name="fio" 
            <?php if ($errors['fio']) {print 'class="error"';} ?>  type="text"
            value="<?php print $values['fio']; ?>" />
        </label><br/><br/>

        <label>
            Email:<br/>
            <input name="e_mail" 
            <?php if ($errors['e_mail']) {print 'class="error"';} ?>
            value="<?php print $values['e_mail']; ?>" type="email" />
        </label><br/><br/>

        <label>
            Дата рождения:<br/>
            <input name="year" 
            <?php if ($errors['year']) {print 'class="error"';} ?>
            value="<?php print $values['year']; ?>" type="date" />
        </label><br/><br/>

        Пол:<br/>
        <label><input type="radio" checked="checked"
            name="gender" value="m" />
            Муж</label>
        <label><input type="radio"
            name="gender" value="f" />
            Жен</label><br/><br/>

        Количество конечностей:<br/>
        <label><input type="radio" checked="checked"
            name="limbs" value="4" />
            4</label>
        <label><input type="radio" checked="checked"
            name="limbs" value="3" />
            3</label>
        <label><input type="radio" checked="checked"
            name="limbs" value="2" />
            2</label>
        <label><input type="radio" checked="checked"
            name="limbs" value="1" />
            1</label>
        <label><input type="radio"
            name="limbs" value="0" />
            0</label><br/><br/>

        <label>
            Сверхспособности:
            <br/>
            <select name="super_skill"
            multiple="multiple">
            <option value="god">Бессмертие</option>
            <option value="fireball" selected="selected">Огненные шары</option>
            <option value="fly" selected="selected">Полет</option>
            </select>
        </label><br/><br/>

        <label>
            Биография:<br/>
            <textarea name="biography"
            <?php if ($errors['biography']) {print 'class="error"';} ?>
            ><?php print $values['biography']; ?></textarea>
        </label><br/><br/>

        <br/>
        <label><input type="checkbox" checked="checked"
            name="check-1" />
            С контрактом ознакомлен</label><br/><br/>

        <input type="submit" value="Отправить" />

        

        </form>
        <a href="login.php">Войти</a>
    </div>
  </body>
</html>
