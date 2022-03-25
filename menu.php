<?php
session_start();
if(isset($_SESSION['user']) && isset($_SESSION['pass'])) {
    $zalogowany = true;
} else {
    $zalogowany = false;
}
?>
        <nav class="navbar navbar-expand-sm navbar-dark bg-<?php echo $kolory?>">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    Home
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="klienci.php">Zarządzanie klientami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="samochody.php">Zarządzanie samochodami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="wypozyczenie.php">Wypożyczenie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ustawienia.php">Ustawienia</a>
                        </li>
                    </ul>
                    <?php
                    if (!$zalogowany) {?>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-link" href="rejestracja.php"></span> Zarejestruj się</a></li>
                            <li><a class="nav-link" href="logowanie.php"></span> Zaloguj</a></li>
                        </ul>
                    <?php    
                    } else {?>
                        <ul class="nav navbar-nav navbar-right">
                            <li>Zalogowany</li>
                        </ul>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </nav>