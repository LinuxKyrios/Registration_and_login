<?php
session_start();

// Inicjalizacja zmiennych
$username = "";
$email    = "";
$errors = array(); 

// połączenie z bazą danych
$db = mysqli_connect('localhost', 'root', '', 'shop');

// Rejestracja użytkownika
if (isset($_POST['reg_user'])) {
  // otrzymuje wszystkie wartości wejściowe z formularza
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // walidacja formularza: upewnij się, że formularz jest prawidłowo wypełniony,
  // poprzez dodanie (array_push()) odpowiedniego błędu do tablicy $errors
  if (empty($username)) { array_push($errors, "Wymagana nazwa użytkownika"); }
  if (empty($email)) { array_push($errors, "Wymagany adres email"); }
  if (empty($password_1)) { array_push($errors, "Wymagane hasło"); }
  if ($password_1 != $password_2) {
	array_push($errors, "Hasła nie pasują do siebie");
  }

  // najpierw należy sprawdzić bazę danych, aby upewnić się, że użytkownik nie istnieje już z tą samą nazwą użytkownika i/lub adresem e-mail
  $user_check_query = "SELECT * FROM users WHERE u_login='$username' OR u_mail='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // jeśli użytkownik istnieje
    if ($user['username'] === $username) {
      array_push($errors, "Nazwa użytkownika jest już zajęta");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Istnieje już konto zarejestrowane na ten adres email");
    }
  }

  // Rejestracja użytkownika, jeżeli nie ma żadnych błędów w formularzu
  if (count($errors) == 0) {
  	$password = md5($password_1);//szyfrowanie hasła przed zapisem w bazie danych

  	$query = "INSERT INTO users (u_login, u_mail, u_password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}