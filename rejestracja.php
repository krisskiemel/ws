<?php
    if (isset($_COOKIE['kolory'])) {
        $kolory = $_COOKIE['kolory'];
    } else {
        $kolory = "dark";
    }
?>
<?php include "db_conn.php";?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Wypożyczalnia samochodów - rejestracja</title>
    </head>    
        
    <body class="d-flex flex-column min-vh-100">
        <?php include "menu.php";
        if (!isset($_REQUEST['typ'])) {?>
            <div class="container-fluid">
                <form action="rejestracja.php" method="get">
                    <input type="hidden" name="typ" value="insert">
                    <div class="mb-3 mt-3">
                        <label for="nazwisko" class="form-label">Imię Nazwisko:</label>
                        <input type="text" class="form-control" id="nazwisko" name="nazwisko" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="login" class="form-label">Login:</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="mb-3 mt-3">
                            <label for="password" class="form-label">Hasło:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 mt-3">
                            <label for="password2" class="form-label">Powtórz hasło:</label>
                            <input type="password" class="form-control" id="password2" name="password2" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <button type="submit" class="btn btn-<?php echo $kolory?>">Zarejestruj</button>
                    </div>
                </form>
            </div>
        <?php
        } else {
            $nazwisko = $_REQUEST['nazwisko'];
            $login = $_REQUEST['login'];
            $password = md5($_REQUEST['password']);
            $password2 = md5($_REQUEST['password2']);
            if ($password == $password2) {
                $sql_log = "SELECT * FROM uzytkownicy WHERE login='$login'";
                $result_log = $conn->query($sql_log);
                if ($row_log = $result_log->fetch_assoc()) {
                    header('Refresh:3 ; URL=rejestracja.php');?>
                    <div class="container-fluid">
                        <div class="mb-3 mt-3">
                            <p>Login: <b><?php $login?></b> istnieje już w bazie użytkowników</p>
                        </div>
                    </div>
                <?php
                } else {
                    header('Refresh:3 ; URL=rejestracja.php');
                    $sql_ins = "INSERT INTO uzytkownicy (login, password, nazwisko)
                                VALUES ('$login', '$password', '$nazwisko')";
                    $result_ins = $conn->query($sql_ins);?>
                    <div class="container-fluid">
                        <div class="mb-3 mt-3">
                            <p>Dodano użytkownika: <b><?php echo $nazwisko?></b><br>Login: <b><?php echo $login?></b>.</p>
                        </div>
                    </div>
                <?php    
                }
            } else {
                $previous = "javascript:history.go(-1)";
                if(isset($_SERVER['HTTP_REFERER'])) {
                    $previous = $_SERVER['HTTP_REFERER'];
                }
                header('Refresh:3 ; URL=rejestracja.php');?>
                <div class="container-fluid">
                    <div class="mb-3 mt-3">
                        <p>Niepoprawnie powtórzone hasło.</p>
                    </div>
                </div>
            <?php        
            }
        }    
?>
        <footer class="mt-auto bg-<?php echo $kolory;?>">TEST</footer>
    </body>

</html>