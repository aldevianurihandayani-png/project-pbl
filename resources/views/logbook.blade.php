<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'Akses.php'; 

    $tanggal = $_POST['tanggal'] ?? '';
    $aktivitas = $_POST['aktivitas'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';

    $query = $pdo->prepare("INSERT INTO logbook (tanggal, aktivitas, keterangan) 
                            VALUES (:tanggal, :aktivitas, :keterangan)");
    $query->bindValue(':tanggal', $tanggal);
    $query->bindValue(':aktivitas', $aktivitas);
    $query->bindValue(':keterangan', $keterangan);
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
<form method="POST" action="">
  <label>Tanggal:</label><br>
  <input type="date" name="tanggal" required><br><br>

  <label>Aktivitas:</label><br>
  <input type="text" name="aktivitas" required><br><br>

  <label>Keterangan:</label><br>
  <textarea name="keterangan" rows="5" cols="50" required></textarea><br><br>
  <button type="submit">Simpan Logbook</button>
</form>
</body>
</html>
