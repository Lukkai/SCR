<?php
  
  session_start();
  
    
  if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
  {
	  header('Location: index.php');
	  exit();
  }
  
  require_once "connect.php";
  
  // @ - jest to operator kontroli bledow, uzycie go przed jakims wyrazeniem
  // spowoduje nie wyswietlenie sie bledu lub ostrzezenia ze strony php
  $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
   
  if ($polaczenie->connect_errno!=0) //jesli polaczenie sie nie uda to pokaze blad i nie wykona zapytania
  {
	echo "Error: ".$polaczenie->connect_errno; //." Opis: ".$polaczenie->connect_error;
  } //wystarczy kod bledu do wygodnego rozwiazania problemu // lepiej sie nie dzielic takimi informacjami (np. nazwa usera z innymi
  else //jesli sie polaczy, to wykonuje zapytanie
  {
	    $login = $_POST['login'];
        $haslo = $_POST['haslo'];
		//echo $login."<br />";
		//echo $haslo;
		
		
		//htmlentities przemiela potencjalnie niebezpieczne 
		//znaki specjalne, ktore zamiast loginu moglyby wydawac polecenia i niechcianie 
		//sie logowac na zamienne znaki, ktore beda nieszkodliwe
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");  
		//$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
		
	    //$sql = "SELECT * FROM uzytkownicy WHERE 
		//user='%s' AND pass='%s'";
		//niebezpieczne uzycie baz danych, nieodporne na wstrzykiwanie danych i wycieki
		
		if($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
				//przynies dane ze skojarzonej tablicy, z kolumn bazy
				
				if (password_verify($haslo, $wiersz['pass']))
				{
					$_SESSION['zalogowany'] = true;					
					$_SESSION['id'] = $wiersz['id'];
					$_SESSION['user'] = $wiersz['user'];
					$_SESSION['drewno'] = $wiersz['drewno'];
					$_SESSION['kamien'] = $wiersz['kamien'];
					$_SESSION['zboze'] = $wiersz['zboze'];
					$_SESSION['email'] = $wiersz['email'];
					$_SESSION['dnipremium'] = $wiersz['dnipremium'];
					
					unset($_SESSION['blad']); //wywal z sesji blad, skoro go nie ma po logowaniu
					$rezultat->free_result();
					//lub free(); lub close();
					header('Location: gra.php');
				}
				else
				{
					$_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
					header('Location: index.php');
				}
			} 
			else
			{
				$_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
				header('Location: index.php');
			}
		}
		
		$polaczenie->close(); //zamyka polaczenie
  }
  
  
  
  
?>  