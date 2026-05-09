<?php
// XAMPP Localhost Database Credentials
$servername = "localhost";
$username = "kamesdca_satyam";      // XAMPP me default username 'root' hota hai
$password = "kameshwar@5858";          // XAMPP me default password khali (empty) hota hai
$dbname = "kamesdca_kf";          // Aapke database ka naam jo humne abhi banaya

// Connection Create karna (MySQLi Object-Oriented approach)
$conn = new mysqli($servername, $username, $password, $dbname);

// Connection Check karna
if ($conn->connect_error) {
    // Agar connection fail ho jaye toh error show karega
    die("Database Connection Failed: " . $conn->connect_error);
}

// Note: Niche di gayi line sirf testing ke liye hai. 
// Jab website live karein toh is 'echo' ko hata dein (comment kar dein) taaki page par text na dikhe.
// echo "Database 'kf' connected successfully!"; 
?>