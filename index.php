<?php
require_once __DIR__ . '/database.php';
$music = get_media('music');
$videos = get_media('video');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Galaxy Stream - Premium Music & Video Delivery</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
tailwind.config={darkMode:'class',theme:{extend:{colors:{brand:{300:'#60a5fa',400:'#3b82f6',500:'#2563eb',600:'#1d4ed8'},dark:{700:'#374151',800:'#1f2937',900:'#111827'}},fontFamily:{sans:['Inter','sans-serif']}}}};
</script>
</head>
<body class="antialiased min-h-screen flex flex-col bg-dark-900 text-white font-sans">

<nav id="navbar" class="glass-nav fixed w-full z-50 top-0 transition-all duration-300">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"><div class="flex items-center justify-between h-16">
<div class="flex-shrink-0 flex items-center cursor-pointer" onclick="window.scrollTo({top:0,behavior:'smooth'})">
<i class="fa-solid fa-play-circle text-brand-400 text-3xl mr-2"></i>
<span class="font-bold text-xl tracking-wider">Galaxy<span class="text-brand-400">Stream</span></span>
</div>
<div class="hidden md:block"><div class="ml-10 flex items-baseline space-x-8">
<a href="#home" class="nav-link">Home</a><a href="#music" class="nav-link">Music</a><a href="#videos" class="nav-link">Videos</a><a href="#contact" class="nav-link">Contact</a>
</div></div>
<div class="flex items-center gap-4">
<button id="openUploadBtn" class="btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i><span class="hidden sm:inline">Upload</span></button>
<button id="mobileMenuBtn" class="md:hidden text-gray-400 hover:text-white p-2"><i class="fa-solid fa-bars text-2xl"></i></button>
</div></div></div>
<div id="mobileMenu" class="hidden md:hidden bg-dark-800 border-t border-gray-700">
<div class="px-2 pt-2 pb-3 space-y-1"><a href="#home" class="mobile-link">Home</a><a href="#music" class="mobile-link">Music</a><a href="#videos" class="mobile-link">Videos</a><a href="#contact" class="mobile-link">Contact</a></div>
</div>
</nav>

<main class="flex-grow pt-16">
<section id="home" class="relative overflow-hidden py-20 sm:py-32 hero">
<div class="glow glow-1"></div><div class="glow glow-2"></div>
<div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
<h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">Discover Latest<br><span class="gradient-text">Music & Visuals</span></h1>
<p class="mt-4 max-w-2xl text-xl text-gray-400 mx-auto mb-10">Your exclusive hub for fresh tracks and high-quality videos. Stream directly or download to enjoy offline.</p>
<div class="flex flex-col sm:flex-row justify-center gap-4">
<a href="#music" class="bg-white text-dark-900 hover:bg-gray-200 px-8 py-3 rounded-full font-bold transition-colors">Explore Music</a>
<a href="#videos" class="bg-dark-800 border border-gray-700 hover:border-brand-500 text-white px-8 py-3 rounded-full font-bold transition-all">Watch Videos</a>
</div>
</div></section>

<section id="music" class="py-16 border-t border-gray-800/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between mb-10"><h2 class="text-3xl font-bold flex items-center gap-3"><i class="fa-solid fa-headphones text-brand-400"></i> Fresh Tracks</h2><span id="musicCount" class="count-badge"><?= count($music) ?> tracks</span></div>
<div id="musicGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<?php foreach($music as $item): ?>
<?= media_card($item, 'music') ?>
<?php endforeach; ?>
</div>
<div id="musicEmpty" class="<?= $music ? 'hidden' : '' ?> empty-state">No music has been uploaded yet.</div>
</div></section>

<section id="videos" class="py-16 border-t border-gray-800/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between mb-10"><h2 class="text-3xl font-bold flex items-center gap-3"><i class="fa-solid fa-video text-brand-400"></i> Featured Videos</h2><span id="videoCount" class="count-badge"><?= count($videos) ?> videos</span></div>
<div id="videoGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach($videos as $item): ?>
<?= media_card($item, 'video') ?>
<?php endforeach; ?>
</div>
<div id="videoEmpty" class="<?= $videos ? 'hidden' : '' ?> empty-state">No videos have been uploaded yet.</div>
</div></section>

<section id="contact" class="py-16 bg-dark-800/30 border-t border-gray-800/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-12"><h2 class="text-3xl font-bold mb-4"><i class="fa-solid fa-envelope text-brand-400"></i> Get in Touch</h2><p class="text-gray-400 max-w-xl mx-auto">Have a question, business inquiry, or want to submit your own media? Drop us a message.</p></div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
<div class="bg-dark-800 rounded-2xl p-8 border border-gray-700/50">
<h3 class="text-xl font-bold mb-6">Contact Information</h3>
<div class="space-y-6"><div class="contact-row"><i class="fa-solid fa-location-dot"></i><div><b>Our Office</b><p>Solwezi,<br>Northwestern Province, Zambia</p></div></div>
<div class="contact-row"><i class="fa-solid fa-phone"></i><div><b>Phone</b><p>+260 772294346</p></div></div>
<div class="contact-row"><i class="fa-solid fa-envelope"></i><div><b>Email</b><p>jamesjayborn@gmail.com</p></div></div></div>
</div>
<div class="bg-dark-800 rounded-2xl p-8 border border-gray-700/50">
<form id="contactForm" class="space-y-5">
<input type="text" id="name" required class="form-input" placeholder="Your name">
<input type="email" id="email" required class="form-input" placeholder="Email address">
<textarea id="message" rows="5" required class="form-input resize-none" placeholder="How can we help you?"></textarea>
<button class="btn-primary w-full justify-center" type="submit"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
</form></div>
</div></div></section>
</main>

<footer class="bg-dark-800 py-10 border-t border-gray-800"><div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
<div class="text-gray-400"><i class="fa-solid fa-play-circle text-brand-500 text-2xl mr-2"></i><span class="font-semibold text-white">Galaxy Stream</span> <span class="mx-2">|</span> &copy; <?= date('Y') ?> All Rights Reserved.</div>
<div class="flex space-x-6 text-gray-400"><a href="#" class="hover:text-white"><i class="fa-brands fa-twitter text-xl"></i></a><a href="#" class="hover:text-white"><i class="fa-brands fa-instagram text-xl"></i></a><a href="#" class="hover:text-white"><i class="fa-brands fa-youtube text-xl"></i></a></div>
</div></footer>

<div id="uploadModal" class="modal hidden">
<div class="modal-backdrop" id="modalBackdrop"></div>
<div class="modal-box" id="modalContent">
<div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center"><h3 class="text-xl font-bold"><i class="fa-solid fa-cloud-arrow-up text-brand-400 mr-2"></i> Upload New Media</h3><button id="closeModalBtn" class="text-gray-400 hover:text-white p-1"><i class="fa-solid fa-xmark text-xl"></i></button></div>
<form id="uploadForm" class="p-6 space-y-5" enctype="multipart/form-data">
<div><label class="label">Media Type</label><div class="grid grid-cols-2 gap-4">
<label class="choice"><input type="radio" name="mediaType" value="music" checked><span><i class="fa-solid fa-music"></i> Music Track</span></label>
<label class="choice"><input type="radio" name="mediaType" value="video"><span><i class="fa-solid fa-video"></i> Video</span></label>
</div></div>
<input type="text" name="title" id="title" class="form-input" placeholder="Title" required>
<input type="text" name="artist" id="artist" class="form-input" placeholder="Artist / Creator">
<input type="file" name="mediaFile" id="mediaFile" class="file-input" required accept="audio/*,video/*">
<input type="file" name="cover" id="cover" class="file-input" accept="image/jpeg,image/png,image/webp">
<input type="password" name="password" class="form-input" placeholder="Admin upload password" required>
<div id="uploadProgressWrap" class="hidden"><div class="progress"><span id="uploadProgress"></span></div><p id="uploadStatus" class="text-sm text-gray-400 mt-2">Uploading…</p></div>
<div class="pt-4 flex justify-end gap-3 border-t border-gray-700"><button type="button" id="cancelUploadBtn" class="btn-secondary">Cancel</button><button type="submit" class="btn-primary">Post Content</button></div>
</form></div></div>

<div id="toastContainer" class="fixed bottom-5 right-5 z-[200] flex flex-col gap-3"></div>
<script src="script.js"></script>
</body></html>
<?php
function media_card(array $item, string $type): string {
    $title = htmlspecialchars($item['title'], ENT_QUOTES);
    $artist = htmlspecialchars($item['artist'] ?: 'Galaxy Stream', ENT_QUOTES);
    $file = htmlspecialchars($item['file_url'], ENT_QUOTES);
    $cover = $item['cover_url'] ? htmlspecialchars($item['cover_url'], ENT_QUOTES) : '';
    $date = date('M j, Y', strtotime($item['created_at']));
    if ($type === 'music') {
        $art = $cover ? "<img src=\"$cover\" alt=\"\" class=\"media-cover\">" : '<div class="media-cover cover-fallback"><i class="fa-solid fa-music"></i></div>';
        return "<article class=\"media-card\"><div class=\"relative\">$art</div><div class=\"p-5\"><h3 class=\"font-bold text-lg truncate\">$title</h3><p class=\"text-sm text-gray-400 mb-4\">$artist • $date</p><audio controls preload=\"metadata\" class=\"w-full\"><source src=\"$file\" type=\"audio/mpeg\"></audio><a class=\"download-link\" href=\"$file\" download><i class=\"fa-solid fa-download\"></i> Download</a></div></article>";
    }
    $poster = $cover ? " poster=\"$cover\"" : '';
    return "<article class=\"media-card\"><div class=\"video-wrap\"><video controls preload=\"metadata\"$poster><source src=\"$file\" type=\"video/mp4\"></video></div><div class=\"p-5\"><h3 class=\"font-bold text-lg\">$title</h3><p class=\"text-sm text-gray-400\">$artist • $date</p><a class=\"download-link\" href=\"$file\" download><i class=\"fa-solid fa-download\"></i> Download</a></div></article>";
}
?>
