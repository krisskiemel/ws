<?php
	//die('Przerwa techniczna związana ze zmianą serwera bazy danych do 13:00');
    session_start();
	$_SESSION['app'] = "int";

	// Łączenie z bazą //
	$conn = polaczenie_z_baza ();
	
    if(isset($_SESSION['user']) && isset($_SESSION['pass']) && $_SESSION['pass'] != md5("")) {
		$user = $_SESSION['user'];
		$pass = $_SESSION['pass'];

		if (!$conn) {
			die('Wystąpił błąd podczas połączenia do MSSQL');
		} else {
			$sql = "SELECT UserID, Haslo, Imie, Nazwisko, IDOddzialu, Prawa FROM INTUsers WHERE UserID='$user'";
			$wynik = sqlsrv_query( $conn, $sql);
			$wiersz = sqlsrv_fetch_array($wynik, SQLSRV_FETCH_ASSOC);			
			if ($user == $wiersz['UserID'] && $pass == $wiersz['Haslo']) {
				$zalogowany = true;
				$nazwa_uzytkownika = $wiersz['Imie'].' '.$wiersz['Nazwisko'];
				$oddzial = $wiersz['IDOddzialu'];
				$prawa = $wiersz['Prawa'];
			} else {
				$zalogowany = false;
				$nazwa_uzytkownika = 'Niezalogowany';
				$oddzial = '';
				$prawa = '';				
			}
		}
	} else {
		unset($_SESSION["user"]);
		unset($_SESSION["pass"]);
		$zalogowany = false;
		$nazwa_uzytkownika = 'Niezalogowany';
		$oddzial = '';
		$prawa = '';
	}
	
?>