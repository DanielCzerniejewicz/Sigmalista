<?php
function WypiszKategorie()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $base = "podpierdalacze";
    $conn = new mysqli($servername, $username, $password, $base);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT Kategoria FROM punktacja";
    $wynik = $conn->query($query);

    if ($wynik) {
        echo "<select name='kategoria' id='kategoria'>";
        while ($row = $wynik->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row["Kategoria"]) . "'>" . htmlspecialchars($row["Kategoria"]) . "</option>";
        }
        echo "</select><br>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}

function WypiszPodpierdalaczy()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $base = "podpierdalacze";
    $conn = new mysqli($servername, $username, $password, $base);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT ID_Sygnalisty,Ksywa FROM sygnalisci";
    $wynik = $conn->query($query);

    if ($wynik) {
        echo "<h2>Sygnaliści w Bazie </h2><br>";
        echo "<table border='1' id='sygnalisci_tabela'>";
        echo "<tr><td>ID_Sygnalisty</td><td>Ksywa</td></tr>";
        while ($row = $wynik->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["ID_Sygnalisty"]) . "</td><td>" . htmlspecialchars($row["Ksywa"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}

$servername = "localhost";
$username = "root";
$password = "";
$base = "podpierdalacze";
$conn = new mysqli($servername, $username, $password, $base);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sprawdzamy, czy formularz dodawania sygnalisty został wysłany
if (isset($_POST["ksywa"])) {
    $ksywa = $_POST["ksywa"];
    $query = "INSERT INTO sygnalisci (Ksywa) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ksywa);

    if ($stmt->execute()) {
        echo "Nowy sygnalista został dodany do bazy.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Sprawdzamy, czy formularz dodawania "podpierdolki" został wysłany
if (isset($_POST["id_konfy"]) && isset($_POST["kategoria"])) {
    $ID_Sygnalisty = $_POST["id_konfy"];
    $Kategoria = $_POST["kategoria"];

    $query = "INSERT INTO podjebki (ID_Sygnalisty, Kategoria) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $ID_Sygnalisty, $Kategoria);

    if ($stmt->execute()) {
        echo "Podpierdolka została dodana.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Doniesienie</title>
    <link rel="stylesheet" href="dodaj_podjebke.css">
</head>
<body>
<div id="podpierdalacze">
    <?php WypiszPodpierdalaczy(); ?>
    <br>
</div>

<div id="Dodaj_podjebke">
    <form method="post">
        <input type="number" min="1" placeholder="ID_Sygnalisty" id="id_konfy" name="id_konfy" required><br>
        <?php WypiszKategorie(); ?>
        <input type="submit" value="Dodaj Podpierdolkę">
    </form>
</div>

<div id="Dodaj_Sygnaliste">
    <!-- Formularz do dodania sygnalisty -->
    <h3>Dodaj nowego sygnalistę</h3>
    <form method="post">
        <label for="ksywa"></label>
        <input type="text" id="ksywa" name="ksywa" placeholder="Ksywa Sygnalisty" required><br>
        <input type="submit" value="Dodaj Sygnalistę">
    </form>
</div>
<button onclick="window.location.href='glowna.php'">Powrót</button>
</body>
</html>
