
<?php

require_once '../config/database.php';
require_once '../classes/Journal.php';

$database = new Database();
$db = $database->connect();

$journal = new Journal($db);

// Get mood options for the form
$moodQuery = $db->prepare("SELECT * FROM moods ORDER BY nama_mood ASC");
$moodQuery->execute();
$moods = $moodQuery->fetchAll();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {

        $judul = trim($_POST['judul_journal'] ?? '');
        $cerita = trim($_POST['cerita'] ?? '');
        $tanggal = $_POST['tanggal'] ?? '';
        $idMood = !empty($_POST['id_mood']) ? $_POST['id_mood'] : null;

        if ($judul !== '' && $cerita !== '' && $tanggal !== '') {

            if ($action === 'create') {
                $journal->create($judul, $cerita, $tanggal, $idMood);
            } else {
                $id = $_POST['id_journal'] ?? 0;
                $journal->update($id, $judul, $cerita, $tanggal, $idMood);
            }
        }

    } elseif ($action === 'delete') {

        $id = $_POST['id_journal'] ?? 0;
        $journal->delete($id);
    }

    // Refresh the page so the form won't submit twice
    header("Location: journals.php");
    exit;
}

// Check if the user wants to edit a journal
$editJournal = null;

if (isset($_GET['edit'])) {
    $editJournal = $journal->getById($_GET['edit']);
}

// Get all journal entries
$allJournals = $journal->getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Diary — Meloire</title>

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
            <a href="journals.php" class="active">My Diary</a>
            <a href="playlists.php">Playlists</a>
            <a href="moods.php">Moods</a>
        </div>
    </nav>


    <section class="mood-section">

        <div class="section-heading">
            <p class="eyebrow">A LITTLE SPACE FOR YOUR THOUGHTS ♡</p>
            <h2>Dear diary...</h2>
            <p>Some feelings are easier to write than to say.</p>
        </div>


        <!-- Journal Form -->
        <div class="journal-form">

            <h2>
                <?= $editJournal ? 'Edit your little story ♡' : 'Write a little story ♡' ?>
            </h2>

            <form method="POST" action="journals.php">

                <?php if ($editJournal): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_journal"
                           value="<?= htmlspecialchars($editJournal['id_journal']) ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>

                <label>What's your story called?</label>
                <input
                    type="text"
                    name="judul_journal"
                    placeholder="A day worth remembering..."
                    value="<?= htmlspecialchars($editJournal['judul_journal'] ?? '') ?>"
                    required
                >

                <label>Tell me everything ♡</label>
                <textarea
                    name="cerita"
                    rows="5"
                    placeholder="Write whatever is on your mind..."
                    required
                ><?= htmlspecialchars($editJournal['cerita'] ?? '') ?></textarea>

                <label>When did it happen?</label>
                <input
                    type="date"
                    name="tanggal"
                    value="<?= htmlspecialchars($editJournal['tanggal'] ?? date('Y-m-d')) ?>"
                    required
                >

                <label>How were you feeling?</label>
                <select name="id_mood">

                    <option value="">Choose your mood</option>

                    <?php foreach ($moods as $item): ?>

                        <option
                            value="<?= $item['id_mood'] ?>"
                            <?= isset($editJournal['id_mood']) &&
                                $editJournal['id_mood'] == $item['id_mood']
                                ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($item['nama_mood']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button type="submit" class="hero-button">
                    <?= $editJournal ? 'Save changes ♡' : 'Save my story ♡' ?>
                </button>

                <?php if ($editJournal): ?>
                    <a href="journals.php" class="cancel-link">Cancel</a>
                <?php endif; ?>

            </form>

        </div>


        <!-- Saved Journal Entries -->
        <div class="journal-list">

            <div class="section-heading">
                <p class="eyebrow">LITTLE MEMORIES, BIG FEELINGS</p>
                <h2>Your diary entries</h2>
            </div>

            <?php if (count($allJournals) > 0): ?>

                <div class="journal-grid">

                    <?php foreach ($allJournals as $item): ?>

                        <div class="journal-entry">

                            <p class="journal-date">
                                <?= date('d M Y', strtotime($item['tanggal'])) ?>
                            </p>

                            <h3>
                                <?= htmlspecialchars($item['judul_journal']) ?>
                            </h3>

                            <p class="journal-mood">
                                Feeling: <?= htmlspecialchars($item['nama_mood'] ?? 'No mood selected') ?>
                            </p>

                            <p class="journal-story">
                                <?= nl2br(htmlspecialchars($item['cerita'])) ?>
                            </p>

                            <div class="journal-actions">

                                <a href="journals.php?edit=<?= $item['id_journal'] ?>">
                                    Edit
                                </a>

                                <form method="POST" action="journals.php"
                                      onsubmit="return confirm('Delete this little memory?')">

                                    <input type="hidden" name="action" value="delete">

                                    <input type="hidden" name="id_journal"
                                           value="<?= $item['id_journal'] ?>">

                                    <button type="submit">Delete</button>

                                </form>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-journal">
                    <span>୨୧</span>
                    <h3>No stories just yet...</h3>
                    <p>Your little memories will find a home here ♡</p>
                </div>

            <?php endif; ?>

        </div>

    </section>

</body>
</html>
