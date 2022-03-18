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
        <title>Wypożyczalnia samochodów - klienci</title>
    </head>    
        
    <body>
        <?php
            include "menu.php";

            $sql = "SELECT id_klienta, imie, nazwisko, nr_dokumentu, status FROM klienci ORDER BY nazwisko";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {?>
                <div class="container-fluid">
                    <br>
                    <div class="mb-3">
                        <form action="edycja_klientow.php" method="post">
                            <input type="hidden" name="typ" value="nowy">
                            <button type="submit" class="btn btn-<?php echo $kolory?>">Dodaj klienta</button>
                        </form>
                    </div>
                    <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID klienta</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Numer dokumentu</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                <?php
                echo "<tbody>\n";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id_klienta"] . "</td><td>" . $row["imie"] . "</td><td>" . $row["nazwisko"] . "</td><td>" . $row["nr_dokumentu"] . "</td><td>" . $row["status"] . "</td>";
                    echo "<td><form action='edycja_klientow.php' method='post' style='display:inline;'><input type='hidden' name='typ' value='edycja'><input type='hidden' name='id_klienta' value='" . $row["id_klienta"] . "'><button type='submit' class='btn btn-$kolory'>Edycja</button></form>\n";
                    echo "<form action='edycja_klientow.php' method='post' style='display:inline;'><input type='hidden' name='typ' value='delete'><input type='hidden' name='id_klienta' value='" . $row["id_klienta"] . "'><button type='submit' class='btn btn-$kolory'>Usuń</button></form></td></tr>\n";
                }
                echo "<tbody>\n";
                echo "</table>\n";
            } else {
                echo "Brak wyników";
            }
            $conn->close();
        ?>
            </div>
        </div>

    </body>

</html>