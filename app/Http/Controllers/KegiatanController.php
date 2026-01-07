<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Kegiatan;   
use App\Models\Anggota;
use App\Models\JenisKegiatan;
// use Illuminate\Support\Facades\Storage;


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

        // Upload langsung ke public/surat_tugas
        if ($request->hasFile('surat_tugas')) {
            $file = $request->file('surat_tugas');

            $filename = time() . '-' . Str::slug(pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            )) . '.pdf';

            $destination = public_path('surat_tugas');

            // pastikan folder ada
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            // simpan path relatif ke DB
            $validated['surat_tugas'] = 'surat_tugas/' . $filename;
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

        if ($request->hasFile('surat_tugas')) {

            // hapus file lama
            if ($kegiatan->surat_tugas) {
                $oldPath = public_path($kegiatan->surat_tugas);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('surat_tugas');

            $filename = time() . '-' . Str::slug(pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            )) . '.pdf';

            $destination = public_path('surat_tugas');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);

            $validated['surat_tugas'] = 'surat_tugas/' . $filename;
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
        // Hapus file surat tugas di public/
        if ($kegiatan->surat_tugas) {
            $path = public_path($kegiatan->surat_tugas);

            if (File::exists($path)) {
                File::delete($path);
            }
        }

        // Lepas relasi anggota
        $kegiatan->anggota()->detach();

        // Hapus data kegiatan
        $kegiatan->delete();

        // ✅ JIKA REQUEST AJAX (fetch)
        if (request()->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Kegiatan berhasil dihapus'
            ]);
        }

        // ✅ JIKA BUKAN AJAX
        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

}
