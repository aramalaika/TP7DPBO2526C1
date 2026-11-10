
<?php

class Mood {
    private $db;
    private $table = "moods";

    public function __construct($connection) {
        $this->db = $connection;
    }

    // Ambil semua mood yang ada
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_mood ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Cari satu mood berdasarkan ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id_mood = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // Tambahkan mood baru
    public function create($nama, $deskripsi) {
        $sql = "INSERT INTO {$this->table} (nama_mood, deskripsi)
                VALUES (:nama, :deskripsi)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama' => $nama,
            ':deskripsi' => $deskripsi
        ]);
    }

    // Update mood yang sudah ada
    public function update($id, $nama, $deskripsi) {
        $sql = "UPDATE {$this->table}
                SET nama_mood = :nama, deskripsi = :deskripsi
                WHERE id_mood = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nama' => $nama,
            ':deskripsi' => $deskripsi
        ]);
    }

    // Hapus mood dari daftar
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id_mood = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
