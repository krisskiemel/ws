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

            $sql = "SELECT w.id_wyp, w.data_wyp, w.data_zwr, concat(k.imie, ' ',k.nazwisko) klient, CONCAT(s.marka, ' ', s.model, ' ', s.nr_rej) samochod
                    FROM wypozyczenia w, samochody s, klienci k
                    WHERE w.id_samochodu=s.vin AND w.id_klienta=k.id_klienta
                    ORDER BY w.data_wyp DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {?>
                <div class="container-fluid">
                    <br>
                    <div class="mb-3">
                        <a href="wypozycz.php"><button type="button" class="btn btn-<?php echo $kolory?>" >Nowe wypożyczenie</button></a>
                    </div>
                    <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data wyp.</th>
                        <th>Data zwr.</th>
                        <th>Klient</th>
                        <th>Samochód</th>
                        <th></th>
                    </tr>
                    </thead>
                <?php
                echo "<tbody>\n";
                while($row = $result->fetch_assoc()) {
                    if ($row['data_zwr'] == '0000-00-00') {
                        $data_zwr = "";
                        $button = "<td><a href='zwroc.php?id_wyp=" . $row["id_wyp"] . "'><button type='button' class='btn btn-$kolory'>Zwrot</button></a></td>";
                    } else{
                        $data_zwr = $row['data_zwr'];
                        $button = "<td></td>";
                    }
                    echo "<tr><td>" . $row["id_wyp"] . "</td><td>" . $row["data_wyp"] . "</td><td>" . $data_zwr . "</td><td>" . $row["klient"] . "</td><td>" . $row["samochod"] . "</td>$button</tr>\n";
                }
                echo "<tbody>\n";
                echo "</table>\n";
            } else {?>
                <div class="container-fluid">
                    <br>
                    <div class="mb-3">
                        <a href="wypozycz.php"><button type="button" class="btn btn-<?php echo $kolory?>" >Nowe wypożyczenie</button></a>
                    </div>
				</div>
			<?php	
            }
            $conn->close();
        ?>
            </div>
        </div>

    </body>

</html>