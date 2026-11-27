<?php

interface KontrakView
{
    public function renderDaftar(string $rowsHtml, int $totalData): string;
    public function renderForm(string $judul, string $mode, array $formData): string;
}
