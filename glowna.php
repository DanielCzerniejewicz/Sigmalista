<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="glowna.css">
    <title>SIGMAliści</title>
</head>
<body>
<!--Paseczek z logoutem oraz dodawaniem userow do bazy jesli admin, dodawanie podjebek-->
    <header>
        <h1>Sigmaliści</h1>
        <button><a href="logout.php">Wyloguj</a></button>
        <button><a href="panel.php">Panel</a></button>
        <button><a href="dodaj_podjebke.php">Dodaj konfę</a></button>
    </header>
    <div id="podjebki">
<!--        OSTATNIE PODJEBKI WYPISANE Z BAZY DANYCH (MAX Z ID)        Gotowe -->
        <div id="lewy-gorny">
            <h2>Ostatnie 5 doniesień</h2>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $base = "podpierdalacze";

            $conn = new mysqli($servername, $username, $password, $base);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = "SELECT podjebki.ID_Podjebki, podjebki.Kategoria, sygnalisci.Ksywa 
          FROM podjebki 
          INNER JOIN sygnalisci ON podjebki.ID_Sygnalisty = sygnalisci.ID_Sygnalisty 
          ORDER BY ID_Podjebki DESC LIMIT 5";
            if ($wynik = $conn->query($query)) {
                echo "<table border='1'>";
                echo "<td>ID Podjebki</td><td>Kategoria Podjebki</td><td>Sygnalisci</td><tr>";
                while ($row = $wynik->fetch_assoc()) {
                    echo "<td>".$row["ID_Podjebki"] ."</td><td>". $row["Kategoria"]."</td><td>" . $row["Ksywa"] . "</td><tr>";
                }
                echo "</table>";
                $wynik->free();
            } else {
                echo "Error: " . $conn->error;
            }
            $conn->close();
            ?>

        </div>
<!--        podpierdalacze     -->
        <div id="prawy-gorny">
            <h2>Sygnaliści</h2>
            <?php

                $server = "localhost";
                $user = "root";
                $pass = "";
                $base = "podpierdalacze";
                $conn = new mysqli($server, $username, $pass, $base);
                $query = "SELECT ID_Sygnalisty,ksywa FROM `sygnalisci`;";

                if($wynik = $conn->query($query))
                    echo "<table border='1'>";
                    echo "<td>Sygnalisci</td><td>Ksywa</td></tr>";
                    while($row = $wynik->fetch_array())
                    {
                        echo "<td>".$row["ID_Sygnalisty"]."</td><td>".$row["ksywa"]."</td><tr>";
                    }
                    echo "</table>";
            ?>

        </div>
<!--        Ranking podpierdalaczy SUM(punkty) GROUP BY ID_Sygnalisty       -->
        <div id="lewy-dolny">
            <h2>Ranking Sygnalistów</h2>
            <?php
                $server = "localhost";
                $user = "root";
                $pass = "";
                $base = "podpierdalacze";
                $conn = new mysqli($server, $username, $pass, $base);
                $query = "SELECT podjebki.ID_Sygnalisty,sygnalisci.Ksywa, SUM(punktacja.Punktacja) as PunktySzacunku FROM podjebki INNER JOIN punktacja ON podjebki.Kategoria = punktacja.Kategoria INNER JOIN sygnalisci ON podjebki.ID_Sygnalisty = sygnalisci.ID_Sygnalisty GROUP BY podjebki.ID_Sygnalisty HAVING PunktySzacunku > 0 ORDER BY PunktySzacunku DESC;";
                if($wynik = $conn->query($query))
                {
                    echo "<table border='1'>";
                    echo "<td>ID_Sygnalisty</td><td>Ksywa</td><td>Punkty Stylu</td></tr>";
                    while($row = $wynik->fetch_array())
                    {
                        echo "<td>".$row["ID_Sygnalisty"]."</td><td>".$row["Ksywa"]."</td><td>".$row["PunktySzacunku"]."</td><tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
        <!--        Spis Kategorii   i Bonusy   -->
        <div id="prawy-dolny">
            <h2>Spis Kategorii</h2>
            <?php
                $server = "localhost";
                $user = "root";
                $pass = "";
                $base = "podpierdalacze";
                $conn = new mysqli($server, $username, $pass, $base);
                $query = "SELECT Kategoria,Punktacja FROM `punktacja`;";

                if($wynik = $conn->query($query))
                    echo "<table border='1'>";
                    echo "<td>Kategoria</td><td>Punktacja</td></tr>";
                    while($row = $wynik->fetch_array())
                    {
                        echo "<td>".$row["Kategoria"]."</td><td>".$row["Punktacja"]."</td><tr>";
                    }
                    echo "</table>";
            ?>
            <h2>Bonusiki ;)</h2>
            <?php

                $server = "localhost";
                $user = "root";
                $pass = "";
                $base = "podpierdalacze";
                $conn = new mysqli($server, $username, $pass, $base);
                $query = "SELECT Kategoria,Punktacja FROM `bonusy`;";

                if($wynik = $conn->query($query))
                    echo "<table border='1'>";
                    echo "<td>Kategoria</td><td>Punktacja</td></tr>";
                    while($row = $wynik->fetch_array())
                    {
                        echo "<td>".$row["Kategoria"]."</td><td>".$row["Punktacja"]."</td><tr>";
                    }
                    echo "</table>";
            ?>
        </div>

    </div>
</body>
</html>
