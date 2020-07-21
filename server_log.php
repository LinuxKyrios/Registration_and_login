<?php
session_start();

// Inicjalizacja zmiennych
$username = "";
$email    = "";
$errors = array(); 

// połączenie z bazą danych
$db = mysqli_connect('localhost', 'root', '', 'shop');

if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Nazwa użytkownika jest wymagana");
  }
  if (empty($password)) {
  	array_push($errors, "Hasło jest wymagane");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE u_login='$username' AND u_password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "Zalogowano pomyślnie";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Błędnie podano nazwę użytkownika lub hasło");
  	}
  }
}

?>