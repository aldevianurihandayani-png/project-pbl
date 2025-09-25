<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'Akses.php'; 

    $tanggal    = $_POST['tanggal'] ?? '';
    $aktivitas  = $_POST['aktivitas'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $fotoPath   = null;

    // Cek apakah ada file foto yang diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $targetDir = "uploads/"; // folder untuk menyimpan file
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // buat folder kalau belum ada
        }

        $fileName   = time() . "_" . basename($_FILES["foto"]["name"]);
        $targetFile = $targetDir . $fileName;

        // Validasi tipe file
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes  = ["jpg", "jpeg", "png"];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                $fotoPath = $targetFile;
            } else {
                $error = "Gagal upload foto.";
            }
        } else {
            $error = "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
        }
    }

    // Simpan ke database kalau tidak ada error
    if (!isset($error)) {
        $query = $pdo->prepare("INSERT INTO logbook (tanggal, aktivitas, keterangan, foto) 
                                VALUES (:tanggal, :aktivitas, :keterangan, :foto)");
        $query->bindValue(':tanggal', $tanggal);
        $query->bindValue(':aktivitas', $aktivitas);
        $query->bindValue(':keterangan', $keterangan);
        $query->bindValue(':foto', $fotoPath);
        $query->execute();

        $success = "Logbook berhasil disimpan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Catat Logbook</title>
  <style>
    /* GLOBAL */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #f9f9f9;
      color: #333;
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* CONTAINER */
    .container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border: 2px solid #19008b;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* JUDUL */
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #19008b;
      font-size: 28px;
    }

    /* FORM */
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      color: #19008b;
    }

    input[type="text"],
    input[type="date"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    textarea:focus {
      outline: none;
      border-color: #19008b;
    }

    /* BUTTON */
    button {
      padding: 12px;
      background-color: #19008b;
      color: #fff;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #130068;
    }

    /* TABEL UNTUK LIST LOGBOOK */
    .table-logbook {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    .table-logbook th,
    .table-logbook td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }

    .table-logbook th {
      background-color: #19008b;
      color: #fff;
      text-transform: uppercase;
      font-size: 14px;
    }

    .table-logbook tr:nth-child(even) {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Catat Logbook</h1>

    <!-- tampilkan pesan -->
    <?php if (isset($success)): ?>
      <p style="color:green;"><?= $success ?></p>
    <?php elseif (isset($error)): ?>
      <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
      <label for="tanggal">Tanggal:</label>
      <input type="date" name="tanggal" required>

      <label for="aktivitas">Aktivitas:</label>
      <input type="text" name="aktivitas" required>

      <label for="keterangan">Keterangan:</label>
      <textarea name="keterangan" rows="5" required></textarea>

      <label for="foto">Foto Dokumentasi:</label>
      <input type="file" name="foto" accept="image/*">

      <button type="submit">Simpan Logbook</button>
    </form>
  </div>
</body>
</html>
