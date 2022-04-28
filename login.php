<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  print("<form action='' method='POST'>
  <label>Вы уже авторизованы под логином({$_SESSION['login']})<label>
  <input type='hidden' name='destroySession' value='1'>
  <input type='submit' value='Выйти' />
  <a href='index.php'>Назад к форме</a>
  </form>");
  $destroySessionFlag = 0;
  $destroySessionFlag = filter_input(INPUT_POST, 'destroySession');
  if ($destroySessionFlag == 1) {
    session_destroy();
    header('Location: index.php');
  }
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
}
else{
  print(' <form action="" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
  <a href="index.php">Назад к форме</a>
  </form>');
  }

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  ?>

  <!--<form action="" method="post">
    <input name="login" />
    <input name="pass" />
    <input type="submit" value="Войти" />
  </form>-->

  <?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  $user = 'u41057';
  $pass = '1243534';
  $db = new PDO('mysql:host=localhost;dbname=u41057', $user, $pass);
  $login = $_POST['login'];
  $hash_pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

  $sql = "SELECT EXISTS (SELECT * FROM 'users' WHERE login = '{$login}' and pass ='{$hash_pass}' )";

  $stmt = $db->prepare($sql);
  $stmt->execute();

  if($stmt) {
      // Если все ок, то авторизуем пользователя.
      $_SESSION['login'] = $_POST['login'];
      // Записываем ID пользователя.
      $_SESSION['uid'] = rand(1, 25);
      // Делаем перенаправление.
      header('Location: index.php');
  }
  else{
      header('Location: login.php');
  }
}
