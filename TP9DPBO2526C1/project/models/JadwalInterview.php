<?php

class JadwalInterview
{
    private int $id;
    private int $kandidatId;
    private string $namaKandidat;
    private string $pewawancara;
    private string $tanggal;
    private string $jam;
    private string $lokasi;
    private string $status;

    public function __construct(int $id, int $kandidatId, string $namaKandidat, string $pewawancara, string $tanggal, string $jam, string $lokasi, string $status)
    {
        $this->id = $id;
        $this->kandidatId = $kandidatId;
        $this->namaKandidat = $namaKandidat;
        $this->pewawancara = $pewawancara;
        $this->tanggal = $tanggal;
        $this->jam = $jam;
        $this->lokasi = $lokasi;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getKandidatId(): int { return $this->kandidatId; }
    public function getNamaKandidat(): string { return $this->namaKandidat; }
    public function getPewawancara(): string { return $this->pewawancara; }
    public function getTanggal(): string { return $this->tanggal; }
    public function getJam(): string { return $this->jam; }
    public function getLokasi(): string { return $this->lokasi; }
    public function getStatus(): string { return $this->status; }
}
