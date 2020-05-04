<?php
  
  session_start();
  
  if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
  {
	  header('Location: gra.php');
	  exit();
  }
  
  //usuwanie zmiennych pamietajacych wartosci wpisane do formularza
  if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
  if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
  if (isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
  if (isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
  if (isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);
  
  //usuwanie błędów rejestracji
  if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
  if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
  if (isset($_SESSION['e_haslo'])) unset($_SESSION['e_haslo']);
  if (isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
  if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
  
  
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>

  Tylko martwi ujrzeli koniec wojny - Planto <br /><br />
  
  <a href="rejestracja.php">Rejestracja - załóż darmowe konto!</a>
  
  <form action="zaloguj.php" method="post">
  
    Login: <br /><input type="text" name="login" /> <br />
	Hasło: <br /><input type="password" name="haslo" /> <br /><br />
	<input type="submit" value="Zaloguj się" />

  </form>
  
<?php
  if (isset($_SESSION['blad'])) //jesli jest blad, to wyswietl blad
	  echo $_SESSION['blad'];
?>


</body>
</html>