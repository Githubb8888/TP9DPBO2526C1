<?php

interface KontrakViewJadwal
{
    public function renderDaftar(string $rowsHtml, int $totalData): string;
    public function renderForm(string $judul, string $mode, array $formData, string $opsiKandidatHtml, string $opsiStatusHtml): string;
}
