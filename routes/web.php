<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\KegiatanController;

/*
|--------------------------------------------------------------------------
| Public (tanpa editor)
|--------------------------------------------------------------------------
*/
Route::get('/kegiatan', [KegiatanController::class, 'index'])
    ->name('kegiatan.index');

/*
|--------------------------------------------------------------------------
| Editor Login
|--------------------------------------------------------------------------
*/
Route::get('/editor/login', function () {
    return view('editor.login');
})->name('editor.login');

Route::post('/editor/login', function (Request $request) {

    $request->validate([
        'password' => 'required|digits:4'
    ]);

    // 🔒 cek lock 15 menit
    if (session('editor_lock_until') && now()->lessThan(session('editor_lock_until'))) {
        return back()->withErrors([
            'password' => 'Terlalu banyak percobaan. Coba lagi 15 menit.'
        ]);
    }

    $attempts = session('editor_attempts', 0);

    if ($request->password !== config('app.editor_password')) {

        session(['editor_attempts' => $attempts + 1]);

        // ❌ limit 3x
        if ($attempts + 1 >= 3) {
            session(['editor_lock_until' => now()->addMinutes(15)]);
        }

        return back()->withErrors([
            'password' => 'Password editor salah'
        ]);
    }

    // ✅ sukses login
    session([
        'editor_auth' => true,
        'editor_attempts' => 0,
        'editor_lock_until' => null,
    ]);

    return redirect()->route('kegiatan.create');
});

/*
|--------------------------------------------------------------------------
| Editor Logout
|--------------------------------------------------------------------------
*/
Route::get('/editor/logout', function () {
    session()->forget([
        'editor_auth',
        'editor_attempts',
        'editor_lock_until'
    ]);

    return redirect()->route('kegiatan.index');
})->name('editor.logout');

/*
|--------------------------------------------------------------------------
| Editor Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('editor.auth')->group(function () {

    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])
        ->name('kegiatan.create');

    Route::post('/kegiatan', [KegiatanController::class, 'store'])
        ->name('kegiatan.store');

    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])
        ->name('kegiatan.edit');

    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])
        ->name('kegiatan.update');

    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])
        ->name('kegiatan.destroy');
});
