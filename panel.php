<?php
session_start();

$server = "localhost";
$user = "root";
$pass = "";
$base = "podpierdalacze";
$conn = new mysqli($server, $user, $pass, $base);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION["moje_id"])) {
    $moje_id = $_SESSION["moje_id"];

    $stmt = $conn->prepare("
        SELECT ID_Usera, Ranga
        FROM userzy
        WHERE ID_Usera = ? AND Ranga = 'Admin'
    ");

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $moje_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_role = $row["Ranga"];

        if ($user_role == "Admin") {
            echo "<button onclick=\"window.location.href='panel_user.php'\">Panel Usera</button>";
            echo "<button onclick=\"window.location.href='panel_admin.php'\">Panel Admina</button>";
        }
    } else {
        header('Location: panel_user.php');
    }
    $stmt->close();
} else {
    header('Location: logowanie.php');
    exit;
}

$conn->close();
?>
