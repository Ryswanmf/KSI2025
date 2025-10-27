<?php
include 'koneksi.php';

$nim = $nama = $jurusan = $email = "";
$errors = [];
$id = null;

// Cek ID dari URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
} else {
    die("ID tidak ditemukan.");
}

// Proses saat form disubmit (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi data
    if (empty(trim($_POST["nim"]))) {
        $errors[] = "NIM wajib diisi.";
    } else {
        $nim = trim($_POST["nim"]);
    }

    if (empty(trim($_POST["nama"]))) {
        $errors[] = "Nama wajib diisi.";
    } else {
        $nama = trim($_POST["nama"]);
    }

    if (empty(trim($_POST["jurusan"]))) {
        $errors[] = "Jurusan wajib diisi.";
    } else {
        $jurusan = trim($_POST["jurusan"]);
    }

    if (empty(trim($_POST["email"]))) {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Jika tidak ada error, update data
    if (empty($errors)) {
        // Cek duplikasi NIM dengan ID yang berbeda
        $stmt_check = $conn->prepare("SELECT id FROM mahasiswa WHERE nim = ? AND id != ?");
        $stmt_check->bind_param("si", $nim, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $errors[] = "NIM sudah digunakan oleh mahasiswa lain.";
        } else {
            $sql = "UPDATE mahasiswa SET nim=?, nama=?, jurusan=?, email=? WHERE id=?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssi", $nim, $nama, $jurusan, $email, $id);
                if ($stmt->execute()) {
                    header("location: index.php");
                    exit();
                } else {
                    $errors[] = "Gagal memperbarui data.";
                }
                $stmt->close();
            }
        }
        $stmt_check->close();
    }
} else {
    // Ambil data existing untuk ditampilkan di form (GET)
    $sql = "SELECT nim, nama, jurusan, email FROM mahasiswa WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($nim, $nama, $jurusan, $email);
                $stmt->fetch();
            } else {
                die("Data tidak ditemukan.");
            }
        } else {
            die("Terjadi kesalahan.");
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 15px; border-radius: 3px; color: white; border: none; cursor: pointer; }
        .btn-submit { background-color: #007bff; }
        .btn-back { background-color: #6c757d; text-decoration: none; display: inline-block; }
        .errors { color: #dc3545; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Mahasiswa</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <strong>Error:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $id); ?>" method="post">
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" name="nim" id="nim" value="<?php echo htmlspecialchars($nim); ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
            </div>
            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <input type="text" name="jurusan" id="jurusan" value="<?php echo htmlspecialchars($jurusan); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <button type="submit" class="btn btn-submit">Update</button>
            <a href="index.php" class="btn btn-back">Kembali</a>
        </form>
    </div>
</body>
</html>
