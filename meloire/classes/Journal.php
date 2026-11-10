
<?php

class Journal {
    private $db;
    private $table = "journals";

    public function __construct($connection) {
        $this->db = $connection;
    }

    // Read all the little stories ♡
    public function getAll() {
        $sql = "SELECT journals.*, moods.nama_mood
                FROM {$this->table}
                LEFT JOIN moods ON journals.id_mood = moods.id_mood
                ORDER BY tanggal DESC, id_journal DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Find a story to read or edit
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_journal = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // Save a new memory
    public function create($judul, $cerita, $tanggal, $idMood) {
        $sql = "INSERT INTO {$this->table}
                (judul_journal, cerita, tanggal, id_mood)
                VALUES (:judul, :cerita, :tanggal, :idMood)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':judul' => $judul,
            ':cerita' => $cerita,
            ':tanggal' => $tanggal,
            ':idMood' => $idMood
        ]);
    }

    // Edit a memory
    public function update($id, $judul, $cerita, $tanggal, $idMood) {
        $sql = "UPDATE {$this->table}
                SET judul_journal = :judul,
                    cerita = :cerita,
                    tanggal = :tanggal,
                    id_mood = :idMood
                WHERE id_journal = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':judul' => $judul,
            ':cerita' => $cerita,
            ':tanggal' => $tanggal,
            ':idMood' => $idMood
        ]);
    }

    // Let go of a memory
    public function delete($id) {
        $sql = "DELETE FROM {$this->table}
                WHERE id_journal = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
