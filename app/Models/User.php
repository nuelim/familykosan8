<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function getUser($request, $level)
    {
        // $idCabangString = array_map('intval', session('site'));
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang');
        if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
            $data->where('cabang.kode_cabang',$request->access);
        }else{
            // $data->whereIn('biodata.id_cabang',session('site'));
        }
        if ($level == 'operator') {
            $data = $data->where('users.level','Operator')
            ->get();
        }elseif($level == 'penghuni'){
            $data = $data->where('users.level','Penghuni')
            ->get();
        }
        return $data;
    }
    public static function getEditOperator($id)
    {
        $data = User::join('biodata','biodata.id_user','=','users.id');
        $data = $data->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang')
        ->where('users.id',$id)
        ->get();
        return $data;
    }
    public static function getEditPenghuni($id)
    {
        $data = User::join('biodata','biodata.id_user','=','users.id');
        $data = $data->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang')
        ->where('users.id',$id)
        ->get();
        return $data;
    }
    public static function getMyProfil()
    {
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang')
        // ->where('users.level','!=','Penghuni')
        ->where('users.id',Auth::user()->id)
        ->get();
        return $data;
    }
    public static function handleSuccessLogin($request, $request_login, $password)
    {
        if (!empty($request_login) && Hash::check($password, $request_login->password) && $request_login->status_user === 'Aktif') {
            Auth::loginUsingId($request_login->id);
            $id_cabang = DB::table('biodata')->select(\DB::RAW('id_cabang'))
            ->where('id_user',Auth::user()->id)
            ->first();
            $request->session()->put('site',explode(',', $id_cabang->id_cabang));
            if (Auth::user()->level == 'Super Admin') {
                return response()->json([
                    'status' => 'true',
                    'title' => 'Login Berhasil',
                    'message' => 'Login berhasil, selamat beraktivitas !!',
                    'url' => route('home'),
                ]);
            } elseif (Auth::user()->level == 'Penghuni') {
                return response()->json([
                    'status' => 'true',
                    'title' => 'Login Berhasil',
                    'message' => 'Login berhasil, selamat beraktivitas !!',
                    'url' => route('home'),
                ]);
            } elseif (Auth::user()->level == 'Operator') {
                return response()->json([
                    'status' => 'true',
                    'title' => 'Login Berhasil',
                    'message' => 'Login berhasil, selamat beraktivitas !!',
                    'url' => route('operator.dashboard'),
                ]);
            }
        }
        return response()->json([
            'status' => 'false',
            'title' => 'Login Gagal',
            'message' => 'Email atau password yang Anda masukkan salah atau akun tidak aktif.',
        ]);
    }
}
