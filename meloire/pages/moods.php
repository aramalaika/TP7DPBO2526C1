
<?php

require_once "../config/database.php";
require_once "../classes/Mood.php";

// Koneksi database
$database = new Database();
$db = $database->connect();

// Membuat objek Mood
$mood = new Mood($db);

// Proses CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"];

    if ($action == "create") {
        $mood->create(
            $_POST["nama_mood"],
            $_POST["deskripsi"]
        );
    }

    if ($action == "update") {
        $mood->update(
            $_POST["id_mood"],
            $_POST["nama_mood"],
            $_POST["deskripsi"]
        );
    }

    if ($action == "delete") {
        $mood->delete($_POST["id_mood"]);
    }

    header("Location: moods.php");
    exit;
}

// Mengambil mood untuk diedit
$editMood = null;

if (isset($_GET["edit"])) {
    $editMood = $mood->getById($_GET["edit"]);
}

// Mengambil semua mood
$allMoods = $mood->getAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Moods | Meloire</title>

    <link rel="stylesheet" href="../assets/style.css">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<nav class="navbar">

    <div class="logo">meloire ♡</div>

    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="songs.php">Discover</a>
        <a href="journals.php">My Diary</a>
        <a href="playlists.php">Playlists</a>
        <a href="moods.php" class="active">Moods</a>
    </div>

</nav>

<main class="page-container">

    <section class="page-heading">

        <p class="eyebrow">A LITTLE SPACE FOR EVERY FEELING</p>

        <h1>Manage Your Moods ♡</h1>

        <p>Every feeling deserves its own little corner.</p>

    </section>


    <!-- FORM TAMBAH DAN EDIT MOOD -->

    <section class="journal-form mood-form">

        <h2>
            <?= $editMood ? "Edit your mood ♡" : "Create a new mood ♡"; ?>
        </h2>

        <form method="POST">

            <input
                type="hidden"
                name="action"
                value="<?= $editMood ? 'update' : 'create'; ?>"
            >

            <?php if ($editMood): ?>

                <input
                    type="hidden"
                    name="id_mood"
                    value="<?= htmlspecialchars($editMood['id_mood']); ?>"
                >

            <?php endif; ?>


            <label>Mood Name</label>

            <input
                type="text"
                name="nama_mood"
                placeholder="e.g. Happy, Romantic, Calm..."
                value="<?= htmlspecialchars($editMood['nama_mood'] ?? ''); ?>"
                required
            >


            <label>Description</label>

            <textarea
                name="deskripsi"
                rows="3"
                placeholder="Describe this little feeling..."
            ><?= htmlspecialchars($editMood['deskripsi'] ?? ''); ?></textarea>


            <div class="song-form-actions">

                <button type="submit" class="pink-button">

                    <?= $editMood ? "Save Changes ♡" : "Add Mood ♡"; ?>

                </button>

                <?php if ($editMood): ?>

                    <a href="moods.php" class="cancel-link">
                        Cancel
                    </a>

                <?php endif; ?>

            </div>

        </form>

    </section>


    <!-- DAFTAR MOOD -->

    <section class="song-list-section">

        <div class="section-heading">

            <p class="eyebrow">YOUR FEELING COLLECTION</p>

            <h2>All Moods</h2>

        </div>


        <?php if (count($allMoods) > 0): ?>

            <div class="mood-management-grid">

                <?php foreach ($allMoods as $m): ?>

                    <div class="mood-management-card">

                        <div class="mood-card-icon">♡</div>

                        <h3>
                            <?= htmlspecialchars($m['nama_mood']); ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($m['deskripsi'] ?? 'A feeling of its own.'); ?>
                        </p>


                        <div class="song-actions">

                            <a
                                href="moods.php?edit=<?= $m['id_mood']; ?>"
                                class="edit-link"
                            >
                                Edit
                            </a>


                            <form
                                method="POST"
                                onsubmit="return confirm('Delete this mood?');"
                            >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete"
                                >

                                <input
                                    type="hidden"
                                    name="id_mood"
                                    value="<?= $m['id_mood']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete-button"
                                >
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-journal">

                <p>No moods yet ♡</p>

                <span>Start creating your mood collection!</span>

            </div>

        <?php endif; ?>

    </section>

</main>


<footer class="footer">
    made with love ♡ meloire
</footer>

</body>
</html>
