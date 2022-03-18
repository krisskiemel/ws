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
        <?php
        include "menu.php";
        if (!isset($_POST['typ'])) {
            $sql1 = "SELECT * FROM klienci WHERE status='Aktywny' ORDER BY nazwisko, imie";
            $result1 = $conn->query($sql1);
            $sql2 = "SELECT * FROM samochody WHERE status='Dostępny' ORDER BY marka, model";
            $result2 = $conn->query($sql2);
            ?>

            <div class="container-fluid">
            <form action="wypozycz.php" method="post">
                <input type="hidden" name="typ" value="update">
                <div class="mb-3 mt-3">
                    <label for="data_wyp" class="form-label">Data wypożyczenia:</label>
                    <input type="date" class="form-control" id="data_wyp" name="data_wyp" value="<?php echo date('Y-m-d');?>" required>
                </div>
                <div class="mb-3">
                    <label for="klient" class="form-label">Klient:</label>
                    <select class="form-select" id="klient" name="klient" required>
                        <option></option>
                    <?php
                    while($row = $result1->fetch_assoc()) {
                        echo "<option value='" . $row['id_klienta'] . "'>" . $row["imie"] . " " . $row["nazwisko"] . " " . $row["nr_dokumentu"] . "</option>\n"; 
                    }?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="samochod" class="form-label">Samochód:</label>
                    <select class="form-select" id="samochod" name="samochod" onchange="stanLicznika()" required>
                        <option></option>
                    <?php
                    while($row = $result2->fetch_assoc()) {
                        echo "<option value='" . $row['vin'] . "'>" . $row["marka"] . " " . $row["model"] . " " . $row["nr_rej"] . "</option>\n"; 
                    }?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="licznik1" class="form-label">Stan licznika przed wypożyczeniem:</label>
                    <input type="text" class="form-control" id="licznik1" name="licznik1" readonly required>
                </div>
                <button type="submit" class="btn btn-<?php echo $kolory?>">Zatwierdź</button>
            </form>

            </div>

            <script>
            function stanLicznika() {
                var liczniki = [
                <?php
                    $sql = "SELECT vin, stan_licznika stl FROM samochody";
                    $result = $conn->query($sql);
                    $tablica = "";
                    while($row = $result->fetch_assoc()) {
                        $tablica = $tablica."['" . $row['vin']. "', " . $row['stl']. "],";
                    }
                    echo substr($tablica, 0, -1)."]\n";
                ?>
                idSamochodu = document.getElementById('samochod').value
                for (var i = 0; i < liczniki.length; i++) {
                    if (liczniki[i][0]==idSamochodu) {
                        document.getElementById('licznik1').value = liczniki[i][1]
                        break
                    }
                }
            }
            </script>             
        <?php
        } else {
            header('Refresh:3 ; URL=wypozyczenie.php');
            $typ = $_POST['typ'];
            if ($typ == "update") {    
                $data_wyp = $_POST['data_wyp'];
                $klient = $_POST['klient'];
                $samochod = $_POST['samochod'];
                $licznik1 = $_POST['licznik1'];
                $sql = "INSERT INTO wypozyczenia (data_wyp, id_klienta, id_samochodu, stan_licz1)
                        VALUES ('$data_wyp', $klient, '$samochod', $licznik1)";
                $result = $conn->query($sql);
                $sql_s = "UPDATE samochody SET status='Wypożyczony' WHERE vin='$samochod'";
                $result_s = $conn->query($sql_s);
        ?>
            <div class="container-fluid">
                <p>Dodano nowe wypożyczenie:<br><br>
                    Data wypożyczenia: <?php echo $data_wyp;?>,<br> 
                    ID klienta: <?php echo $klient;?>,<br>
                    Nr VIN: <?php echo $samochod;?>,<br>
                    Stan licznika: <?php echo $licznik1;?>.
                </p>
                <a href="wypozyczenie.php"><button type="button" class="btn btn-<?php echo $kolory?>">Powrót</button></a>    
            </div>
        <?php
            }   
        }   
        ?>
    </body>

</html>