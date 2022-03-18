<?php
    if (isset($_REQUEST['kolory'])) {
        $kolory = $_REQUEST['kolory'];
        setcookie('kolory', $kolory, time() + (86400 * 30), "/");
    } else {
        if (isset($_COOKIE['kolory'])) {
            $kolory = $_COOKIE['kolory'];
        } else {
            $kolory = "dark";
        }
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
        <title>Wypożyczalnia samochodów</title>
    </head>    
        
    <body>
        <?php include "menu.php";?>
        <div class="container-fluid">
            <form action="ustawienia.php" method="get">
                <div class="mb-3 mt-3">
                    <label for="kolory" class="form-label">Kolorystyka strony:</label>
                    <select class="form-select" id="kolory" name="kolory" required>
                        <option></option>
                        <option class="text-white bg-dark" value="dark"<?php if ($kolory == 'dark') {echo ' selected';}?>>Czarny</option>
                        <option class="text-white bg-primary" value="primary"<?php if ($kolory == 'primary') {echo ' selected';}?>>Niebieski</option>
                        <option class="text-white bg-secondary" value="secondary<?php if ($kolory == 'secondary') {echo ' selected';}?>">Szary</option>
                        <option class="text-white bg-success" value="success"<?php if ($kolory == 'success') {echo ' selected';}?>>Zielony</option>
                        <option class="text-white bg-danger" value="danger"<?php if ($kolory == 'danger') {echo ' selected';}?>>Czerwony</option>
                        <option class="text-white bg-warning" value="warning"<?php if ($kolory == 'warning') {echo ' selected';}?>>Żółty</option>
                    </select>
                </div>
                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-<?php echo $kolory?>">Zapisz ustawienia</button>
                </div>
            </form>
        </div>

    </body>

</html>