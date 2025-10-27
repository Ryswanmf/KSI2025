<?php
// Cek apakah parameter id ada di URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    require_once "koneksi.php";

    $id = trim($_GET["id"]);

    // Siapkan statement delete
    $sql = "DELETE FROM mahasiswa WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $id);

        // Coba eksekusi statement
        if ($stmt->execute()) {
            // Jika berhasil, redirect ke halaman utama
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Terjadi kesalahan. Silakan coba lagi nanti.";
        }
    }

    // Tutup statement
    $stmt->close();

    // Tutup koneksi
    $conn->close();
} else {
    // Jika ID tidak ada, redirect ke halaman error atau utama
    echo "ID tidak valid.";
    exit();
}
?>
