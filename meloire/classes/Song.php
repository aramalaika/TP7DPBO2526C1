
<?php

class Song {
    private $db;
    private $table = "songs";

    public function __construct($connection) {
        $this->db = $connection;
    }

    // See all the songs in our little collection ♡
    public function getAll() {
        $sql = "SELECT songs.*, moods.nama_mood
                FROM {$this->table}
                LEFT JOIN moods ON songs.id_mood = moods.id_mood
                ORDER BY songs.id_song DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Find a song by its ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table}
                WHERE id_song = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // Add a new song to the collection
    public function create($judul, $artis, $genre, $link, $idMood) {
        $sql = "INSERT INTO {$this->table}
                (judul_lagu, artis, genre, link_lagu, id_mood)
                VALUES (:judul, :artis, :genre, :link, :idMood)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':judul' => $judul,
            ':artis' => $artis,
            ':genre' => $genre,
            ':link' => $link,
            ':idMood' => $idMood
        ]);
    }

    // Update song details
    public function update($id, $judul, $artis, $genre, $link, $idMood) {
        $sql = "UPDATE {$this->table}
                SET judul_lagu = :judul,
                    artis = :artis,
                    genre = :genre,
                    link_lagu = :link,
                    id_mood = :idMood
                WHERE id_song = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':judul' => $judul,
            ':artis' => $artis,
            ':genre' => $genre,
            ':link' => $link,
            ':idMood' => $idMood
        ]);
    }

    // Remove a song from the collection
    public function delete($id) {
        $sql = "DELETE FROM {$this->table}
                WHERE id_song = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
