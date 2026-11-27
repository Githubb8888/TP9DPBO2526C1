<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelJadwal.php';

class TabelJadwal extends DB implements KontrakModelJadwal
{
    private const SELECT_JOIN = "SELECT j.*, k.nama AS namaKandidat
                                 FROM jadwal_interview j
                                 JOIN kandidat k ON k.id = j.kandidat_id";

    private function mapRowToJadwal(array $row): JadwalInterview
    {
        return new JadwalInterview(
            (int)$row['id'],
            (int)$row['kandidat_id'],
            $row['namaKandidat'],
            $row['pewawancara'],
            $row['tanggal'],
            $row['jam'],
            $row['lokasi'],
            $row['status']
        );
    }

    public function getAllJadwal(): array
    {
        $this->executeQuery(self::SELECT_JOIN . " ORDER BY j.tanggal ASC, j.jam ASC, j.id ASC");
        $list = [];
        foreach ($this->getAllResult() as $row) {
            $list[] = $this->mapRowToJadwal($row);
        }
        return $list;
    }

    public function getJadwalById(int $id): ?JadwalInterview
    {
        $this->executeQuery(self::SELECT_JOIN . " WHERE j.id = :id LIMIT 1", [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToJadwal($rows[0]);
    }

    public function getOpsiKandidat(): array
    {
        $this->executeQuery("SELECT id, nama FROM kandidat ORDER BY nama ASC");
        return $this->getAllResult();
    }

    public function addJadwal(int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): bool
    {
        return $this->executeQuery(
            "INSERT INTO jadwal_interview (kandidat_id, pewawancara, tanggal, jam, lokasi, status)
             VALUES (:kandidat, :pewawancara, :tanggal, :jam, :lokasi, :status)",
            [
                ':kandidat' => $kandidatId,
                ':pewawancara' => $pewawancara,
                ':tanggal' => $tanggal,
                ':jam' => $jam,
                ':lokasi' => $lokasi,
                ':status' => $status,
            ]
        );
    }

    public function updateJadwal(int $id, int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): bool
    {
        return $this->executeQuery(
            "UPDATE jadwal_interview
             SET kandidat_id = :kandidat, pewawancara = :pewawancara, tanggal = :tanggal,
                 jam = :jam, lokasi = :lokasi, status = :status
             WHERE id = :id",
            [
                ':kandidat' => $kandidatId,
                ':pewawancara' => $pewawancara,
                ':tanggal' => $tanggal,
                ':jam' => $jam,
                ':lokasi' => $lokasi,
                ':status' => $status,
                ':id' => $id,
            ]
        );
    }

    public function deleteJadwal(int $id): bool
    {
        return $this->executeQuery("DELETE FROM jadwal_interview WHERE id = :id", [':id' => $id]);
    }
}
