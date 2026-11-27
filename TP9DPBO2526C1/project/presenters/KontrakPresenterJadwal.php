<?php

interface KontrakPresenterJadwal
{
    public function tampilkanJadwal(): string;
    public function tampilkanFormJadwal(?int $id = null): string;
    public function tambahJadwal(int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): void;
    public function ubahJadwal(int $id, int $kandidatId, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status): void;
    public function hapusJadwal(int $id): void;
}
