<?php
  
  session_start();
  
  if(isset($_POST['email']))
  {
	//udana walidacja? Zakładaj, że tak!
	$wszystko_OK=true;
	
	//Sprawdź poprawność nickaname'a
	$nick=$_POST['nick'];
	
	//Sprawdzenie dlugości nicka
	if((strlen($nick)<3) || (strlen($nick)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków!";
	}			//e_nick = error nick
	
	if (ctype_alnum($nick)==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
	}
	
	//Sprawdź poprawność adresu e-mail
	
	$email=$_POST['email'];
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
	//emailB - email bezpieczny, po sanityzacji
	
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		$wszystko_OK=false;
		$_SESSION['e_email']="Podaj poprawny adres email!";
	}
	
	//Sprawdź poprawność hasła
	$haslo1 = $_POST['haslo1'];
	$haslo2 = $_POST['haslo2'];
	
    if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
    {
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
	}
	
	if ($haslo1!=$haslo2)
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
	}
	
	$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
	
	//Czy zaakceptowano regulamin?
	if (!isset($_POST['regulamin']))
	{
		$wszystko_OK=false;
		$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
	}
	
	//Bot or not, oto jest pytanie?
	$sekret = "6LcGlLUUAAAAAM-bWSR5uE74s5FjLy7tW4eQC96k";
	
	$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST[
	'g-recaptcha-response']);
	
	$odpowiedz = json_decode($sprawdz);
	
	if ($odpowiedz->success==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
	}
	
	//Zapamiętaj wprowadzone dane  fr=formularz_rejestracji
	$_SESSION['fr_nick'] = $nick;
	$_SESSION['fr_email'] = $email;
	$_SESSION['fr_haslo1'] = $haslo1;
	$_SESSION['fr_haslo2'] = $haslo2;
	if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	
	try
	{
	  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name); 
	  if ($polaczenie->connect_errno!=0) //jesli polaczenie sie nie uda to pokaze blad i nie wykona zapytania
	  {
		throw new Exception(mysqli_connect_errno());
	  }
	  else
	  {
		  //Czy email juz istnieje?
		  $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
		  
		  if(!$rezultat) throw new Exception($polaczenie->error);
		  
		  $ile_takich_maili = $rezultat->num_rows;
		  if ($ile_takich_maili>0)
		  {
			  $wszystsko_OK = false;
			  $_SESSION['e_email']="Istnieje już konto przypisane do tego adresu email!";
		  }
		  
		  //Czy login już istnieje?
		  $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
		  
		  if(!$rezultat) throw new Exception($polaczenie->error);
		  
		  $ile_takich_nickow = $rezultat->num_rows;
		  if ($ile_takich_nickow>0)
		  {
			  $wszystsko_OK = false;
			  $_SESSION['e_nick']="Istnieje już gracz o takim nicku, wybierz inny!";
		  }
		  
		  if ($wszystko_OK==true)
	{
		//Hurra, wzystkie testy zaliczone, dodajemy gracza do bazy
		if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, 14)"))
		{
			$_SESSION['udanarejestracja']=true;
			header('Location: witamy.php');
		}
		else
		{
			throw new Exception($polaczenie->error);
		}
	}
		  
		  $polaczenie->close();
	  }
	}
	catch(Exception $e)
	{
	  echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o ejestrację w innym terminie!</span>';
	  echo '<br />Informacja developerska: '.$e;
	}
	
	
	
  }
  
  
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Osadnicy - załóż darmowe konto</title>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
	.error
	{
		color:red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
  </style>
</head>

<body>

  <form method="post">
  
	Nickname: <br /><input type="text" value="<?php
		if(isset($_SESSION['fr_nick']))
		{
			echo $_SESSION['fr_nick'];
			unset($_SESSION['fr_nick']);
		}
	
	?>" name="nick" /><br />
	<?php
	
		if(isset($_SESSION['e_nick']))
		{
			echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
			unset($_SESSION['e_nick']);
		}
	?>
	
	E-mail: <br /><input type="text " value="<?php
		if(isset($_SESSION['fr_email']))
		{
			echo $_SESSION['fr_email'];
			unset($_SESSION['fr_email']);
		}
	
	?>" name="email" /><br />
	<?php
	
		if(isset($_SESSION['e_email']))
		{
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
	?>
	
	Twoje hasło: <br /><input type="password" value="<?php
		if(isset($_SESSION['fr_haslo1']))
		{
			echo $_SESSION['fr_haslo1'];
			unset($_SESSION['fr_haslo1']);
		}
	
	?>" name="haslo1" /><br />	
	<?php
	
		if(isset($_SESSION['e_haslo']))
		{
			echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
			unset($_SESSION['e_haslo']);
		}
	?>
	
	Powtórz hasło: <br /><input type="password" value="<?php
		if(isset($_SESSION['fr_haslo2']))
		{
			echo $_SESSION['fr_haslo2'];
			unset($_SESSION['fr_haslo2']);
		}
	
	?>" name="haslo2" /><br />	
	
	<label>
	<input type="checkbox" name="regulamin" <?php
		if(isset($_SESSION['fr_regulamin']))
		{
			echo "checked";
			unset($_SESSION['fr_regulamin']);
		}

		?>/> Akceptuję regulamin
	</label>
	<?php
	
		if(isset($_SESSION['e_regulamin']))
		{
			echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
			unset($_SESSION['e_regulamin']);
		}
	?>
	
	<div class="g-recaptcha" data-sitekey="6LcGlLUUAAAAAGYZa1qYCWV9s8apkf6_prgHifMc"></div>
	<?php
	
		if(isset($_SESSION['e_bot']))
		{
			echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
			unset($_SESSION['e_bot']);
		}
	?>
	
	<br />
	
	<input type="submit" value="Zarejestruj się" />
  </form>
  
</body>
</html>