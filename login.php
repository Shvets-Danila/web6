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
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('Location: ./');
}
if(!empty($_COOKIE['auth_error']))
{
    echo '<div class="row justify-content-md-center p-2"><div class="alert alert-danger" role="alert">Неправильный логин или пароль</div></div>';
    setcookie('auth_error', '', 100000); 
}
if(isset($_GET['m']))
{
    if($_GET['m']=='exit')
    {
            setcookie('login', '', 100000);
            setcookie('pass', '', 100000);   
            session_destroy();
    }
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<div class="row justify-content-md-center p-2">
<form action="" method="post" class="col-12 col-md-6 align-self-center jumbotron p-4">
 <h1 class="text-center">Вход</h1>
    <div class="form-group">
  <input name="login" class="form-control" placeholder="Логин"/>
  <br>
  <input name="pass" class="form-control" placeholder="Пароль"/>
  <br>
  <input type="submit" value="Войти" class="form-control"/>
  
<div class="row justify-content-md-center p-2">
<a href="index.php" style="color: #fff; text-decoration:none;"><button type="button" class="btn btn-primary">Обратно к форме</button></a></div>
  </div>
</form>
</div>
<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {

  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
$dbuser = 'u15912';
$dbpass = '8355498';
$db = new PDO('mysql:host=localhost;dbname=u15912', $dbuser, $dbpass, array(PDO::ATTR_PERSISTENT => true));
$hash = md5($_POST['pass']);
$stmt = $db->prepare("SELECT id FROM web5 WHERE login=? AND password=?");
$stmt->execute(array($_POST['login'], $hash));
$user = $stmt->fetch();
if($user){
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = $user['id'];
  $_SESSION['pass'] = $_POST['pass'];
  // Делаем перенаправление.
  header('Location: ./');
  }else{
         setcookie('auth_error', '1', time()+86400);
         header('Location: login.php');
    }
}
?>
</body>