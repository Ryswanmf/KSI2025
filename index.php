<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        .container { max-width: 960px; margin: auto; }
        .btn { padding: 5px 10px; border-radius: 3px; color: white; display: inline-block; margin-top: 20px;}
        .btn-add { background-color: #28a745; }
        .btn-edit { background-color: #ffc107; color: #212529; padding: 2px 5px;}
        .btn-delete { background-color: #dc3545; padding: 2px 5px;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Mahasiswa</h2>
        <a href="tambah.php" class="btn btn-add">Tambah Mahasiswa</a>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'koneksi.php';
                $sql = "SELECT id, nim, nama, jurusan, email FROM mahasiswa ORDER BY nama ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jurusan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo '<td>';
                        echo '<a href="edit.php?id=' . $row['id'] . '" class="btn-edit">Edit</a> ';
                        echo "<a href='hapus.php?id=" . $row['id'] . "' class='btn-delete' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>";
                        echo '</td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada data</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>



