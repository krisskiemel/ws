<?php
    session_start();
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
        <title>Wypożyczalnia samochodów - logowanie</title>
    </head>    
        
    <body>
        <?php
        if (!isset($_REQUEST['typ'])) {?>
            <div class="container-fluid">
                <form action="logowanie.php" method="get">
                    <input type="hidden" name="typ" value="login">
                    <div class="mb-3 mt-3">
                        <label for="login" class="form-label">Login:</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="mb-3 mt-3">
                            <label for="password" class="form-label">Hasło:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <button type="submit" class="btn btn-<?php echo $kolory?>">Zaloguj</button>
                    </div>
                </form>
            </div>
        <?php
        } else {
            $login = $_REQUEST['login'];
            $password = md5($_REQUEST['password']);
            $sql_log = "SELECT password FROM uzytkownicy WHERE login='$login'";
            $result_log = $conn->query($sql_log);
            if ($row_log = $result_log->fetch_assoc()) {
                if ($password == $row_log['password']) {
                    header('Refresh:3 ; URL=index.php');
                    $_SESSION['user'] = $login;
				    $_SESSION['pass'] = $password;
                    echo "<p>Zalogowano</p>";
                } else {
                    unset($_SESSION["user"]);
                    unset($_SESSION["pass"]);
                    echo "<p>Nieprawidłowe hasło: $password " . $row_log['password'] . "</p>";
                }
            } else {
                unset($_SESSION["user"]);
                unset($_SESSION["pass"]);
                echo "<p>Nieprawidłowy login</p>";
            }
        }    
?>
    </body>

</html>