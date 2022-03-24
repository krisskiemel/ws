<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link href='./style.css' rel='stylesheet' type='text/css'/>
	<link href='/images/favicon.ico' rel='shortcut icon' />
	<link rel='stylesheet' href='./library/bootstrap.min.css'>
	<script src='./library/jquery-3.1.1.min.js'></script>
	<script src='./library/jquery.chained.js'></script>
	<script src='./library/bootstrap.min.js'></script>
	<title>Zmiana hasła</title>	
</head>

<body class='ZS'>
<div class="container" style="width:600px;">

<?php
require 'ZWfunctions.php';
session_start();
$app = $_SESSION['app'];
$typ = $_REQUEST['typ'];

switch ($app) {
	case 'int':
		$urlapp = 'index.php';
		break;
	case 'zs':	
		$urlapp = 'ZSumowy.php';
		break;
	case 'zz':
		$urlapp = 'ZZzgloszenia.php';
		break;
	case 'zw':	
		$urlapp = 'ZWlistazlecen.php';
		break;
}

// Łączenie z bazą //
	$conn = polaczenie_z_baza ();

if (!$conn) {
	die('Wystąpił błąd podczas połączenia do MSSQL');}
else {
	//echo "    $app<br/>".$_SESSION['user']."<br/>".$_SESSION['pass'];
	if ($typ == '1' || $typ == '0') {
		$user = $_REQUEST['user'];
		switch ($app) {
			case 'int':
				$sql_form = "SELECT UserID ID, Nazwisko + ' ' + Imie Nazwa FROM INTUsers WHERE INTUserStatus = 'Aktywny' ORDER BY Nazwisko, Imie";
				break;		
			case 'zs':
				$sql_form = "SELECT Z.UserID ID, Nazwisko + ' ' + Imie Nazwa FROM ZSUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.UserID = '$user'";
				break;
			case 'zs_old':
				$sql_form = "SELECT Z.UserID ID, Nazwisko + ' ' + Imie Nazwa FROM ZSUsersOld Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.UserID = '$user'";
				break;
			case 'zw':
				$sql_form = "SELECT Z.UserID ID, Nazwisko + ' ' + Imie Nazwa FROM AWAUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.UserID = '$user'";
				break;
			case 'zz':
				$sql_form = "SELECT Z.UserID ID, Nazwisko + ' ' + Imie Nazwa FROM ZZUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.UserID = '$user'";
				break;
			}
		echo "    <div class='panel panel-warning'>\n";	
		if ($typ == '1') {
			echo "      <div class='panel-heading'>Zmiana hasła</div>\n";}
		else {
			echo "      <div class='panel-heading'>Wprowadź nowe hasło</div>\n";
		}
		echo "      <div class='panel-body'>\n";		
		echo "		  <form action='INTzmiana_hasla.php' method='post'>";
		echo "			<input type='hidden' name='typ' value='2'>";
		echo "          <div class='form-group row'>\n";
		echo "            <div class='col-xs-3'></div>\n";
		echo "            <div class='col-xs-6'>\n";
		echo "              <label for='user'>Login: </label>\n";
							form_combo_query($conn, $sql_form, "", "user", "required", $user, "", "");
		echo "            </div>\n";
		echo "          </div>\n";
		if ($typ == '1') {
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-3'></div>\n";
			echo "            <div class='col-xs-6'>\n";
			echo "              <label for='pass'>Stare hasło: </label>\n";
			echo "			    <input id='pass' name='pass' type='password' class='form-control input-sm' maxlength='15'>\n";
			echo "            </div>\n";
			echo "          </div>\n";
		}
		echo "          <div class='form-group row'>\n";
		echo "            <div class='col-xs-3'></div>\n";
		echo "            <div class='col-xs-6'>\n";
		echo "              <label for='pass'>Nowe hasło: </label>\n";
		echo "				<input id='newpass' name='newpass' type='password' class='form-control input-sm' required='required' maxlength='15'>\n";
		echo "            </div>\n";
		echo "          </div>\n";
		echo "          <div class='form-group row'>\n";
		echo "            <div class='col-xs-3'></div>\n";
		echo "            <div class='col-xs-6'>\n";
		echo "              <label for='pass'>Powtórz nowe hasło: </label>\n";
		echo "				<input id='newpass2' name='newpass2' type='password' class='form-control input-sm' required='required' maxlength='15'>";
		echo "            </div>\n";
		echo "          </div>\n";
		echo "          <div class='form-group row'>\n";
		if (typ == '1') {
			echo "            <div class='col-xs-4'></div>\n";
			echo "            <div class='col-xs-4'>\n";		
			echo "              <button type='submit' class='btn btn-default btn-block'>Zmień hasło</button>\n";
			echo "		        </form>";} // na koniec
		else {
			echo "            <div class='col-xs-2'></div>\n";
			echo "            <div class='col-xs-4'>\n";
			echo "              <button type='submit' class='btn btn-default btn-block'>Zmień hasło</button>\n";
			echo "		        </form>\n"; // na koniec
			echo "            </div>\n";
			echo "            <div class='col-xs-4'>\n";
			echo "              <form action='$urlapp'><button type='submit' class='btn btn-default btn-block'>Anuluj</button></form>\n";
		}
		echo "            </div>\n";		
		echo "          </div>\n";
}
	if ($typ == '2') {
		$user = $_REQUEST['user'];
		$pass = md5($_REQUEST['pass']);
		$newpass = md5($_REQUEST['newpass']);
		$newpass2 = md5($_REQUEST['newpass2']);

		echo "    <div class='panel panel-warning'>\n";	
		if ($typ == '1') {
			echo "      <div class='panel-heading'>Zmiana hasła</div>\n";}
		else {
			echo "      <div class='panel-heading'>Wprowadź nowe hasło</div>\n";
		}
		echo "      <div class='panel-body'>\n";
		
		$sql = "SELECT UserID, Haslo, Imie, Nazwisko FROM INTUsers WHERE UserID='$user'";
		$wynik = sqlsrv_query( $conn, $sql);
		$wiersz = sqlsrv_fetch_array($wynik, SQLSRV_FETCH_ASSOC);			
		if ($user == $wiersz['UserID'] && $pass == $wiersz['Haslo']) {
			if ($newpass == $newpass2) {
				$sql = "UPDATE INTUsers SET Haslo='$newpass' WHERE UserID='$user'";
				$wynik = sqlsrv_query( $conn, $sql);
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $newpass;				
				echo "Nastąpiła poprawna zmiana hasła.";
				header("Refresh: 1; url=$urlapp");}
			else {
				echo "Niepoprawne nowe hasło.";
				header("Refresh: 1; url=INTzmiana_hasla.php?typ=1&user=".$user);
			}}
		else {
			echo "Niepoprawne stare hasło.";
			header("Refresh: 1; url=INTzmiana_hasla.php?typ=1&user=".$user);
		}
		echo "		</div>";
		echo "	  </div>";		
	}	
}
echo "			</div>";
echo "		</div>";
?>