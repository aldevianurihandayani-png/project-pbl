<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'Akses.php'; 

    $tanggal = $_POST['tanggal'] ?? '';
    $aktivitas = $_POST['aktivitas'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $fotoPath = null;

    // Cek apakah ada file foto yang diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $targetDir = "uploads/"; // folder untuk menyimpan file
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // buat folder kalau belum ada
        }

        $fileName = time() . "_" . basename($_FILES["foto"]["name"]);
        $targetFile = $targetDir . $fileName;

        // Validasi tipe file
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png"];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                $fotoPath = $targetFile;
            } else {
                echo "Gagal upload foto.";
            }
        } else {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        }
    }

    // Simpan ke database
    $query = $pdo->prepare("INSERT INTO logbook (tanggal, aktivitas, keterangan, foto) 
                            VALUES (:tanggal, :aktivitas, :keterangan, :foto)");
    $query->bindValue(':tanggal', $tanggal);
    $query->bindValue(':aktivitas', $aktivitas);
    $query->bindValue(':keterangan', $keterangan);
    $query->bindValue(':foto', $fotoPath);
    $query->execute();

    echo "Logbook berhasil disimpan!";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Catat Logbook</title>
</head>
<body>
<h1>Catat Logbook</h1>
<form method="POST" action="" enctype="multipart/form-data">
  <label>Tanggal:</label><br>
  <input type="date" name="tanggal" required><br><br>

  <label>Aktivitas:</label><br>
  <input type="text" name="aktivitas" required><br><br>

  <label>Keterangan:</label><br>
  <textarea name="keterangan" rows="5" cols="50" required></textarea><br><br>

  <label>Foto Dokumentasi:</label><br>
  <input type="file" name="foto" accept="image/*"><br><br>

  <button type="submit">Simpan Logbook</button>
</form>
</body>
</html>
