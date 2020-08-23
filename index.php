<?php
header('Content-Type: text/html; charset=UTF-8');
$dbuser = 'u15912';
$dbpass = '8355498';
$db = new PDO('mysql:host=localhost;dbname=u15912', $dbuser, $dbpass, array(PDO::ATTR_PERSISTENT => true));
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <title>Форма</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"
        integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
</head>
<body class="container">

<?php
$ability_labels = [1 => 'Бессмертие', 3=> 'Левитация', 2 => 'Прохождение сквозь стены'];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = '<div class="row justify-content-md-center p-4">Спасибо, результаты сохранены.<br></div>';
        if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }
  
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['bodyparts'] = !empty($_COOKIE['bodyparts_error']);
  $errors['powers'] = !empty($_COOKIE['powers_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['agreed'] = !empty($_COOKIE['agreed_error']);
  
  if ($errors['name']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Имя может содержать только русские буквы.</div><br>';
  }
  if ($errors['email']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('email_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Неверно указан Email</div><br>';
  }
  if ($errors['year']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('year_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Неверно заполнен год.</div><br>';
  }
  if ($errors['gender']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('gender_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Укажите пол.</div><br>';
  }
  if ($errors['bodyparts']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('bodyparts_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Укажите число конечностей.</div><br>';
  }
  if ($errors['powers']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('powers_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Выберите способности.</div><br>';
  }
  if ($errors['bio']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('bio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Заполните биографию.</div><br>';
  }
  if ($errors['agreed']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('agreed_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="alert alert-danger" role="alert">Вы не согласились с контрактом.</div><br>';
  }
  
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['bodyparts'] = empty($_COOKIE['bodyparts_value']) ? '' : $_COOKIE['bodyparts_value'];
  $values['powers'] = empty($_COOKIE['powers_value']) ? '' : $_COOKIE['powers_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['agreed'] = empty($_COOKIE['agreed_value']) ? '' : $_COOKIE['agreed_value'];
    if (!empty($messages)) {
        print('<div class="row justify-content-md-center p-4">');
  // Выводим все сообщения.
       print('<div class="col-12 col-md-6 p-4">');
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
  print('</div>');
}

if (!in_array(true, $errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    $data = getData($db, $_SESSION['login'], $_SESSION['pass']);
    if($data)
    {
        $values = $data; 
        foreach($values as $k=>$v)
        {
            $values[$k] = strip_tags($v);
        }
    }
    echo '<div class="row justify-content-md-center p-2">';
    echo '<div class="alert alert-success" role="alert">';
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    echo '<br><a href="login.php?m=exit">Выйти</a></div></div>';
  }else{
        echo '<div class="row justify-content-md-center p-2">';
        echo '<a href="login.php" style="color: #fff; text-decoration:none;"><button type="button" class="btn btn-primary">Войти для изменения данных</button></a></div>';  
    }
  include('form.php');
}else{

$errors = array();

?>
<div class="row justify-content-md-center p-4">
<div class="col-12 col-md-6 jumbotron">
<?php
if (empty($_POST['name'])) {
  setcookie('name_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else if (!preg_match('/^[а-яА-Я ]+$/u', $_POST['name'])) {
  setcookie('name_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }
if (empty($_POST['bio'])){
    setcookie('bio_error', '1', time() + 24 * 60 * 60);

    $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
if (empty($_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);

    $errors = TRUE;
}
else if (!(is_numeric($_POST['year']) && intval($_POST['year']) >= 1900 && intval($_POST['year']) <= 2020)) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  }

$ability_data = array_keys($ability_labels);
if (empty($_POST['powers'])) {
    setcookie('powers_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
else{
  $abilities = $_POST['powers'];
  foreach ($abilities as $ability) {
    if (!in_array($ability, $ability_data)) {
      setcookie('powers_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
  }
  if(count($abilities)>0){
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('powers_value', serialize($_POST['powers']), time() + 30 * 24 * 60 * 60);
  }
  }
if(!isset($_POST['gender']))
{
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
else if(intval($_POST['gender'])<0 || intval($_POST['gender'])>1)
{
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
  }

if(empty($_POST['bodyparts']))
{
    setcookie('bodyparts_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
else if($_POST['bodyparts']<1 || $_POST['bodyparts']>4)
{
    setcookie('bodyparts_error', '1', time() + 24 * 60 * 60);
    $erros = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('bodyparts_value', $_POST['bodyparts'], time() + 30 * 24 * 60 * 60);
  }
if(empty($_POST['email'])){
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else if(!preg_match('/^.*\@.*\..+$/u', $_POST['email'])){
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
if(empty($_POST['agreed']))
{
    setcookie('agreed_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else if($_POST['agreed']!=="on"){
    setcookie('agreed_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('agreed_value', $_POST['agreed'], time() + 30 * 24 * 60 * 60);
  }
  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('bodyparts_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('powers_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
    setcookie('name_value', '', 1000000);
    setcookie('email_value', '', 1000000);
    setcookie('year_value', '', 1000000);
    setcookie('gender_value', '', 1000000);
    setcookie('bodyparts_value', '', 1000000);
    setcookie('powers_value', '', 1000000);
    setcookie('bio_value', '', 1000000);
    setcookie('agreed_value', '', 1000000);
  }
?>
</div>
</div>
<?php
  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
    updateData($db, $_SESSION['login'], $_SESSION['pass']);
  }
  else {
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = randomString(10);
    $pass = randomString(10);
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);
    insertData($db, $login, $pass);
    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    // ...
  }



  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}

function insertData($db, $l, $p)
{
$hash = md5($p);
try {
$stmt = $db->prepare("INSERT INTO web5 (login, password, name, year, powers, bio, gender, email, bodyparts) VALUES (?,?,?, ?, ?, ?, ?, ?, ?)");
$stmt->execute(array($l, $hash, $_POST['name'], intval($_POST['year']), serialize($_POST['powers']), $_POST['bio'], intval($_POST['gender']), $_POST['email'], intval($_POST['bodyparts'])));

}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
}
function updateData($db, $l, $p)
{
$hash = md5($p);
try {
$stmt = $db->prepare("UPDATE web5 SET name=?, year=?, powers=?, bio=?, gender=?, email=?, bodyparts=? WHERE login=? AND password=?");
$stmt->execute(array($_POST['name'], intval($_POST['year']), serialize($_POST['powers']), $_POST['bio'], intval($_POST['gender']), $_POST['email'], intval($_POST['bodyparts']), $l, $hash));
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}
}
function getData($db, $l, $p)
{
    $hash = md5($p);
    $stmt = $db->prepare("SELECT * FROM web5 WHERE login=? AND password=?");
    $stmt->execute(array($l, $hash));
    $user = $stmt->fetch();
    return $user;
}
function randomString($length)
{
    $data = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    $string = "";
    for($i=0;$i<$length;$i++)
    {
           $m = mt_rand(0,1);
           if($m==0)$string.=$data[mt_rand(0, count($data)-1)];
           else $string.=strval(mt_rand(0, 9));
    }
    return $string;
}
?>
</body>