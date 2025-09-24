<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Arahkan kembali ke halaman beranda (index.php)
header("Location: index.php");
exit();
