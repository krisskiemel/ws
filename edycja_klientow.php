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
        <title>Wypożyczalnia samochodów</title>
    </head>    
        
    <body>
        <?php include "menu.php";
        $typ = $_REQUEST['typ'];
        switch ($typ) {
            case "nowy":
        ?>
                <div class="container-fluid">
                    <form action="edycja_klientow.php" method="post">
                        <input type="hidden" name="typ" value="insert">
                        <div class="mb-3 mt-3">
                            <label for="imie" class="form-label">Imie:</label>
                            <input type="text" class="form-control" id="imie" name="imie" pattern="[A-ZĆŁŃÓŚŹŻ][a-ząćęłńóśźż]+" maxlength="40" required>
                        </div>
                        <div class="mb-3">
                            <label for="nazwisko" class="form-label">Nazwisko:</label>
                            <input type="text" class="form-control" id="nazwisko" name="nazwisko" pattern="[A-ZĆŁŃÓŚŹŻa-ząćęłńóśźż\-]+" maxlength="40" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokument" class="form-label">Dokument:</label>
                            <input type="text" class="form-control" id="dokument" name="dokument" pattern="[A-Z0-9 ]{9,15}" required>
                        </div>
                        <button type="submit" class="btn btn-<?php echo $kolory?>">Zatwierdź</button>
                    </form>
                </div>
        <?php
                break;
            case "insert":
                header('Refresh:3 ; URL=klienci.php'); 
                $imie = $_POST['imie'];
                $nazwisko = $_POST['nazwisko'];
                $dokument = $_POST['dokument'];
                $sql = "INSERT INTO klienci (imie, nazwisko, nr_dokumentu, status)
                        VALUES ('$imie', '$nazwisko', '$dokument', 'Aktywny')";
                $result = $conn->query($sql);
                $sql_id = "SELECT MAX(id_klienta) id FROM klienci";
                $result_id = $conn->query($sql_id);
                $row_id = $result_id->fetch_assoc();
            ?>
                <div class="container-fluid">
                    <p>Dodano nowego klienta:<br><br>
                        ID klienta: <b><?php echo $row_id['id'];?>,</b><br> 
                        Imię: <b><?php echo $imie;?>,</b><br>
                        Nazwisko: <b><?php echo $nazwisko;?>,</b><br>
                        Numer dokumentu: <b><?php echo $dokument;?>.</b>
                    </p>   
                    <a href="klienci.php"><button type="button" class="btn btn-dark">Powrót</button></a>  
                </div>
        <?php
                break;
            case "edycja":
                $id_klienta = $_POST['id_klienta'];
                $sql = "SELECT imie, nazwisko, nr_dokumentu, status FROM klienci WHERE id_klienta=$id_klienta";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $status = $row['status'];
        ?>
                <div class="container-fluid">
                    <form action="edycja_klientow.php" method="post">
                        <input type="hidden" name="typ" value="update">
                        <div class="mb-3 mt-3">
                            <label for="id_klienta" class="form-label">ID klienta:</label>
                            <input type="text" class="form-control" id="id_klienta" name="id_klienta" value="<?php echo $id_klienta;?>" readonly>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="imie" class="form-label">Imie:</label>
                            <input type="text" class="form-control" id="imie" name="imie" value="<?php echo $row['imie'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="nazwisko" class="form-label">Nazwisko:</label>
                            <input type="text" class="form-control" id="nazwisko" name="nazwisko" value="<?php echo $row['nazwisko'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="dokument" class="form-label">Dokument:</label>
                            <input type="text" class="form-control" id="dokument" name="dokument" value="<?php echo $row['nr_dokumentu'];?>">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status:</label>
                            <select  class="form-select" id="status" name="status">
                                <option value="Aktywny"<?php if ($status=="Aktywny") {echo " selected";}?>>Aktywny</option>
                                <option value="Nieaktywny"<?php if ($status=="Nieaktywny") {echo " selected";}?>>Nieaktywny</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">Zatwierdź</button>
                    </form>
                </div>
        <?php
                break;
            case "update": 
                header('Refresh:3 ; URL=klienci.php');
                $id_klienta = $_POST['id_klienta'];
                $imie = $_POST['imie'];
                $nazwisko = $_POST['nazwisko'];
                $dokument = $_POST['dokument'];
                $status = $_POST['status'];
                $sql = "UPDATE klienci SET imie = '$imie', nazwisko = '$nazwisko', nr_dokumentu = '$dokument', status = '$status' WHERE id_klienta = $id_klienta";
                $result = $conn->query($sql);
        ?>
                <div class="container-fluid">
                    <p>Zaktualizowano dane klienta:<br><br>
                        ID klienta: <b><?php echo $id_klienta;?>,</b><br> 
                        Imię: <b><?php echo $imie;?>,</b><br>
                        Nazwisko: <b><?php echo $nazwisko;?>,</b><br>
                        Numer dokumentu: <b><?php echo $dokument;?>.</b><br>
                        Status: <b><?php echo $status;?>.</b>
                    </p>
                    <a href="klienci.php"><button type="button" class="btn btn-dark">Powrót</button></a>
                </div>
        <?php
                break;
            case "delete":
                header('Refresh:3 ; URL=klienci.php');  
                $id_klienta = $_POST['id_klienta'];
                $sql = "UPDATE klienci SET status = 'Nieaktywny' WHERE id_klienta = $id_klienta";
                $result = $conn->query($sql);
                $sql_kl = "SELECT imie, nazwisko, nr_dokumentu FROM klienci WHERE id_klienta=$id_klienta";
                $result_kl = $conn->query($sql_kl);
                $row_kl = $result_kl->fetch_assoc();
        ?>
                <div class="container-fluid">
                    <p>Zmieniono status klienta na <b>Nieaktywny</b>:<br><br>
                        ID klienta: <b><?php echo $id_klienta;?>,</b><br> 
                        Imię: <b><?php echo $row_kl['imie'];?>,</b><br>
                        Nazwisko: <b><?php echo $row_kl['nazwisko'];?>,</b><br>
                        Numer dokumentu: <b><?php echo $row_kl['nr_dokumentu'];?>.</b>
                    </p>
                    <a href="klienci.php"><button type="button" class="btn btn-dark">Powrót</button></a>                       
                </div>
            <?php
                break;
        }?>    

    </body>

</html>