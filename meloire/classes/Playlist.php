
<?php

class Playlist {
    private $conn;
    private $table = "playlists";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all playlists
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY tanggal_dibuat DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get playlist by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_playlist = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create playlist
    public function create($nama, $deskripsi) {
        $query = "INSERT INTO " . $this->table . "
                  (nama_playlist, deskripsi)
                  VALUES (:nama, :deskripsi)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama' => $nama,
            ':deskripsi' => $deskripsi
        ]);
    }

    // Update playlist
    public function update($id, $nama, $deskripsi) {
        $query = "UPDATE " . $this->table . "
                  SET nama_playlist = :nama,
                      deskripsi = :deskripsi
                  WHERE id_playlist = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama' => $nama,
            ':deskripsi' => $deskripsi,
            ':id' => $id
        ]);
    }

    // Delete playlist
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . "
                  WHERE id_playlist = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }

    // Add song to playlist
    public function addSong($idPlaylist, $idSong) {
        $query = "INSERT INTO playlist_songs
                  (id_playlist, id_song)
                  VALUES (:id_playlist, :id_song)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id_playlist' => $idPlaylist,
            ':id_song' => $idSong
        ]);
    }

    // Get songs inside a playlist
    public function getSongs($idPlaylist) {
        $query = "SELECT songs.*, playlist_songs.id AS playlist_song_id
                  FROM playlist_songs
                  INNER JOIN songs
                  ON playlist_songs.id_song = songs.id_song
                  WHERE playlist_songs.id_playlist = :id
                  ORDER BY songs.judul_lagu ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $idPlaylist]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Remove song from playlist
    public function removeSong($id) {
        $query = "DELETE FROM playlist_songs WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }
}
