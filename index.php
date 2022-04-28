<?php
  /**
   * Реализовать возможность входа с паролем и логином с использованием
   * сессии для изменения отправленных данных в предыдущей задаче,
   * пароль и логин генерируются автоматически при первоначальной отправке формы.
   */

  // Отправляем браузеру правильную кодировку,
  // файл index.php должен быть в кодировке UTF-8 без BOM.

  function generate($number)
  {
      $arr = array('a','b','c','d','e','f',
          'g','h','i','j','k','l',
          'm','n','o','p','r','s',
          't','u','v','x','y','z',
          'A','B','C','D','E','F',
          'G','H','I','J','K','L',
          'M','N','O','P','R','S',
          'T','U','V','X','Y','Z',
          '1','2','3','4','5','6',
          '7','8','9','0');
  // Генерируем пароль
      $pass = "";
      for($i = 0; $i < $number; $i++)
      {
  // Вычисляем случайный индекс массива
          $index = rand(0, count($arr) - 1);
          $pass .= $arr[$index];
      }
      return $pass;
  }
  header('Content-Type: text/html; charset=UTF-8');

  // В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
  // и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
      // Удаляем куку, указывая время устаревания в прошлом.
      setcookie('save', '', 100000);
      setcookie('login', '', 100000);
      setcookie('pass', '', 100000);
      // Выводим сообщение пользователю.
      $messages[] = 'Спасибо, результаты сохранены.';
      // Если в куках есть пароль, то выводим сообщение.
      if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass']));
      }
    }

    // Складываем признак ошибок в массив.
    $errors = array();
    $errors['fio'] = !empty($_COOKIE['fio_error']);
    $errors['e_mail'] = !empty($_COOKIE['e_mail_error']);
    $errors['year'] = !empty($_COOKIE['year_error']);
    $errors['biography'] = !empty($_COOKIE['biography_error']);
    // TODO: аналогично все поля.

    // Выдаем сообщения об ошибках.
    if (($errors['fio'])) {
      // Удаляем куку, указывая время устаревания в прошлом.
      setcookie('fio_error', '', 100000);
      // Выводим сообщение.
      //$messages[] = '<div class="error">Заполните имя.</div>';
    }
    if($errors['e_mail']){
      setcookie('e_mail_error', '', 100000);
      //$messages[] = '<div>Заполните почту корректно.</div>';
    }
    if($errors['year']){
      setcookie('year_error', '', 100000);
      //$messages[] = '<div>Заполните год корректно.</div>';
    }
    if($errors['biography']){
      setcookie('biography_error', '', 100000);
      //$messages[] = '<div>Заполните биографию.</div>';
    }
    // TODO: тут выдать сообщения об ошибках в других полях.

    // Складываем предыдущие значения полей в массив, если есть.
    // При этом санитизуем все данные для безопасного отображения в браузере.
    $user = 'u41057';
    $password = '1243534';
    $db = new PDO('mysql:host=localhost;dbname=u41057', $user, $password, array(PDO::ATTR_PERSISTENT => true));

    $values = array();
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
    $values['e_mail'] = empty($_COOKIE['e_mail_value']) ? '' : $_COOKIE['e_mail_value'];
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
    $values['super_skill'] = empty($_COOKIE['super_skill_value']) ? '' : $_COOKIE['super_skill_value'];
    $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
    // TODO: аналогично все поля.

    $flag = 0;
    foreach($errors as $err){ //Проверка ошибок
        if($err==1)$flag=1;
    }

    // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
    // ранее в сессию записан факт успешного логина.
    if (!$flag&&!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])) {
       // TODO: загрузить данные пользователя из БД
            $login = $_SESSION['login'];
            $stmt = $db->prepare("SELECT id FROM users WHERE login = '$login'");
            $stmt->execute();
            $user_id='';
            while($row = $stmt->fetch())
            {
                $user_id=$row['id'];
            }

            

            $request = "SELECT fio,e_mail,year,biography FROM form WHERE id = '$user_id'";
            
            $result = $db -> prepare($request);
            $result ->execute();
            
            $data = $result->fetch(PDO::FETCH_ASSOC);

            $values['fio'] = strip_tags($data['fio']);
            $values['e_mail'] = strip_tags($data['e_mail']);
            $values['year'] = strip_tags($data['year']);
            //$values['sex_value'] = strip_tags($data['gender']);
            //$values['limb_value'] = $data['limbs'];
            $values['biography'] = strip_tags($data['biography']);
            
            //$request = "SELECT  FROM abilities WHERE id = '$user_id'";



            $login_ses=$_SESSION['login'];
            $uid_ses=$_SESSION['uid'];

            
            // и заполнить переменную $values,
            // предварительно санитизовав.
            printf('<div style="color:yellow;">Вход с логином %s, uid %d</div>', $_SESSION['login'], $_SESSION['uid']);
          

          // Включаем содержимое файла form.php.
          // В нем будут доступны переменные $messages, $errors и $values для вывода 
          // сообщений, полей с ранее заполненными данными и признаками ошибок.
        }
        include('form.php');
   }
  // Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
  else {
    // Проверяем ошибки.
    $errors = FALSE;

    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
    setcookie('super_skill_value', $_POST['super_skill'], time() + 30 * 24 * 60 * 60);

    if (empty($_POST['fio'])) {
      // Выдаем куку на день с флажком об ошибке в поле fio.
      setcookie('fio_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
    else {
      // Сохраняем ранее введенное в форму значение на месяц.
      setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
    }
    
    if (empty($_POST['e_mail'])) {
      // Выдаем куку на день с флажком об ошибке в поле fio.
      setcookie('e_mail_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
    else {
      // Сохраняем ранее введенное в форму значение на месяц.
      setcookie('e_mail_value', $_POST['e_mail'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['year'])) {
      // Выдаем куку на день с флажком об ошибке в поле fio.
      setcookie('year_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
    else {
      // Сохраняем ранее введенное в форму значение на месяц.
      setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
    }

    if (empty($_POST['biography'])) {
      // Выдаем куку на день с флажком об ошибке в поле fio.
      setcookie('biography_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
    else {
      // Сохраняем ранее введенное в форму значение на месяц.
      setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
    }


    // *************
    // TODO: тут необходимо проверить правильность заполнения всех остальных полей.
    // Сохранить в Cookie признаки ошибок и значения полей.
    // *************

    if ($errors) {
      // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
      header('Location: index.php');
      exit();
    }
    else {
      $user = 'u41057';
      $password = '1243534';
      $db = new PDO('mysql:host=localhost;dbname=u41057', $user, $password);
      // Удаляем Cookies с признаками ошибок.
      setcookie('fio_error', '', 100000);
      setcookie('e_mail_error', '', 100000);
      setcookie('year_error', '', 100000);
      setcookie('biography_error', '', 100000);
      // TODO: тут необходимо удалить остальные Cookies.
    }

    // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])&&!empty($_SESSION['uid'])) {
        
        $fio = $_POST['fio'];
        $e_mail = $_POST['e_mail'];
        $year = $_POST['year'];
        $gender = $_POST['gender'];
        $limbs = $_POST['limbs'];
        $biography = $_POST['biography'];
        $god = $_POST['god'];
        $fly = $_POST['fly'];
        $fireball = $_POST['fireball'];
       

        $login = $_SESSION['login'];
        $stmt = $db->prepare("SELECT id FROM users WHERE login = '$login'");
        $stmt->execute();
        $user_id='';
        while($row = $stmt->fetch())
        {
            $user_id=$row['id'];
        }

        $sql = "UPDATE form SET fio='$fio',e_mail='$e_mail',year='$year',gender='$gender',limbs='$limbs',biography='$biography' WHERE id='$user_id'";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $sql = "UPDATE abilities SET god='$god',fly='$fly',fireball='$fireball' WHERE id='$user_id'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
          // TODO: перезаписать данные в БД новыми данными,
       // кроме логина и пароля.
      }
    else {
      // Генерируем уникальный логин и пароль.
      // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
      $login=generate(rand(1,25));

      $pass =generate(rand(1,25));

      $hash_pass=password_hash($pass, PASSWORD_DEFAULT);
      
      $user = 'u41057';
      $password = '1243534';
      $db = new PDO('mysql:host=localhost;dbname=u41057', $user, $password);
      
      // Сохраняем в Cookies.
      setcookie('login', $login);
      setcookie('pass', $pass);

      // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
      // ...

      $stmt = $db->prepare("INSERT INTO form (fio, year,e_mail,gender,limbs,biography) VALUES (:fio, :year,:e_mail,:gender,:limbs,:biography)");
      $stmt->execute(array('fio' => $_POST['fio'], 'year' => $_POST['year'], 'e_mail' => $_POST['e_mail'], 'gender' => $_POST['gender'], 'limbs' => $_POST['limbs'], 'biography' => $_POST['biography']));
      
      //$stmt = $db->prepare("INSERT INTO form (fio, year,e_mail,gender,limbs,biography) VALUES (:fio, :year,:e_mail,:gender,:limbs,:biography)");
      $stmt = $db->prepare("INSERT INTO abilities (god, fireball, fly) VALUES (:god, :fireball, :fly)");
      $stmt->execute(array('god' => $_POST['god'], 'fireball' => $_POST['fireball'], 'fly' => $_POST['fly']));
      
      $stmt = $db->prepare("INSERT INTO users (login, hash) VALUES (:login,:hash)");
      $stmt->bindParam(':login', $login);
      $stmt->bindParam(':hash', $hash_pass);
      $stmt->execute();
    }

    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');

    // Делаем перенаправление.
    header('Location: index.php');
  }
?>
