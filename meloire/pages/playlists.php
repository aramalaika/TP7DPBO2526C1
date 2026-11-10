
<?php

require_once '../config/database.php';
require_once '../classes/Playlist.php';

$database = new Database();
$db = $database->connect();

$playlist = new Playlist($db);

// Get all available songs
$songQuery = $db->prepare("SELECT * FROM songs ORDER BY judul_lagu ASC");
$songQuery->execute();
$songs = $songQuery->fetchAll();

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {

        $nama = trim($_POST['nama_playlist'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($nama !== '') {

            if ($action === 'create') {
                $playlist->create($nama, $deskripsi);
            } else {
                $id = $_POST['id_playlist'] ?? 0;
                $playlist->update($id, $nama, $deskripsi);
            }
        }

    } elseif ($action === 'delete') {

        $id = $_POST['id_playlist'] ?? 0;
        $playlist->delete($id);

    } elseif ($action === 'add_song') {

        $idPlaylist = $_POST['id_playlist'] ?? 0;
        $idSong = $_POST['id_song'] ?? 0;

        if ($idPlaylist && $idSong) {
            try {
                $playlist->addSong($idPlaylist, $idSong);
            } catch (PDOException $error) {
                // This song might already be in the playlist ♡
            }
        }

    } elseif ($action === 'remove_song') {

        $id = $_POST['playlist_song_id'] ?? 0;
        $playlist->removeSong($id);
    }

    header("Location: playlists.php");
    exit;
}

// Check whether a playlist is being edited
$editPlaylist = null;

if (isset($_GET['edit'])) {
    $editPlaylist = $playlist->getById($_GET['edit']);
}

// Get all playlists
$allPlaylists = $playlist->getAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Playlists — Meloire</title>

    <link rel="stylesheet" href="../assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <a href="../index.php" class="logo">meloire<span> ♡</span></a>

        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="songs.php">Discover</a>
            <a href="journals.php">My Diary</a>
            <a href="playlists.php" class="active">Playlists</a>
            <a href="moods.php">Moods</a>
        </div>
    </nav>


    <section class="mood-section">

        <div class="section-heading">
            <p class="eyebrow">YOUR VERY OWN SOUNDTRACK ♡</p>
            <h2>Little playlists, big feelings.</h2>
            <p>Collect the songs that feel like you.</p>
        </div>


        <!-- Create or edit playlist -->
        <div class="journal-form">

            <h2>
                <?= $editPlaylist ? 'Edit your playlist ♡' : 'Create a little playlist ♡' ?>
            </h2>

            <form method="POST" action="playlists.php">

                <?php if ($editPlaylist): ?>

                    <input type="hidden" name="action" value="update">

                    <input type="hidden" name="id_playlist"
                           value="<?= htmlspecialchars($editPlaylist['id_playlist']) ?>">

                <?php else: ?>

                    <input type="hidden" name="action" value="create">

                <?php endif; ?>


                <label>Give your playlist a name</label>

                <input
                    type="text"
                    name="nama_playlist"
                    placeholder="songs for late night drives..."
                    value="<?= htmlspecialchars($editPlaylist['nama_playlist'] ?? '') ?>"
                    required
                >


                <label>A little description</label>

                <textarea
                    name="deskripsi"
                    rows="3"
                    placeholder="What's the feeling behind this playlist?"
                ><?= htmlspecialchars($editPlaylist['deskripsi'] ?? '') ?></textarea>


                <button type="submit" class="hero-button">
                    <?= $editPlaylist ? 'Save changes ♡' : 'Create playlist ♡' ?>
                </button>


                <?php if ($editPlaylist): ?>
                    <a href="playlists.php" class="cancel-link">Cancel</a>
                <?php endif; ?>

            </form>

        </div>


        <!-- Playlist collection -->
        <div class="journal-list">

            <div class="section-heading">
                <p class="eyebrow">MADE BY YOU</p>
                <h2>Your music collection</h2>
            </div>


            <?php if (count($allPlaylists) > 0): ?>

                <div class="journal-grid">

                    <?php foreach ($allPlaylists as $item): ?>

                        <?php $playlistSongs = $playlist->getSongs($item['id_playlist']); ?>

                        <div class="journal-entry">

                            <div class="mood-icon">♫</div>

                            <h3>
                                <?= htmlspecialchars($item['nama_playlist']) ?>
                            </h3>

                            <p class="journal-story">
                                <?= htmlspecialchars($item['deskripsi'] ?? '') ?>
                            </p>

                            <p class="journal-date">
                                <?= date('d M Y', strtotime($item['tanggal_dibuat'])) ?>
                            </p>


                            <div class="playlist-song-list">

                                <h4>Songs in this playlist ♡</h4>

                                <?php if (count($playlistSongs) > 0): ?>

                                    <?php foreach ($playlistSongs as $songItem): ?>

                                        <div class="playlist-song">

                                            <div>
                                                <strong>
                                                    <?= htmlspecialchars($songItem['judul_lagu']) ?>
                                                </strong>

                                                <p>
                                                    <?= htmlspecialchars($songItem['artis']) ?>
                                                </p>
                                            </div>

                                            <form method="POST" action="playlists.php">

                                                <input type="hidden" name="action" value="remove_song">

                                                <input type="hidden" name="playlist_song_id"
                                                       value="<?= $songItem['playlist_song_id'] ?>">

                                                <button type="submit" class="remove-song">
                                                    ×
                                                </button>

                                            </form>

                                        </div>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <p class="empty-song">
                                        No songs yet. Let's add some music ♡
                                    </p>

                                <?php endif; ?>

                            </div>


                            <!-- Add song -->
                            <?php if (count($songs) > 0): ?>

                                <form method="POST" action="playlists.php" class="add-song-form">

                                    <input type="hidden" name="action" value="add_song">

                                    <input type="hidden" name="id_playlist"
                                           value="<?= $item['id_playlist'] ?>">

                                    <select name="id_song" required>

                                        <option value="">Choose a song</option>

                                        <?php foreach ($songs as $songOption): ?>

                                            <option value="<?= $songOption['id_song'] ?>">
                                                <?= htmlspecialchars($songOption['judul_lagu']) ?>
                                                — <?= htmlspecialchars($songOption['artis']) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <button type="submit">+ Add song</button>

                                </form>

                            <?php else: ?>

                                <p class="empty-song">
                                    Add some songs in Discover first ♡
                                </p>

                            <?php endif; ?>


                            <!-- Edit and delete playlist -->
                            <div class="journal-actions">

                                <a href="playlists.php?edit=<?= $item['id_playlist'] ?>">
                                    Edit playlist
                                </a>

                                <form method="POST" action="playlists.php"
                                      onsubmit="return confirm('Delete this playlist?')">

                                    <input type="hidden" name="action" value="delete">

                                    <input type="hidden" name="id_playlist"
                                           value="<?= $item['id_playlist'] ?>">

                                    <button type="submit">Delete playlist</button>

                                </form>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-journal">
                    <span>୨୧</span>
                    <h3>Your playlist era starts here.</h3>
                    <p>Create your first playlist and give your feelings a soundtrack ♡</p>
                </div>

            <?php endif; ?>

        </div>

    </section>

</body>
</html>
