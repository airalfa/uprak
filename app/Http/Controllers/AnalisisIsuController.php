<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AnalisisIsuController extends Controller
{
    public function index(): View
    {
        $data = [
            'halaman'   => 2,
            'judul'     => 'Isu Suku Bunga',
            'subjudul'  => 'Analisis Kritis dalam Ilmu Sosial',
            'isu'       => 'Penggunaan suku bunga dalam nilai Pancasila dan kesejahteraan rakyat.',
            'pro' => [
                'judul' => 'Argumentasi Pro (Komersial / Bank)',
                'poin'  => [
                    'Mendorong daya tarik tabungan',
                    'Menyesuaikan nilai uang dengan tingkat inflasi',
                    'Mengurangi risiko kredit',
                ],
            ],
            'kontra' => [
                'judul' => 'Argumentasi Kontra (Nilai Sosial)',
                'poin'  => [
                    'Memberatkan pelaku usaha kecil',
                    'Potensi ketidakadilan dalam bunga',
                    'Kesenjangan sosial semakin melebar',
                ],
            ],
            'kesimpulan' => [
                'judul' => 'Kesimpulan / Rekomendasi',
                'teks'  => 'Perlu kebijakan keseimbangan antara kepentingan ekonomi perbankan dan nilai keadilan sosial agar sistem keuangan tidak memberatkan masyarakat kecil.',
            ],
        ];

        return view('analisis.suku-bunga', compact('data'));
    }
}