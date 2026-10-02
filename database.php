<?php
declare(strict_types=1);

const DB_PATH = __DIR__ . '/data/galaxy.sqlite';
const MUSIC_DIR = __DIR__ . '/uploads/music/';
const VIDEO_DIR = __DIR__ . '/uploads/videos/';
const COVER_DIR = __DIR__ . '/uploads/covers/';

foreach ([dirname(DB_PATH), MUSIC_DIR, VIDEO_DIR, COVER_DIR] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0755, true);
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec("CREATE TABLE IF NOT EXISTS media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type TEXT NOT NULL CHECK(type IN ('music','video')),
            title TEXT NOT NULL,
            artist TEXT DEFAULT '',
            file_name TEXT NOT NULL,
            file_url TEXT NOT NULL,
            cover_url TEXT DEFAULT '',
            mime_type TEXT NOT NULL,
            file_size INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
        $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL, email TEXT NOT NULL, message TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
    }
    return $pdo;
}

function get_media(?string $type = null): array {
    $pdo = db();
    if ($type) {
        $s = $pdo->prepare("SELECT * FROM media WHERE type = ? ORDER BY id DESC");
        $s->execute([$type]);
        return $s->fetchAll();
    }
    return $pdo->query("SELECT * FROM media ORDER BY id DESC")->fetchAll();
}
db();
?>
