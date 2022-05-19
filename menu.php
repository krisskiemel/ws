<?php
session_start();
if(isset($_SESSION['user']) && isset($_SESSION['pass'])) {
    $zalogowany = true;
    $nazwisko = $_SESSION["name"];
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
                            <li>
                                <span class="navbar-text"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg>
                                &nbsp;<?php echo $nazwisko;?></span>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </nav>