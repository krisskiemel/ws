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
	<title>Logowanie</title>	
</head>

<body class='ZS'>
<div class="container" style="width:600px;">

<?php
require 'ZWfunctions.php';
session_start();
$app = $_SESSION['app'];
//echo $app;
if (isset($_SESSION['link'])) {
	$link = $_SESSION['link'];
	echo "Link ustawiony:".$link;
} else {
	$_SESSION['link'] = $_SERVER['HTTP_REFERER'];
	$link = $_SERVER['HTTP_REFERER'];
	echo "Link nie ustawiony:".$link;
}	
//echo $_SESSION['link'];
if ($_REQUEST['typ'] != 'wyloguj') {

	// Łączenie z bazą //
	$conn = polaczenie_z_baza ();
	
	if (isset($_REQUEST['user']) && isset($_REQUEST['pass'])) {
		$user = $_REQUEST['user'];
		$pass0 = $_REQUEST['pass'];
		$pass = md5($pass0);
		$_SESSION['link'] = $_REQUEST['link'];
		if (!$conn) {
			die('Wystąpił błąd podczas połączenia do MSSQL');}
		else {
			$sql = "SELECT UserID, Haslo, Imie, Nazwisko FROM INTUsers WHERE UserID='$user'";
			//echo $sql;
			$wynik = sqlsrv_query( $conn, $sql);
			$wiersz = sqlsrv_fetch_array($wynik, SQLSRV_FETCH_ASSOC);
			echo "    <br/>";
			echo "    <div class='panel panel-warning'>\n";	
			echo "      <div class='panel-heading'>Logowanie</div>\n";
			echo "      <div class='panel-body'>\n";
			if ($user == $wiersz['UserID'] && $pass == $wiersz['Haslo']) {
				$_SESSION['user'] = $user;
				$_SESSION['pass'] = $pass;
				if ($pass0 == '') {
					header("Refresh: 0; url=INTzmiana_hasla.php?typ=0&user=$user");
				} else {
					echo "Poprawne logowanie";
					$_SESSION['wrongpass'] = false;
					header('Location: '.$_SESSION['link']);
				}	
			} else {
				echo "<p>Błędna nazwa użytkownika lub hasło</p>";
				$_SESSION['wrongpass'] = true;
				header("Refresh: 1; url=INTlogowanie.php");
			}
			echo "		</div>";
			echo "	  </div>";
		}
	} else {
		if (!$conn) {
			die('Wystąpił błąd podczas połączenia do MSSQL');
		} else {
			// Javascript
			echo "<script>\n";
			echo "    var daneUzytkownikow = new Array(";
			$sql = "SELECT UserID ID, NrKadrowy NR, Imie + ' ' + Nazwisko NAZ FROM INTUsers WHERE NrKadrowy <> '' AND INTUserStatus='Aktywny'";
			$wynik = sqlsrv_query($conn, $sql, array(), array( "Scrollable" => 'static' ));
			$ilosc = sqlsrv_num_rows($wynik);
			for ($y = 0; $y < $ilosc; $y++) {
				$wiersz = sqlsrv_fetch_array($wynik, SQLSRV_FETCH_ASSOC);
				echo "Array('".$wiersz['ID']."', '".$wiersz['NR']."', '".$wiersz['NAZ']."')";
				if ($y < $ilosc - 1) echo ', ';
			}
			echo ");\n";
			echo "  function wyszukajNazwisko() {\n";
			echo "    var znNazw = false;\n";
			echo "    for (i = 0; i < daneUzytkownikow.length; i++) {\n";
			echo "      if (daneUzytkownikow[i][0] == $('#user').val() || daneUzytkownikow[i][1] == $('#user').val()) {\n";
			echo "        $('#userid').val(daneUzytkownikow[i][0]);\n";
			echo "        $('#name').html(daneUzytkownikow[i][2]);\n";
			echo "        znNazw = true;\n";
			echo "        break;\n";
			echo "      }\n";
			echo "    }\n";
			echo "    if (!znNazw) {\n";
			echo "        $('#userid').val('');\n";
			echo "        $('#name').html('&nbsp;');\n";				
			echo "    }\n";
			echo "  }\n";
			echo "</script>\n";
			
			switch ($app) {
				case 'int':	
					$sql_form = "SELECT UserID ID, Nazwisko + ' ' + Imie Nazwa FROM INTUsers WHERE INTUserStatus = 'Aktywny' ORDER BY Nazwisko, Imie";
					break;
				case 'inw':	
					$sql_form = "SELECT UserID ID, Nazwisko + ' ' + Imie Nazwa FROM INTUsers WHERE INTUserStatus = 'Aktywny' ORDER BY Nazwisko, Imie";
					break;
				case 'pb':
					$sql_form = "SELECT P.UserID ID, I.Nazwisko + ' ' + I.Imie Nazwa FROM PBUsers Z JOIN INTUsers I ON I.UserID = P.UserID WHERE P.Status = 'Aktywny' ORDER BY I.Nazwisko, I.Imie";
					break;
				case 'zw':
					$sql_form = "SELECT Z.UserID ID, I.Nazwisko + ' ' + I.Imie Nazwa FROM AWAUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.Status = 'Aktywny' ORDER BY I.Nazwisko, I.Imie";
					break;
				case 'zs_old':
					$sql_form = "SELECT Z.UserID ID, I.Nazwisko + ' ' + I.Imie Nazwa FROM ZSUsersOld Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.ZSUserStatus = 'Aktywny' ORDER BY I.Nazwisko, I.Imie";
					break;
				case 'zs':
					$sql_form = "SELECT Z.UserID ID, I.Nazwisko + ' ' + I.Imie Nazwa FROM ZSUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.ZSUserStatus = 'Aktywny' ORDER BY I.Nazwisko, I.Imie";
					break;					
				case 'zz':
					$sql_form = "SELECT Z.UserID ID, I.Nazwisko + ' ' + I.Imie Nazwa FROM ZZUsers Z JOIN INTUsers I ON I.UserID = Z.UserID WHERE Z.ZZUserStatus = 'Aktywny' ORDER BY I.Nazwisko, I.Imie";
					break;	
			}
			echo "  <br/>\n";
			//echo "    $app<br/>".$_SESSION['user']."<br/>".$_SESSION['pass'];
			echo "  <div class='panel-group'>\n";		
			echo "    <div class='panel panel-warning'>\n";	
			echo "      <div class='panel-heading'>Logowanie</div>\n";
			echo "      <div class='panel-body'>\n";
			echo "        <form action='INTlogowanie.php' method='post'>\n";
			echo "          <input type='hidden' name='link' value='".$_SESSION['link']."'>\n";
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-2'></div>\n";
			echo "            <div class='col-xs-8'>\n";
			echo "              <label for='user'>Login: <small style='color:#a0a0a0'>wprowadź kod ABAS lub numer personalny</small></label>\n";
			echo "              <input id='user' type='text' class='form-control input-sm' size='20' maxlength='15' oninput='wyszukajNazwisko()' autocomplete='off'>\n";
			echo "              <input id='userid' name='user' type='hidden'>\n";
			echo "            </div>\n";
			echo "          </div>\n";
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-2'></div>\n";
			echo "            <div class='col-xs-8'>\n";
			echo "              <p id='name' style='font-size:120%;color:grey;text-align:center;'>&nbsp;</p>\n";
			echo "            </div>\n";
			echo "          </div>\n";
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-2'></div>\n";		
			echo "            <div class='col-xs-8'>\n";
			echo "              <label for='pass'>Hasło: <small style='color:#a0a0a0'>puste przy pierwszym logowaniu</small></label>\n";
			echo "              <input id='pass' name='pass' type='password' class='form-control input-sm' size='20' maxlength='15'>\n";
			echo "            </div>\n";		
			echo "          </div>\n";
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-4'></div>\n";
			echo "            <div class='col-xs-4'>\n";
			echo "              <button type='submit' class='btn btn-default btn-block'>Zaloguj</button>\n";
			echo "            </div>\n";
			echo "          </div>\n";
			echo "          <div class='form-group row'>\n";
			echo "            <div class='col-xs-12'>\n";
			echo "              <label ><small style='color:#a0a0a0'>Wskazówka: kod ABAS to 2 pierwsze litery imienia i 3 pierwsze litery nazwiska</small></label>\n";
			echo "            </div>\n";
			echo "          </div>\n";
			echo "        </form>\n";
			echo "      </div>\n";
			echo "    </div>\n";
			echo "  </div>\n";
		}
	}
}
else {
	switch ($app) {
		case 'int':
			$urlapp = 'index.php';
			break;
		case 'pb':
			$urlapp = 'PBprzegladybhp.php';
			break;			
		case 'zs':	
			$urlapp = 'ZSumowy.php';
			break;
		case 'zs_old':	
			$urlapp = 'arch/ZSumowy.php';
			break;			
		case 'zz':
			$urlapp = 'ZZzgloszenia.php';
			break;
		case 'zw':	
			$urlapp = 'ZWlistazlecen.php';
			break;
	}	
	session_destroy();
	header("Location: $urlapp");
}	
?>