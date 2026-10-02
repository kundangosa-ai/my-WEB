<?php
declare(strict_types=1);
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

const MAX_AUDIO = 100 * 1024 * 1024;
const MAX_VIDEO = 1024 * 1024 * 1024;
const MAX_COVER = 5 * 1024 * 1024;
/* Upload password is configured in config.php. */

function response(bool $ok, string $message = '', array $data = [], int $status = 200): never {
    http_response_code($status);
    echo json_encode(['success'=>$ok,'message'=>$message,'data'=>$data], JSON_UNESCAPED_SLASHES);
    exit;
}
function clean(string $v): string { return trim(strip_tags($v)); }
function safe_name(string $original): string {
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    return bin2hex(random_bytes(16)) . ($ext ? '.' . $ext : '');
}
function allowed_audio(string $mime): bool {
    return in_array($mime, ['audio/mpeg','audio/mp3','audio/wav','audio/x-wav','audio/ogg','audio/mp4','audio/aac','audio/flac','audio/webm'], true);
}
function allowed_video(string $mime): bool {
    return in_array($mime, ['video/mp4','video/webm','video/ogg','video/quicktime','video/x-msvideo','video/mpeg'], true);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'media') {
    $type = $_GET['type'] ?? null;
    if ($type !== null && !in_array($type, ['music','video'], true)) response(false, 'Invalid media type', [], 400);
    response(true, 'OK', ['items'=>get_media($type)]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload') {
    if (!hash_equals(GALAXY_UPLOAD_PASSWORD, (string)($_POST['password'] ?? ''))) response(false, 'Incorrect upload password.', [], 403);
    $type = $_POST['mediaType'] ?? '';
    $title = clean($_POST['title'] ?? '');
    $artist = clean($_POST['artist'] ?? '');
    if (!in_array($type, ['music','video'], true) || $title === '') response(false, 'Media type and title are required.', [], 422);
    if (!isset($_FILES['mediaFile']) || $_FILES['mediaFile']['error'] !== UPLOAD_ERR_OK) response(false, 'Media upload failed.', [], 400);

    $f = $_FILES['mediaFile'];
    $limit = $type === 'music' ? MAX_AUDIO : MAX_VIDEO;
    if ($f['size'] <= 0 || $f['size'] > $limit) response(false, 'File is missing or exceeds the allowed size.', [], 413);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (($type === 'music' && !allowed_audio($mime)) || ($type === 'video' && !allowed_video($mime))) response(false, 'Unsupported media format.', [], 415);

    $name = safe_name($f['name']);
    $targetDir = $type === 'music' ? MUSIC_DIR : VIDEO_DIR;
    $urlPrefix = $type === 'music' ? 'uploads/music/' : 'uploads/videos/';
    if (!move_uploaded_file($f['tmp_name'], $targetDir . $name)) response(false, 'Could not save media file.', [], 500);

    $coverUrl = '';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['cover']['size'] > MAX_COVER) response(false, 'Cover image is too large.', [], 413);
        $coverMime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['cover']['tmp_name']);
        if (!in_array($coverMime, ['image/jpeg','image/png','image/webp'], true)) response(false, 'Cover must be JPG, PNG or WebP.', [], 415);
        $coverName = safe_name($_FILES['cover']['name']);
        if (!move_uploaded_file($_FILES['cover']['tmp_name'], COVER_DIR . $coverName)) response(false, 'Could not save cover image.', [], 500);
        $coverUrl = 'uploads/covers/' . $coverName;
    }

    $s = db()->prepare("INSERT INTO media(type,title,artist,file_name,file_url,cover_url,mime_type,file_size) VALUES(?,?,?,?,?,?,?,?)");
    $s->execute([$type,$title,$artist,$name,$urlPrefix.$name,$coverUrl,$mime,$f['size']]);
    $id = (int)db()->lastInsertId();
    $s = db()->prepare("SELECT * FROM media WHERE id=?"); $s->execute([$id]);
    response(true, 'Upload complete.', ['item'=>$s->fetch()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'contact') {
    $name = clean($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || !$email || $message === '') response(false, 'Please complete all contact fields.', [], 422);
    $s = db()->prepare("INSERT INTO messages(name,email,message) VALUES(?,?,?)");
    $s->execute([$name,$email,$message]);
    response(true, 'Message received. Thank you.');
}

response(false, 'Unknown request.', [], 404);
?>
