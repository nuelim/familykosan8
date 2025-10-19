<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\FasilitasController;
use App\Http\Controllers\Page\KamarController;
use App\Http\Controllers\Page\CabangController;
use App\Http\Controllers\Page\UserController;
use App\Http\Controllers\Page\PenyewaanController;
use App\Http\Controllers\Page\PembayaranController;
use App\Http\Controllers\Page\PengeluaranController;
use App\Http\Controllers\Page\AduanController;
use App\Http\Controllers\Page\KeuanganController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Penghuni\DashboardController;
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
date_default_timezone_set('Asia/Jakarta');
App\Models\Penyewaan::getTagihanTiapBulan();
Route::get('/clear', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('config:clear');
	dd("Sudah Bersih nih!, Silahkan Kembali ke Halaman Utama");
});
Route::get('get_tenggat/destroy',[PenyewaanController::class,'hapus_tenggat'])->name('hapus_tenggat');
Route::post('save-token', [PembayaranController::class, 'saveToken'])->name('save-token');
Route::get('login', function () {
	if (Auth::user()) {
		if (Auth::user()->level == 'Super Admin') {
			return redirect(route('index.dashboard'));
		}elseif (Auth::user()->level == 'Operator') {
			return redirect(route('operator.dashboard'));
		}else{
			return redirect(route('penghuni.dashboard'));
		}
	}
	return view('page.login');
})->name('login');
Route::get('register', function () {
	if (Auth::user()) {
		if (Auth::user()->level == 'Super Admin') {
			return redirect(route('index.dashboard'));
		}else{
			return redirect(route('penghuni.dashboard'));
		}
	}
	return view('page.register');
})->name('register');
Route::post('penghuni/save',[UserController::class,'save_penghuni'])->name('save.penghuni');
Route::get('lupa_password', function () {
	return view('page.forgot');
})->name('forgot');
Route::post('auth/request_login',[AuthController::class,'ceklogin'])->name('ceklogin');
Route::post('auth/request_forgot', [AuthController::class,'proses_forgot'])->name('proses_forgot');

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('view/{id_kamar}', [HomeController::class,'view'])->name('view');

Route::middleware(['auth', 'ceklevel:Super Admin'])->prefix('page/master')->group(function() {
	Route::get('fasilitas',[FasilitasController::class,'index'])->name('index.fasilitas');
	Route::post('fasilitas/save',[FasilitasController::class,'save'])->name('save.fasilitas');
	Route::get('fasilitas/get_edit/{id_fasilitas}',[FasilitasController::class,'get_edit']);
	Route::post('fasilitas/update',[FasilitasController::class,'update'])->name('update.fasilitas');
	Route::get('fasilitas/destroy/{id_fasilitas}',[FasilitasController::class,'delete']);

	Route::get('kamar',[KamarController::class,'index'])->name('index.kamar');
	Route::post('kamar/save',[KamarController::class,'save'])->name('save.kamar');
	Route::get('kamar/get_edit/{id_kamar}',[KamarController::class,'get_edit']);
	Route::post('kamar/update',[KamarController::class,'update'])->name('update.kamar');
	Route::get('kamar/destroy/{id_kamar}',[KamarController::class,'delete']);

	Route::get('cabang',[CabangController::class,'index'])->name('index.cabang');
	Route::get('cabang/getIdCabangSuperAdmin',[CabangController::class,'get_id_cabang'])->name('get_id_cabang');
	Route::post('cabang/save',[CabangController::class,'save'])->name('save.cabang');
	Route::get('cabang/get_edit/{id_cabang}',[CabangController::class,'get_edit']);
	Route::post('cabang/update',[CabangController::class,'update'])->name('update.cabang');
	Route::get('cabang/destroy/{id_cabang}',[CabangController::class,'delete']);
});

Route::middleware(['auth', 'ceklevel:Super Admin,Operator'])->prefix('page/user')->group(function() {
	Route::get('{level}',[UserController::class,'index'])->name('index.user');
	Route::get('destroy/{id}',[UserController::class,'delete']);
	// action penghuni
	Route::get('penghuni/get_edit/{id}',[UserController::class,'get_edit_penghuni']);
	Route::post('penghuni/update',[UserController::class,'update_penghuni'])->name('update.penghuni');
});
Route::middleware(['auth', 'ceklevel:Super Admin'])->prefix('page/user')->group(function() {
	// action operator
	Route::post('operator/save',[UserController::class,'save_operator'])->name('save.operator');
	Route::get('operator/get_edit/{id}',[UserController::class,'get_edit_operator']);
	Route::post('operator/update',[UserController::class,'update_operator'])->name('update.operator');
});
Route::middleware(['auth', 'ceklevel:Super Admin,Operator,Penghuni'])->prefix('page/pengelolaan')->group(function() {
	Route::post('penyewaan/save',[PenyewaanController::class,'save'])->name('save.penyewaan');
});
// kelola sewa,bayar,pengeluaran
Route::middleware(['auth', 'ceklevel:Super Admin,Operator'])->prefix('page/pengelolaan')->group(function() {
	Route::get('penyewaan',[PenyewaanController::class,'index'])->name('index.penyewaan');
	Route::get('penyewaan/get_penghuni',[PenyewaanController::class,'get_penghuni'])->name('get_penghuni');
	Route::get('penyewaan/view/{kode_penyewaan}-{id_penyewaan}',[PenyewaanController::class,'detail_penyewan'])->name('detail_penyewan');
	Route::get('penyewaan/get_edit/{id_penyewaan}',[PenyewaanController::class,'get_edit']);
	Route::post('penyewaan/update',[PenyewaanController::class,'update'])->name('update.penyewaan');
	Route::get('penyewaan/destroy/{id_penyewaan}',[PenyewaanController::class,'delete']);
	Route::get('penyewaan/bayar_tagihan/get_edit/{id_bayar}',[PembayaranController::class,'get_edit_pembayaran']);
	// Route::post('penyewaan/bayar_tagihan/update',[PembayaranController::class,'update_pembayaran'])->name('update.pembayaran');
	Route::get('penyewaan/bayar_tagihan/destroy/{id_bayar}',[PembayaranController::class,'delete_pembayaran']);
	// pembayaran side menu
	Route::get('pembayaran',[PembayaranController::class,'index'])->name('index.pembayaran');
	// pengeluaran
	Route::get('pengeluaran',[PengeluaranController::class,'index'])->name('index.pengeluaran');
	Route::post('pengeluaran/save',[PengeluaranController::class,'save'])->name('save.pengeluaran');
	Route::get('pengeluaran/get_edit/{id_pengeluaran}',[PengeluaranController::class,'get_edit']);
	Route::post('pengeluaran/update',[PengeluaranController::class,'update'])->name('update.pengeluaran');
	Route::get('pengeluaran/destroy/{id_pengeluaran}',[PengeluaranController::class,'delete']);
});
Route::middleware(['auth', 'ceklevel:Super Admin,Penghuni,Operator'])->prefix('page')->group(function() {
	Route::post('penyewaan/bayar_tagihan/save',[PembayaranController::class,'save_pembayaran'])->name('save.pembayaran');
	Route::post('penyewaan/reminder/send',[PenyewaanController::class,'reminder_send'])->name('send.reminder');
});
// end kelola

// penghuni
Route::middleware(['auth', 'ceklevel:Penghuni'])->prefix('page/penghuni')->group(function() {
	Route::get('dashboard',[App\Http\Controllers\Penghuni\DashboardController::class,'index'])->name('penghuni.dashboard');
	Route::get('detail_penyewan/{kode_penyewaan}/{id_penyewaan}',[App\Http\Controllers\Penghuni\DashboardController::class,'detail_penyewan'])->name('penghuni.detail_penyewan');
});
// ajuan/aduan
Route::middleware(['auth', 'ceklevel:Penghuni'])->prefix('page')->group(function() {
	Route::post('aduan/save',[AduanController::class,'save'])->name('save.ajuan');
});
Route::middleware(['auth', 'ceklevel:Penghuni,Super Admin,Operator'])->prefix('page')->group(function() {
	Route::get('kotak_saran',[AduanController::class,'index'])->name('penghuni.aduan');
	Route::get('aduan/get_edit/{id_ajuan}',[AduanController::class,'get_edit']);
	Route::get('aduan/destroy/{id_ajuan}',[AduanController::class,'delete']);

	Route::get('invoice/{id_bayar}',[PembayaranController::class,'invoice'])->name('invoice');

	Route::get('myprofil',[DashboardController::class,'myprofil'])->name('myprofil');
	Route::post('myprofil/update',[DashboardController::class,'update_profil'])->name('update_profil');
});
// end aduan
// laporan sewa,bayar,pengeluaran
Route::middleware(['auth', 'ceklevel:Super Admin,Operator'])->prefix('page/laporan')->group(function() {
	Route::get('penyewaan',[PenyewaanController::class,'laporan'])->name('laporan.penyewaan');
	Route::get('penyewaan/export',[PenyewaanController::class,'laporan_export'])->name('laporan.export');
	Route::get('pembayaran',[PembayaranController::class,'laporan'])->name('laporan.pembayaran');
	Route::get('pembayaran/export',[PembayaranController::class,'laporan_export'])->name('pembayaran.export');
	Route::get('pengeluaran',[PengeluaranController::class,'laporan'])->name('laporan.pengeluaran');
	Route::get('pengeluaran/export',[PengeluaranController::class,'laporan_export'])->name('pengeluaran.export');
	Route::get('keuangan',[KeuanganController::class,'laporan'])->name('laporan.keuangan');
	Route::get('keuangan/export',[KeuanganController::class,'laporan_export'])->name('keuangan.export');
});
// end kelola
Route::middleware(['auth', 'ceklevel:Super Admin,Operator'])->prefix('page')->group(function() {
	Route::get('getNotifikasi',[DashboardController::class,'get_notifikasi'])->name('get_notifikasi');

	Route::get('getReminder',[DashboardController::class,'get_reminder'])->name('get_reminder');

	Route::get('penyewaan_reminder',[PenyewaanController::class,'penyewaan_reminder'])->name('penyewaan_reminder');

	Route::get('riwayat_reminder',[PenyewaanController::class,'riwayat_reminder'])->name('riwayat_reminder');
	Route::get('riwayat_reminder/get_view/{id_penyewaan_reminder}',[PenyewaanController::class,'get_edit_riwayat_reminder'])->name('get_edit_riwayat_reminder');
	Route::get('riwayat_reminder/destroy/{id_penyewaan_reminder}',[PenyewaanController::class,'delete_reminder']);
});
Route::middleware(['auth', 'ceklevel:Super Admin'])->prefix('page')->group(function() {
	Route::get('dashboard',[DashboardController::class,'index'])->name('index.dashboard');
	Route::post('reminder/save',[DashboardController::class,'save_reminder'])->name('save_reminder');
	Route::get('beralih/access/{id_cabang}',[CabangController::class,'beralih']);
});
Route::middleware(['auth', 'ceklevel:Operator'])->prefix('page/operator')->group(function() {
	Route::get('dashboard',[App\Http\Controllers\Operator\DashboardController::class,'index'])->name('operator.dashboard');
});

Route::get('back/auth-logout',[AuthController::class,'logout'])->name('logout');
