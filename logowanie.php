<?php
session_start();

$server = "localhost";
$user = "w";
$pass = "";
$base = "podpierdalacze";
$conn = new mysqli($server, $user, $pass, $base);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = isset($_POST["login"]) ? $_POST["login"] : "";
    $haslo = isset($_POST["haslo"]) ? $_POST["haslo"] : "";

    if (!empty(trim($login)) && !empty(trim($haslo))) {
        $stmt = $conn->prepare("SELECT ID_Usera, Haslo FROM userzy WHERE Ksywa = ? AND Haslo = ?");

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $login, $haslo);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $moje_id = $row["ID_Usera"];
            $_SESSION["moje_id"] = $moje_id;
            header('Location: glowna.php');
            exit;
        } else {
            echo "Błędne dane logowania.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="logowanie.css">
</head>
<body>
<div id="logowanie">
    <form method="post">
        <input type="text" placeholder="Login" name="login" id="login"> <br>
        <input type="password" placeholder="Hasło" name="haslo" id="haslo"> <br>
        <input type="submit" value="Zaloguj" id="zaloguj">
    </form>
</div>
</body>
</html>
