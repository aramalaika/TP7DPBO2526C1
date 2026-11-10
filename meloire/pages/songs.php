
<?php
require_once "../config/database.php";
require_once "../classes/Song.php";

// Koneksi database
$database = new Database();
$db = $database->connect();

// Membuat objek Song
$song = new Song($db);

// Mengambil daftar mood untuk dropdown
$moodQuery = $db->prepare("SELECT * FROM moods ORDER BY nama_mood ASC");
$moodQuery->execute();
$moods = $moodQuery->fetchAll(PDO::FETCH_ASSOC);

// Proses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "create") {
        $song->create(
            $_POST["judul_lagu"],
            $_POST["artis"],
            $_POST["genre"],
            $_POST["link_lagu"],
            !empty($_POST["id_mood"]) ? $_POST["id_mood"] : null
        );
    }

    if ($action == "update") {
        $song->update(
            $_POST["id_song"],
            $_POST["judul_lagu"],
            $_POST["artis"],
            $_POST["genre"],
            $_POST["link_lagu"],
            !empty($_POST["id_mood"]) ? $_POST["id_mood"] : null
        );
    }

    if ($action == "delete") {
        $song->delete($_POST["id_song"]);
    }

    header("Location: songs.php");
    exit;
}

// Mengambil data lagu yang akan diedit
$editSong = null;

if (isset($_GET["edit"])) {
    $editSong = $song->getById($_GET["edit"]);
}

// Mengambil seluruh lagu
$allSongs = $song->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover | Meloire</title>

    <link rel="stylesheet" href="../assets/style.css">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<nav class="navbar">
    <div class="logo">meloire ♡</div>

    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="songs.php" class="active">Discover</a>
        <a href="journals.php">My Diary</a>
        <a href="playlists.php">Playlists</a>
        <a href="moods.php">Moods</a>
    </div>
</nav>

<main class="page-container">

    <section class="page-heading">
        <p class="eyebrow">YOUR PERSONAL SOUNDTRACK</p>
        <h1>Discover Songs ♡</h1>
        <p>Little songs for every version of you.</p>
    </section>

    <!-- FORM TAMBAH / EDIT LAGU -->
    <section class="journal-form song-form">

        <h2>
            <?= $editSong ? "Edit your song ♡" : "Add a new song ♡"; ?>
        </h2>

        <form method="POST">

            <input type="hidden" name="action"
                value="<?= $editSong ? 'update' : 'create'; ?>">

            <?php if ($editSong): ?>
                <input type="hidden" name="id_song"
                    value="<?= htmlspecialchars($editSong['id_song']); ?>">
            <?php endif; ?>

            <label>Song Title</label>
            <input type="text" name="judul_lagu"
                placeholder="e.g. About You"
                value="<?= htmlspecialchars($editSong['judul_lagu'] ?? ''); ?>"
                required>

            <label>Artist</label>
            <input type="text" name="artis"
                placeholder="e.g. The 1975"
                value="<?= htmlspecialchars($editSong['artis'] ?? ''); ?>"
                required>

            <label>Genre</label>
            <input type="text" name="genre"
                placeholder="e.g. Indie Pop"
                value="<?= htmlspecialchars($editSong['genre'] ?? ''); ?>">

            <label>Song Link</label>
            <input type="url" name="link_lagu"
                placeholder="Paste your Spotify or YouTube link"
                value="<?= htmlspecialchars($editSong['link_lagu'] ?? ''); ?>">

            <label>Choose a Mood</label>
            <select name="id_mood">
                <option value="">No specific mood</option>

                <?php foreach ($moods as $m): ?>
                    <option value="<?= $m['id_mood']; ?>"
                        <?= ($editSong && $editSong['id_mood'] == $m['id_mood']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($m['nama_mood']); ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <div class="song-form-actions">
                <button type="submit" class="pink-button">
                    <?= $editSong ? "Save Changes ♡" : "Add Song ♡"; ?>
                </button>

                <?php if ($editSong): ?>
                    <a href="songs.php" class="cancel-link">Cancel</a>
                <?php endif; ?>
            </div>

        </form>
    </section>

    <!-- DAFTAR LAGU -->
    <section class="song-list-section">

        <div class="section-heading">
            <p class="eyebrow">YOUR COLLECTION</p>
            <h2>All Songs</h2>
        </div>

        <?php if (count($allSongs) > 0): ?>

            <div class="song-grid">

                <?php foreach ($allSongs as $s): ?>

                    <div class="song-card">

                        <div class="song-icon">♫</div>

                        <div class="song-info">
                            <h3><?= htmlspecialchars($s['judul_lagu']); ?></h3>

                            <p><?= htmlspecialchars($s['artis']); ?></p>

                            <span class="song-genre">
                                <?= htmlspecialchars($s['genre'] ?? 'No genre'); ?>
                            </span>

                            <span class="song-mood">
                                ♡ <?= htmlspecialchars($s['nama_mood'] ?? 'No mood'); ?>
                            </span>
                        </div>

                        <?php if (!empty($s['link_lagu'])): ?>
                            <a href="<?= htmlspecialchars($s['link_lagu']); ?>"
                               target="_blank" rel="noopener noreferrer"
                               class="song-link">
                                Listen ♡
                            </a>
                        <?php endif; ?>

                        <div class="song-actions">

                            <a href="songs.php?edit=<?= $s['id_song']; ?>"
                               class="edit-link">Edit</a>

                            <form method="POST"
                                  onsubmit="return confirm('Delete this song?');">

                                <input type="hidden" name="action" value="delete">

                                <input type="hidden" name="id_song"
                                    value="<?= $s['id_song']; ?>">

                                <button type="submit" class="delete-button">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-journal">
                <p>No songs yet ♡</p>
                <span>Start adding songs to your collection!</span>
            </div>

        <?php endif; ?>

    </section>

</main>

<footer class="footer">
    made with love ♡ meloire
</footer>

</body>
</html>
