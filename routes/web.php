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
        'password' => 'required'
    ]);

    if ($request->password === config('app.editor_password')) {
        session(['editor_auth' => true]);
        return redirect()->route('kegiatan.create');
    }

    return back()->with('error', 'Password editor salah');
});

Route::get('/editor/logout', function () {
    session()->forget('editor_auth');
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
