<?php

class ViewKandidat implements KontrakView
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
        $html = file_get_contents($this->dir . 'skin.html');
        return strtr($html, [
            '{{TABLE_ROWS}}' => $rowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ]);
    }

    public function renderForm(string $judul, string $mode, array $formData): string
    {
        $html = file_get_contents($this->dir . 'form.html');
        return strtr($html, [
            '{{JUDUL}}' => $this->esc($judul),
            '{{MODE}}' => $this->esc($mode),
            '{{ID}}' => $this->esc($formData['id'] ?? ''),
            '{{NAMA}}' => $this->esc($formData['nama'] ?? ''),
            '{{POSISI}}' => $this->esc($formData['posisi'] ?? ''),
            '{{DOMISILI}}' => $this->esc($formData['domisili'] ?? ''),
            '{{SKOR_TES}}' => $this->esc($formData['skorTes'] ?? ''),
            '{{PENGALAMAN}}' => $this->esc($formData['pengalamanTahun'] ?? ''),
        ]);
    }
}
