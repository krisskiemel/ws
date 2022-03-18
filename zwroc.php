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
        if (!isset($_REQUEST['typ'])) {
            if (isset($_REQUEST['id_wyp'])) {
                $id_wyp = $_REQUEST['id_wyp'];
                $sql = "SELECT w.id_wyp, w.data_wyp, w.data_zwr, CONCAT(k.imie, ' ', k.nazwisko, ' ', k.nr_dokumentu) klient,
                        CONCAT(s.marka, ' ', s.model, ' ', s.nr_rej) samochod, s.vin, s.cena_dz, s.cena_km, w.stan_licz1,
                        w.stan_licz2, w.do_zaplaty
                        FROM wypozyczenia w, klienci k, samochody s
                        WHERE k.id_klienta = w.id_klienta AND s.vin = w.id_samochodu AND id_wyp = $id_wyp";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $data_wyp = new DateTime( $row['data_wyp'] );
                $data_wyp->modify( '+1 day' );
                ?>

                <div class="container-fluid">
                <form action="zwroc.php">
                    <input type="hidden" name="typ" value="update">
                    <input type="hidden" name="vin" value="<?php echo $row['vin'];?>">
                    <div class="mb-3 mt-3">
                        <label for="id_wyp" class="form-label">ID wypożyczenia:</label>
                        <input type="text" class="form-control" id="id_wyp" name="id_wyp" value="<?php echo $row['id_wyp'];?>" readonly>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="data_wyp" class="form-label">Data wypożyczenia:</label>
                        <input type="text" class="form-control" id="data_wyp" name="data_wyp" value="<?php echo $row['data_wyp'];?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="data_zwr" class="form-label">Data zwrotu:</label>
                        <input type="date" class="form-control" id="data_zwr" name="data_zwr" min="<?php echo $data_wyp->format( 'Y-m-d' );?>" value="<?php echo date('Y-m-d');?>" onchange="doZaplaty()" required>
                    </div>
                    <div class="mb-3">
                        <label for="klient" class="form-label">Klient:</label>
                        <input type="text" class="form-control" id="klient" name="klient" value="<?php echo $row['klient'];?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="samochod" class="form-label">Samochód:</label>
                        <input type="text" class="form-control" id="samochod" name="samochod" value="<?php echo $row['samochod'];?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="licznik1" class="form-label">Stan licznika przed wypożyczeniem:</label>
                        <input type="text" class="form-control" id="licznik1" name="licznik1" value="<?php echo $row['stan_licz1'];?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="licznik2" class="form-label">Stan licznika przy zwrocie:</label>
                        <input type="number" class="form-control" id="licznik2" name="licznik2" min="<?php echo $row['stan_licz1'];?>" max="<?php echo $row['stan_licz1'] + 100000;?>" value="<?php echo $row['stan_licz1'];?>" onchange="doZaplaty()" required>
                    </div>
                    <div class="mb-3">
                        <label for="zaplata" class="form-label">Do zapłaty:</label>
                        <input type="text" class="form-control" id="zaplata" name="zaplata" readonly required>
                    </div>
                    <button type="submit" class="btn btn-<?php echo $kolory?>">Zatwierdź</button>
                </form>

                </div>

                <script>
                function doZaplaty() {
                    licznik1 = document.getElementById('licznik1').value
                    licznik2 = document.getElementById('licznik2').value
                    var data1 = new Date(document.getElementById('data_wyp').value)
                    var data2 = new Date(document.getElementById('data_zwr').value)
                    cena_km = <?php echo $row['cena_km']."\n";?>
                    cena_dz = <?php echo $row['cena_dz']."\n"?>
                    zaplata = (licznik2 - licznik1) * cena_km + (data2-data1) / 86400000 * cena_dz
                    document.getElementById('zaplata').value = zaplata
                }
                </script>
            <?php    
            } else {?>
                <div class="container-fluid">
                    <p>Nie podano właściwego ID wypożyczenia.</p> 
                </div>
            <?php    
            }                 
        } else {
            $typ = $_REQUEST['typ'];
            if ($typ == "update") {
                header('Refresh:3 ; URL=wypozyczenie.php');
                $id_wyp = $_REQUEST['id_wyp'];
                $data_wyp = $_REQUEST['data_wyp'];
                $data_zwr = $_REQUEST['data_zwr'];
                $klient = $_REQUEST['klient'];
                $samochod = $_REQUEST['samochod'];
                $vin = $_REQUEST['vin'];
                $licznik1 = $_REQUEST['licznik1'];
                $licznik2 = $_REQUEST['licznik2'];
                $zaplata = $_REQUEST['zaplata'];
                $sql = "UPDATE wypozyczenia
                        SET data_zwr = '$data_zwr', stan_licz2 = $licznik2, do_zaplaty = $zaplata
                        WHERE id_wyp = $id_wyp";        
                $result = $conn->query($sql);
                $sql_s = "UPDATE samochody SET status='Dostępny' WHERE vin='$vin'";
                $result_s = $conn->query($sql_s);
        ?>
            <div class="container-fluid">
                <p><br>Dokonano zwrotu samochodu:<br><br>
                    Data wypożyczenia: <b><?php echo $data_wyp;?></b>,<br>
                    Data zwrotu: <b><?php echo $data_zwr;?></b>,<br>
                    ID klienta: <b><?php echo $klient;?></b>,<br>
                    Nr VIN: <b><?php echo $samochod;?></b>,<br>
                    Stan licznika przed: <b><?php echo $licznik1;?></b>,<br>
                    Stan licznika po: <b><?php echo $licznik2;?></b>,<br>
                    Do zapłaty: <b><?php echo $zaplata;?></b>.
                </p>
                <a href="wypozyczenie.php"><button type="button" class="btn btn-<?php echo $kolory?>">Powrót</button></a>    
            </div>
        <?php
            }   
        }   
        ?>
    </body>

</html>