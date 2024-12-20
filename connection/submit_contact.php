<?php
// Include koneksi
$path = 'db_connection.php';
if (file_exists($path)) {
    include $path;
} else {
    die("File koneksi tidak ditemukan!");
}

$responseMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if ($name && $email && $subject && $message) {
        $sql = "INSERT INTO contactus (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $responseMessage = "Pesan Anda berhasil dikirim, terima kasih!";
        } else {
            $responseMessage = "Terjadi kesalahan: " . $conn->error;
        }

        $stmt->close();
    } else {
        $responseMessage = "Mohon lengkapi semua data!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Contact</title>
    <style>
        /* Overlay */
        #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Transparansi latar belakang */
        z-index: 999; /* Tetap berada di bawah pop-up */
        }

        /* Form styling (jaga di atas overlay) */
        form {
        z-index: 1001;
        position: relative; /* Tambahkan ini agar form tetap interaktif */
        }

        /* Pop-up styling */
        #popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        z-index: 1000; /* Di atas overlay */
        text-align: center;
        font-family: Arial, sans-serif;
        }

        #popup button {
        margin-top: 15px;
        padding: 10px 15px;
        border: none;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        }

        #popup button:hover {
        background-color: #45a049;
        }
    </style>

    <script>
       // Function to show pop-up
    function showPopup(message) {
        document.getElementById('popup-message').textContent = message;
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

        // Function to close pop-up
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
    </script>
</head>

<body>
    <div id="overlay"></div>
    <div id="popup">
        <p id="popup-message"></p>
        <button onclick="closePopup()">Tutup</button>
    </div>

    <?php if (!empty($responseMessage)) : ?>
        <script>
            showPopup("<?php echo $responseMessage; ?>");
        </script>
    <?php endif; ?>

    <form action="submit_contact.php" method="POST">
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>

        <label for="subject">Subjek:</label><br>
        <input type="text" id="subject" name="subject"><br><br>

        <label for="message">Pesan:</label><br>
        <textarea id="message" name="message"></textarea><br><br>

        <button type="submit">Kirim</button>
    </form>
</body>
</html>
