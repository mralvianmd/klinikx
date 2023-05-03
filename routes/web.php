<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordConfirmationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-op', function() {
    Artisan::call('optimize:clear');
    return redirect()->back()->with('warning', 'Optimize is cleared');
});

// ----------------------------------Begin Account Controller
Route::get('/signup', [RegisterController::class, 'show'])->name('signup');
Route::post('/signup-user', [RegisterController::class, 'handle'])->name('signup.post');
Route::get('/signin', [LoginController::class, 'show'])
    ->name('signin');
Route::post('/signin-user', [LoginController::class, 'handle'])
    // ->middleware('throttle:3,1')
    ->name('signin.post');
Route::get('/logout', [LogoutController::class, 'handle'])
    ->name('logout');
// ----------------------------------End Account Controller


Route::get('/', 'PagesController@index')->name('klinik');


// ----------------------------------Begin Hak Akses Controller
Route::get('/hakakses', 'HakaksesController@index')->name('kl.hakakses');
Route::post('/get-hakakses', 'HakaksesController@get_hakakses')->name('get.hakakses');
Route::post('/fill-hakakses', 'HakaksesController@fill_hakakses')->name('fill.hakakses');
Route::post('/edit-hakakses', 'HakaksesController@edit_hakakses')->name('edit.hakakses');
Route::post('/add-hakakses', 'HakaksesController@add_hakakses')->name('add.hakakses');
Route::post('/delete-hakakses', 'HakaksesController@destroy')->name('delete.hakakses');
// ----------------------------------End Hak Akses Controller

// ----------------------------------Begin Obat Controller
Route::get('/obat', 'ObatController@index')->name('kl.obat');
Route::post('/get-obat', 'ObatController@get_obat')->name('get.obat');
Route::post('/fill-obat', 'ObatController@fill_obat')->name('fill.obat');
Route::post('/edit-obat', 'ObatController@edit_obat')->name('edit.obat');
Route::post('/add-obat', 'ObatController@add_obat')->name('add.obat');
Route::post('/delete-obat', 'ObatController@destroy')->name('delete.obat');
// ----------------------------------End Obat Controller

// ----------------------------------Begin Pasien Controller
Route::get('/pasien', 'PasienController@index')->name('kl.pasien');
Route::post('/get-pasien', 'PasienController@get_pasien')->name('get.pasien');
Route::get('/find-pasien', 'PasienController@find_pasien')->name('find.pasien');
Route::post('/fill-pasien', 'PasienController@fill_pasien')->name('fill.pasien');
Route::post('/edit-pasien', 'PasienController@edit_pasien')->name('edit.pasien');
Route::post('/add-pasien', 'PasienController@add_pasien')->name('add.pasien');
Route::post('/delete-pasien', 'PasienController@destroy')->name('delete.pasien');
// ----------------------------------End Pasien Controller

// ----------------------------------Begin User Controller
Route::get('/user', 'UserController@index')->name('kl.user');
Route::post('/get-user', 'UserController@get_user')->name('get.user');
Route::post('/fill-user', 'UserController@fill_user')->name('fill.user');
Route::post('/edit-user', 'UserController@edit_user')->name('edit.user');
Route::post('/add-user', 'UserController@add_user')->name('add.user');
Route::post('/delete-user', 'UserController@destroy')->name('delete.user');
// ----------------------------------End User Controller

// ----------------------------------Begin Menu Controller
Route::get('/menu', 'MenuController@index')->name('kl.menu');
Route::post('/get-menu', 'MenuController@get_menu')->name('get.menu');
Route::post('/fill-menu', 'MenuController@fill_menu')->name('fill.menu');
Route::post('/edit-menu', 'MenuController@edit_menu')->name('edit.menu');
Route::post('/add-menu', 'MenuController@add_menu')->name('add.menu');
Route::post('/delete-menu', 'MenuController@destroy')->name('delete.menu');
// ----------------------------------End Menu Controller

// ----------------------------------Begin Transaksi Controller
Route::get('/antrian', 'TransaksiController@index')->name('tr.antrian');
Route::post('/daftar-antrian', 'TransaksiController@daftar_antrian')->name('daftar.antrian');
Route::post('/get-antrian', 'TransaksiController@get_antrian')->name('get.antrian');
Route::post('/fill-antrian', 'TransaksiController@fill_antrian')->name('fill.antrian');
Route::post('/periksa-pasien', 'TransaksiController@periksa_pasien')->name('periksa.pasien');
Route::get('/transaksi-resep-obat', 'TransaksiController@index_obat')->name('tr.transaksi.obat');
Route::post('/get-transaksi-obat', 'TransaksiController@get_transaksi_obat')->name('get.transaksi.obat');
Route::post('/fill-resep-obat', 'TransaksiController@fill_resep_obat')->name('fill.resep.obat');
Route::post('/bayar-obat', 'TransaksiController@bayar_obat')->name('bayar.obat');
// ----------------------------------End Transaksi Controller


