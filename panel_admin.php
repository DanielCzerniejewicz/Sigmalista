<?php
session_start(); // Ensure session is started at the very top

$servername = "localhost";
$username = "root";
$password = "";
$base = "podpierdalacze";
$conn = new mysqli($servername, $username, $password, $base);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["form_type"])) {
        switch ($_POST["form_type"]) {
            case "update":
                if (isset($_POST["nowy_login"]) && isset($_POST["nowe_haslo"])) {
                    $login = $conn->real_escape_string($_POST["nowy_login"]);
                    $haslo = $conn->real_escape_string($_POST["nowe_haslo"]);
                    $user_id = $conn->real_escape_string($_POST["ID_Usera_input"]);
                    $query = "UPDATE userzy SET Ksywa = '$login', Haslo = '$haslo' WHERE ID_Usera = $user_id";

                    if ($user_id !== null) {
                        if ($conn->query($query) === TRUE) {
                            echo "Wykonano";
                        } else {
                            echo "Error: " . $conn->error;
                        }
                    } else {
                        echo "User ID is not set in the session.";
                    }
                }
                break;

            case "create":
                if (isset($_POST["nowy_login"]) && isset($_POST["nowe_haslo"])) {
                    $login = $conn->real_escape_string($_POST["nowy_login"]);
                    $haslo = $conn->real_escape_string($_POST["nowe_haslo"]);
                    $ranga = $conn->real_escape_string($_POST["selekcik"]);
                    $query = "INSERT INTO userzy (Ksywa, Haslo,Ranga) VALUES ('$login', '$haslo', '$ranga')";

                    if ($conn->query($query) === TRUE) {
                        echo "Utworzono";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                }
                break;

            case "delete":
                if (isset($_POST["user_id_to_delete"])) {
                    $user_id_to_delete = $conn->real_escape_string($_POST["user_id_to_delete"]);
                    $query = "DELETE FROM userzy WHERE ID_Usera = $user_id_to_delete";

                    if ($conn->query($query) === TRUE) {
                        echo "Usunieto frajera.";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                }
                break;

            default:
                echo "Wprowadzono błędne dane.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Admina</title>
    <link rel="stylesheet" href="panel_admin.css">
</head>
<body>
<div id="formularze">
    <div id="wypisywane">
        <?php
        $server="localhost";
        $username = "root";
        $password = "";
        $base = "podpierdalacze";
        $conn = new mysqli($server, $username, $password, $base);
        $query = "SELECT ID_Usera, Ksywa, Haslo FROM userzy";
        if ($result = $conn->query($query)) {
            echo "<table border='1'>";
            echo "<tr><td>ID_Usera</td><td>Ksywa</td><td>Haslo</td></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["ID_Usera"]."</td><td>".$row["Ksywa"]."</td><td>".$row["Haslo"]."</td></tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
    <form method="post">
        <h2>Zmiana Loginu i hasła</h2>
        <input type="hidden" name="form_type" value="update">
        <input type="text" placeholder="Nowy Login" id="nowy_login" name="nowy_login" required> <br>
        <input type="text" placeholder="Nowe Hasło" id="nowe_haslo" name="nowe_haslo" required> <br>
        <input type="number" placeholder="ID_Usera" id="ID_Usera_input" name="ID_Usera_input" required min="1"> <br>
        <input type="submit" value="Zatwierdz" id="nowy_submit" name="nowy_submit">
    </form>
    <form method="post">
        <h2>Tworzenie Nowego Usera</h2>
        <input type="hidden" name="form_type" value="create">
        <input type="text" placeholder="Nowy Login" id="nowy_login_create" name="nowy_login" required> <br>
        <input type="text" placeholder="Nowe Hasło" id="nowe_haslo_create" name="nowe_haslo" required> <br>
        <br>
        <select id="selekcik" name="selekcik">
            <option>Admin</option>
            <option>User</option>
        </select><br>
        <input type="submit" value="Stworz" id="nowy_create" name="nowy_create">
    </form>
    <form method="post">
        <h2>Usuwanie Usera</h2>
        <input type="hidden" name="form_type" value="delete">
        <input type="number" min="1" placeholder="ID_Usera" id="user_id_to_delete" name="user_id_to_delete" required> <br>
        <input type="submit" value="Usun" id="delete_submit" name="delete_submit">
    </form>
    <button onclick="window.location.href='glowna.php'">Powrót do głównej</button>
</div>

</body>
</html>
