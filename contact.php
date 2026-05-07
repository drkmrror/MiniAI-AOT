<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "html5"
);

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO messages (name, email, message)
VALUES ('$name', '$email', '$message')";

mysqli_query($conn, $sql);

echo "Mesaj başarıyla kaydedildi!";

?>