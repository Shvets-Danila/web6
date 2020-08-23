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
$dbuser = 'u15912';
$dbpass = '8355498';
$db = new PDO('mysql:host=localhost;dbname=u15912', $dbuser, $dbpass, array(PDO::ATTR_PERSISTENT => true));
session_start();
/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) || !isset($_SESSION['login']))
{
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  $_SESSION['login'] = "";
  exit();
}
$_SESSION['login'] = $_SERVER['PHP_AUTH_USER'];
$isAdmin = isAdmin($db, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
if(!$isAdmin)
{
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
if(isset($_GET['exit']))
{
  $_SESSION = array();
  unset($_COOKIE['session_name']);
  session_destroy();
  header("Location: admin.php");
}

if(isset($_GET['del']))
{
    if(!empty($_GET['del']) )
    {
        $id = intval($_GET['del']);
        if(is_numeric($id))deleteData($db, $_GET['del']);   
    }
}
print('<div class="row">');
print('<p class="text-left">Вход выполнен: '.$_SERVER['PHP_AUTH_USER'].'</p></div>');
print('<div class="row"><a class="text-left" href="?exit">Выйти</a></div>');
print('</div>');
print('<div class="row justify-content-md-center text-center">');
print('<h3 class="mt-4">Данные пользователей</h3>');
print('<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Логин</th>
      <th scope="col">Имя</th>
      <th scope="col">Год</th>
      <th scope="col">Email</th>
      <th scope="col">Пол</th>
      <th scope="col">Способности</th>
      <th scope="col">Кол-во кон.</th>
      <th scope="col">Биография</th>
      <th scope="col">Действие</th>
    </tr>
  </thead>
  <tbody>');
$data = getData($db);
foreach($data as $k=>$v)
{
    print('<tr>');
    foreach($v as $c=>$r){    
        $value = strip_tags($r);
        if($c=="gender")$value = getGender($r);
        if($c=="powers")$value=getPowers(unserialize($r));  
        print('<td>'.$value.'</td>');
    }
    print('<th><a href="?del='.$v['id'].'"><button class="btn btn-danger">Уд.</button></a></th>');
    print('</tr>');
}
print('</tbody></table></div>');

function getGender($g)
{
    $s = "";
    switch($g)
    {
        case 0:
            $s = "М";
            break;
        case 1:
            $s = "Ж";
            break;   
    }
    return $s;
}

function getPowers($p)
{
    $powers = array("1"=>"Бессмертие", "2"=>"Прохождение сквозь стены", "3"=>"Левитация");
    $s = array();
    foreach($p as $k)
    {
          if(array_key_exists($k, $powers))
          {
            $s[] = $powers[$k];        
          }  
    }
    return implode(',', $s);
}

function getData($db)
{
    $stmt = $db->prepare('SELECT id, login, name, year, email, gender, powers, bodyparts, bio FROM web5');
    $stmt->execute(array());
    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = array();
    if($fetch)$data = $fetch;
    return $data;
}

function isAdmin($db, $l, $p)
{
    $hash = md5($p);
    $stmt = $db->prepare('SELECT * FROM web6_admins WHERE login=? AND password=?');
    $stmt->execute(array($l, $hash));
    $user = $stmt->fetch();
    if($user)return true;
    else return false;
}

function deleteData($db, $id)
{
    $stmt = $db->prepare('DELETE FROM web5 WHERE id=?');
    $stmt->execute(array($id));
    header("Location: admin.php");
}
?>
</body>
</html>