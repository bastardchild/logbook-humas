<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;   
use App\Models\Anggota;
use App\Models\JenisKegiatan;


class KegiatanController extends Controller
{
    public function index()
    {
        $data = Kegiatan::with('anggota')->get();
        return view('kegiatan.index', compact('data'));
    }

    public function create()
    {
        $jenisKegiatan = JenisKegiatan::all();
        $anggota = Anggota::all();
        return view('kegiatan.create', compact('jenisKegiatan', 'anggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan'     => 'required',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'lokasi'            => 'required',
            'jenis_kegiatan_id' => 'required',
            'link_drive'        => 'nullable|url',
            'link_website'      => 'nullable|url',
            'catatan'           => 'nullable|string',
            'surat_tugas'       => 'nullable|file|mimes:pdf',
        ]);

        // Upload PDF
        if ($request->hasFile('surat_tugas')) {
            $validated['surat_tugas'] = $request->file('surat_tugas')->store('surat_tugas');
        }

        // Simpan kegiatan
        $kegiatan = Kegiatan::create($validated);

        // Sync anggota petugas
        if ($request->has('anggota')) {
            $kegiatan->anggota()->sync($request->anggota);
        }

        return redirect()->route('kegiatan.index')
                        ->with('success', 'Kegiatan berhasil disimpan!');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $jenisKegiatan = JenisKegiatan::all();
        $anggota = Anggota::all();

        // load relasi anggota
        $kegiatan->load('anggota');

        return view('kegiatan.edit', compact(
            'kegiatan',
            'jenisKegiatan',
            'anggota'
        ));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama_kegiatan'     => 'required',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'lokasi'            => 'required',
            'jenis_kegiatan_id' => 'required',
            'link_drive'        => 'nullable|url',
            'link_website'      => 'nullable|url',
            'catatan'           => 'nullable|string',
            'surat_tugas'       => 'nullable|file|mimes:pdf',
        ]);

        // Upload PDF baru (jika ada)
        if ($request->hasFile('surat_tugas')) {
            $validated['surat_tugas'] =
                $request->file('surat_tugas')->store('surat_tugas');
        }

        // Update kegiatan
        $kegiatan->update($validated);

        // Sync anggota
        if ($request->has('anggota')) {
            $kegiatan->anggota()->sync($request->anggota);
        } else {
            $kegiatan->anggota()->detach();
        }

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

}

