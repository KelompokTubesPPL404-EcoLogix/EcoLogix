<?php
session_start();

// Database setup
$conn = new mysqli("localhost", "root", "", "ecologix");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['password_confirm'] ?? '';
$role = $_POST['role'] ?? '';

// Validasi
if (empty($name) || empty($email) || empty($phone) || empty($password) || $password !== $confirm) {
    $_SESSION['error'] = "Data tidak lengkap atau password tidak cocok.";
    header("Location: register_{$role}.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $phone, $hashed, $role);

if ($stmt->execute()) {
    header("Location: login.php");
    exit();
} else {
    $_SESSION['error'] = "Registrasi gagal: " . $stmt->error;
    header("Location: register_{$role}.php");
    exit();
}
