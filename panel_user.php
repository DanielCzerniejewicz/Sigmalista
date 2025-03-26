<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $base = "podpierdalacze";
    $conn = new mysqli($servername, $username, $password, $base);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST["nowy_login"]) && isset($_POST["nowe_haslo"])) {
        $login = $_POST["nowy_login"];
        $haslo = $_POST["nowe_haslo"];
        $user_id = isset($_SESSION["moje_id"]) ? $_SESSION["moje_id"] : null;

        if ($user_id !== null) {
            $stmt = $conn->prepare("UPDATE userzy SET Ksywa = ?, Haslo = ? WHERE ID_Usera = ?");
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("ssi", $login, $haslo, $user_id);
            if ($stmt->execute() === TRUE) {
                header("Location: glowna.php");
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "User ID is not set in the session.";
        }
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Usera</title>
    <link rel="stylesheet" href="panel_user.css">
</head>
<body>
<form method="post">
    <h2>Zmiana Loginu i hasła</h2>
    <input type="text" placeholder="Nowy Login" id="nowy_login" name="nowy_login" required> <br>
    <input type="text" placeholder="Nowe Hasło" id="nowe_haslo" name="nowe_haslo" required> <br>
    <input type="submit" value="Zatwierdz" id="nowy_submit" name="nowy_submit">
</form>
</body>
</html>
