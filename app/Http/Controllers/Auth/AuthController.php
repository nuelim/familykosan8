<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
	public function ceklogin(Request $request)
	{
		$request_input = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'ponsel';
		$request_login = User::join('biodata', 'biodata.id_user', '=', 'users.id')
		->select('users.*', 'biodata.ponsel')
		->when($request_input == 'email', function ($query) use ($request) {
			$query->where('users.email', $request->email);
		})
		->when($request_input == 'ponsel', function ($query) use ($request) {
			$query->where('biodata.ponsel', $request->email);
		})
		->first();
		return User::handleSuccessLogin($request, $request_login, $request->password);
	}
	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->forget('access');
		$request->session()->forget('site');
		return redirect(route('home'));
	}
	public function proses_forgot(Request $request)
	{
		try {
			DB::beginTransaction();
			$data = User::join('biodata','biodata.id_user','=','users.id')
			->where('users.email',$request->email)
			->where('users.status_user','Aktif')
			->first();
			if ($data) {
				$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				$shuffle  = substr(str_shuffle($karakter), 0, 8);
				$kode=$shuffle.$data->id;
				DB::table('users')->where('id',$data->id)->update([
					'password'=>hash::make($kode)
				]);
				$details = [
					'status'=>'Lupa Password',
					'subject'=>'Lupa Password',
					'password'=>$kode,
					'name'=>$data->name
				];
				\Mail::to($data->email)->send(new \App\Mail\SendMail($details));
			}else{
				return response()->json(['status'=>'warning','message'=>'Email tidak ditemukan.']);
			}
			DB::commit();
			return response()->json(['status'=>'true','message'=>'Lupa Password berhasil, password baru telah terkirim di email Anda !!']);
		} catch (Exception $e) {
			DB::rollBack();
		}
	}
}
