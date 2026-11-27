<?php

class ViewJadwal implements KontrakViewJadwal
{
    private string $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../template/';
    }

    private function esc($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    public function renderDaftar(string $rowsHtml, int $totalData): string
    {
        $html = file_get_contents($this->dir . 'skin_jadwal.html');
        return strtr($html, [
            '{{TABLE_ROWS}}' => $rowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ]);
    }

    public function renderForm(string $judul, string $mode, array $formData, string $opsiKandidatHtml, string $opsiStatusHtml): string
    {
        $html = file_get_contents($this->dir . 'form_jadwal.html');
        return strtr($html, [
            '{{JUDUL}}' => $this->esc($judul),
            '{{MODE}}' => $this->esc($mode),
            '{{ID}}' => $this->esc($formData['id'] ?? ''),
            '{{PEWAWANCARA}}' => $this->esc($formData['pewawancara'] ?? ''),
            '{{TANGGAL}}' => $this->esc($formData['tanggal'] ?? ''),
            '{{JAM}}' => $this->esc($formData['jam'] ?? ''),
            '{{LOKASI}}' => $this->esc($formData['lokasi'] ?? ''),
            '{{OPSI_KANDIDAT}}' => $opsiKandidatHtml,
            '{{OPSI_STATUS}}' => $opsiStatusHtml,
        ]);
    }
}
