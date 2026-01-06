<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;   
use App\Models\Anggota;
use App\Models\JenisKegiatan;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->query('view', 'card');

        $data = Kegiatan::with(['jenis', 'anggota'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->paginate(32);

        return view('kegiatan.index', compact('data', 'view'));
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
            'nama_kegiatan'     => 'required|string',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'lokasi'            => 'required|string',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'link_drive'        => 'nullable|url',
            'link_website'      => 'nullable|url',
            'catatan'           => 'nullable|string',
            'surat_tugas'       => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Upload PDF ke storage/app/public/surat_tugas
        if ($request->hasFile('surat_tugas')) {
            $validated['surat_tugas'] = $request
                ->file('surat_tugas')
                ->store('surat_tugas', 'public');
        }

        $kegiatan = Kegiatan::create($validated);

        if ($request->has('anggota')) {
            $kegiatan->anggota()->sync($request->anggota);
        }

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil disimpan!');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $jenisKegiatan = JenisKegiatan::all();
        $anggota = Anggota::all();

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
            'nama_kegiatan'     => 'required|string',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'lokasi'            => 'required|string',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'link_drive'        => 'nullable|url',
            'link_website'      => 'nullable|url',
            'catatan'           => 'nullable|string',
            'surat_tugas'       => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Upload PDF baru (hapus yang lama)
        if ($request->hasFile('surat_tugas')) {

            if (
                $kegiatan->surat_tugas &&
                Storage::disk('public')->exists($kegiatan->surat_tugas)
            ) {
                Storage::disk('public')->delete($kegiatan->surat_tugas);
            }

            $validated['surat_tugas'] = $request
                ->file('surat_tugas')
                ->store('surat_tugas', 'public');
        }

        $kegiatan->update($validated);

        if ($request->has('anggota')) {
            $kegiatan->anggota()->sync($request->anggota);
        } else {
            $kegiatan->anggota()->detach();
        }

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

   public function destroy(Kegiatan $kegiatan)
    {
        if (
            $kegiatan->surat_tugas &&
            Storage::disk('public')->exists($kegiatan->surat_tugas)
        ) {
            Storage::disk('public')->delete($kegiatan->surat_tugas);
        }

        $kegiatan->anggota()->detach();
        $kegiatan->delete();

        // ✅ JIKA REQUEST AJAX (fetch)
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kegiatan berhasil dihapus'
            ]);
        }

        // ✅ JIKA BUKAN AJAX (form biasa)
        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

}
