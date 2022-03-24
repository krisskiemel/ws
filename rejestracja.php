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
        
    <body>
        <?php include "menu.php";
        if (!isset('typ')) {?>
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
                
            }

        }    

    </body>

</html>