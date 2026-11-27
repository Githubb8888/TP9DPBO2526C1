<?php
require_once __DIR__ . '/JadwalInterview.php';

interface KontrakModelJadwal
{
    public function getAllJadwal(): array;
    public function getJadwalById(int $id): ?JadwalInterview;
    public function getOpsiKandidat(): array;
    public function addJadwal(int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): bool;
    public function updateJadwal(int $id, int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): bool;
    public function deleteJadwal(int $id): bool;
}
