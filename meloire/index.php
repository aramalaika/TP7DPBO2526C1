
<?php

require_once 'config/database.php';
require_once 'classes/Mood.php';

$database = new Database();
$db = $database->connect();

$mood = new Mood($db);
$allMoods = $mood->getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meloire — Your Mood, Your Music</title>

    <link rel="stylesheet" href="assets/style.css">

    <!-- A little font combination for Meloire ♡ -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.php" class="logo">meloire<span> ♡</span></a>

        <div class="nav-links">
            <a href="index.php" class="active">Home</a>
            <a href="pages/songs.php">Discover</a>
            <a href="pages/journals.php">My Diary</a>
            <a href="pages/playlists.php">Playlists</a>
            <a href="pages/moods.php">Moods</a>
        </div>

        <div class="nav-note">made with ♡ and music</div>
    </nav>


    <!-- Hero Section -->
    <section class="hero">

        <div class="hero-content">
            <p class="eyebrow">A LITTLE MUSIC FOR YOUR SOUL ♡</p>

            <h1>
                Every feeling<br>
                deserves a <span>soundtrack.</span>
            </h1>

            <p class="hero-description">
                Some days are meant for dancing, some for crying.
                Whatever you're feeling, there's a song for it.
            </p>

            <a href="#moods" class="hero-button">Find your mood ♡</a>
        </div>

        <div class="hero-decoration">
            <div class="music-card">
                <span class="music-heart">♡</span>
                <p class="music-label">CURRENTLY FEELING</p>
                <h2>something soft</h2>
                <p class="music-caption">let the music understand you.</p>
                <div class="music-bars">
                    <span></span><span></span><span></span>
                    <span></span><span></span><span></span>
                    <span></span><span></span><span></span>
                </div>
            </div>
            <span class="deco deco-one">✿</span>
            <span class="deco deco-two">♡</span>
            <span class="deco deco-three">✧</span>
        </div>

    </section>


    <!-- Mood Section -->
    <section class="mood-section" id="moods">

        <div class="section-heading">
            <p class="eyebrow">LET'S FIND YOUR SOUND</p>
            <h2>How's your heart today?</h2>
            <p>Pick a feeling, we'll find the music.</p>
        </div>

        <div class="mood-grid">

            <?php foreach ($allMoods as $item): ?>

                <div class="mood-card">

                    <div class="mood-icon">
                        <?php
                        $icons = [
                            'Happy' => '☀',
                            'Sad' => '☁',
                            'Calm' => '☾',
                            'Angry' => '♡',
                            'Romantic' => '୨୧',
                            'Energetic' => '✧'
                        ];

                        echo $icons[$item['nama_mood']] ?? '♡';
                        ?>
                    </div>

                    <h3>
                        <?= htmlspecialchars($item['nama_mood']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($item['deskripsi']) ?>
                    </p>

                    <a href="#" class="mood-link">Explore songs →</a>

                </div>

            <?php endforeach; ?>

        </div>

    </section>


    <!-- Journal Invitation -->
    <section class="journal-banner">

        <div>
            <p class="eyebrow">A SPACE JUST FOR YOU</p>
            <h2>Dear diary, what's on your mind?</h2>
            <p>
                Write your little stories, save your feelings,
                and let music be part of your memories.
            </p>
        </div>

        <a href="#" class="journal-button">Write a little note ♡</a>

    </section>


    <!-- Footer -->
    <footer>
        <h2>meloire ♡</h2>
        <p>made for every little feeling.</p>
        <p class="copyright">© 2026 Meloire. Made with love.</p>
    </footer>

</body>
</html>
