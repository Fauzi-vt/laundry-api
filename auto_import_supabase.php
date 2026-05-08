<?php
function getEnvValue($key, $default = null) {
    $content = file_get_contents(__DIR__ . '/.env');
    if (preg_match("/^{$key}=(.*)$/m", $content, $matches)) {
        return trim($matches[1], " \t\n\r\0\x0B\"");
    }
    return $default;
}

// Gunakan IP IPv6 langsung untuk menghindari DNS error
$host = "[2406:da18:243:7423:b8a5:74b5:152a:1c6c]"; 
$port = getEnvValue('DB_PORT', '5432');
$db   = getEnvValue('DB_DATABASE');
$user = getEnvValue('DB_USERNAME');
$pass = getEnvValue('DB_PASSWORD');

try {
    // Tambahkan timeout dan SSL mode jika diperlukan
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 30
    ]);
    
    echo "Koneksi ke Supabase Berhasil via IPv6!\n";
    
    $sql = file_get_contents(__DIR__ . '/master_migration.sql');
    
    echo "Sedang mengimpor data... Mohon tunggu...\n";
    
    $pdo->exec($sql);
    
    echo "BERHASIL! Data dari HeidiSQL sudah pindah ke Supabase.\n";

} catch (PDOException $e) {
    echo "ERROR: Gagal import. " . $e->getMessage() . "\n";
    echo "Saran: Jika masih gagal, kemungkinan internet Anda tidak mendukung IPv6. Silakan gunakan cara 'Copy-Paste' ke SQL Editor Supabase secara manual.\n";
}
