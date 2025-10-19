<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Penyewaan;
use App\Models\User;
use Carbon\Carbon;

class Bayar extends Model
{
    // use HasFactory;
	protected $table="bayar";
	protected $primaryKey="id_bayar";

	public static function cekValidPembayaran($request,$key)
	{
		$cek_penyewaan_valid_penghuni = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->select(
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan')
		)
		->where('penyewaan.id_user',Auth::user()->id)
		->where('penyewaan.status_penyewaan','!=','I')
		->where('users.status_user','Aktif')
		->first();
		$cek_valid_pembayaran = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->where('pembayaran.id_pembayaran',$request->id_pembayaran[$key])
		->where('pembayaran.id_bayar',NULL)
		->where('pembayaran.tanggal_pembayaran',NULL);
		if (Auth::user()->level == 'Penghuni') {
			$cek_valid_pembayaran->where('pembayaran.id_penyewaan',$cek_penyewaan_valid_penghuni->id_penyewaan);
		}else{
			$cek_valid_pembayaran->where('pembayaran.id_penyewaan',$request->id_penyewaan);
		}
		$cek_valid_pembayaran = $cek_valid_pembayaran->first();
		return $cek_valid_pembayaran;
	}
	public static function sendReminderToPengurus($request)
	{
		// $data = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		// ->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		// ->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		// ->join('users','users.id','=','penyewaan.id_user')
		// ->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		// $penghuni_login = User::join('biodata','biodata.id_user','=','users.id')
		// ->select(
		// 	\DB::RAW('biodata.id_cabang as id_cabang')
		// )
		// ->where('users.id',Auth::user()->id)
		// ->where('users.level','Penghuni')
		// ->first();
		// $id_cabang = explode(',', $penghuni_login->id_cabang);
		$detail_sewa = Penyewaan::join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->select(
			\DB::RAW('users.name as name'),
			\DB::RAW('kamar.id_cabang as id_cabang'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.created_by as created_by'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('kamar.jenis_kamar as jenis_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar')
		)
		->where('penyewaan.id_user',Auth::user()->id)
		->where('penyewaan.status_penyewaan','!=','I')
		->where('penyewaan.id_penyewaan',$request->id_penyewaan)
		->first();
		$result = User::join('biodata','biodata.id_user','=','users.id')
		->select(
			\DB::RAW('users.email as email'),
			\DB::RAW('biodata.ponsel as ponsel'),
			\DB::RAW('users.name as name')
		)
		->where('biodata.id_cabang','LIKE','%'.$detail_sewa->id_cabang.'%');
		$pengurus = clone $result;
		$pengurus = $pengurus->where('users.level','!=','Penghuni')
		->get();
		$operator = clone $result;
		$operator = $operator->where('users.level','Operator')
		->first();
		$super_admin = clone $result;
		$super_admin = $super_admin->where('users.level','Super Admin')
		->first();
		return ['operator'=>$operator,'detail_sewa'=>$detail_sewa,'pengurus'=>$pengurus,'super_admin'=>$super_admin];
	}
	public static function getEditPembayaran($id_bayar)
	{
		$data = Bayar::join('pembayaran','pembayaran.id_bayar','=','bayar.id_bayar')
		->where('pembayaran.jenis_pembayaran','lain-lain')
		->where('bayar.id_bayar',$id_bayar)
		->get();
		return $data;
	}
	public static function getIndexBayar($request)
	{
		$result = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('users','users.id','=','penyewaan.id_user')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('users.name as name'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('bayar.id_bayar as id_bayar'),
			\DB::RAW('bayar.kode_bayar as kode_bayar'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('bayar.status_bayar as status_bayar'),
			\DB::RAW('pembayaran.jenis_pembayaran as jenis_pembayaran'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
			\DB::RAW('bayar.bukti_bayar as bukti_bayar'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
		)->groupBy('bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','bayar.id_bayar','pembayaran.jenis_pembayaran','bayar.status_bayar','penyewaan.id_penyewaan','penyewaan.kode_penyewaan','users.name');
		if ($request->type == 'tagihan') {
			$result->select(
				\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
				\DB::RAW('bayar.status_bayar as status_bayar'),
				\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
				\DB::RAW('cabang.nama_cabang as nama_cabang'),
				\DB::RAW('users.name as name'),
				\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
				\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
				\DB::RAW('pembayaran.bulan_pembayaran as bulan_pembayaran'),
				\DB::RAW('pembayaran.tahun_pembayaran as tahun_pembayaran')
			)
			->groupBy('pembayaran.bulan_pembayaran','pembayaran.tahun_pembayaran','bayar.status_bayar','penyewaan.kode_penyewaan','penyewaan.id_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang')
			->where('pembayaran.jenis_pembayaran','kos')
			->where('pembayaran.bulan_pembayaran',date('m'))
			->where('pembayaran.tahun_pembayaran',date('Y'));
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$result->where('cabang.kode_cabang',$request->access);
		}else{
			$result->whereIn('kamar.id_cabang',session('site'));
		}
		if (!empty($request->keyword)) {
			if ($request->keyword == 'Belum Bayar') {
				$result->where('pembayaran.id_bayar',NULL)
				->where('pembayaran.tanggal_pembayaran',NULL);
			}else{
				$result->where('bayar.status_bayar',$request->keyword);
			}
		}
		if (!empty($request->tanggal_awal)) {
			$result->where(function ($query) use ($request) {
				$startMonth = date('m', strtotime($request->tanggal_awal));
				$startYear = date('Y', strtotime($request->tanggal_awal));
				$endMonth = date('m', strtotime($request->tanggal_akhir));
				$endYear = date('Y', strtotime($request->tanggal_akhir));
				if ($startYear == $endYear) {
					$query->where('pembayaran.tahun_pembayaran', $startYear)
					->whereBetween('pembayaran.bulan_pembayaran', [$startMonth, $endMonth]);
				} else {
					$query->where(function ($subQuery) use ($startMonth, $startYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $startYear)
						->where('pembayaran.bulan_pembayaran', '>=', $startMonth);
					})
					->orWhere(function ($subQuery) use ($endMonth, $endYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $endYear)
						->where('pembayaran.bulan_pembayaran', '<=', $endMonth);
					})
					->orWhere(function ($subQuery) use ($startYear, $endYear) {
						$subQuery->whereBetween('pembayaran.tahun_pembayaran', [$startYear + 1, $endYear - 1]);
					});
				}
			});
		}
		$result->where('penyewaan.status_penyewaan','!=','I');
		if ($request->type == 'pembayaran') {
			$result->orderBy('bayar.id_bayar','DESC');
		}else{
			$result->orderBy('pembayaran.bulan_pembayaran','ASC')
			->orderBy('pembayaran.tahun_pembayaran','ASC');
		}
		$result = $result->get();
		return $result;
	}
	public static function getLaporanBayar($request)
	{
		$data = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('users','users.id','=','penyewaan.id_user')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('users.name as name'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('bayar.id_bayar as id_bayar'),
			\DB::RAW('bayar.kode_bayar as kode_bayar'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('bayar.status_bayar as status_bayar'),
			\DB::RAW('pembayaran.jenis_pembayaran as jenis_pembayaran'),
			\DB::RAW('pembayaran.bulan_pembayaran as bulan_pembayaran'),
			\DB::RAW('pembayaran.tahun_pembayaran as tahun_pembayaran'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('pembayaran.harga_pembayaran as total_tagihan'),
			\DB::RAW('bayar.bukti_bayar as bukti_bayar'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
		)
		->groupBy('bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','bayar.id_bayar','pembayaran.jenis_pembayaran','bayar.status_bayar','penyewaan.id_penyewaan','penyewaan.kode_penyewaan','cabang.nama_cabang','kamar.nomor_kamar','users.name','pembayaran.bulan_pembayaran','pembayaran.tahun_pembayaran','pembayaran.harga_pembayaran');
		if (!empty($request->tanggal_awal)) {
			$data = $data->where(function ($query) use ($request) {
				$startMonth = date('m', strtotime($request->tanggal_awal));
				$startYear = date('Y', strtotime($request->tanggal_awal));
				$endMonth = date('m', strtotime($request->tanggal_akhir));
				$endYear = date('Y', strtotime($request->tanggal_akhir));
				if ($startYear == $endYear) {
					$query->where('pembayaran.tahun_pembayaran', $startYear)
					->whereBetween('pembayaran.bulan_pembayaran', [$startMonth, $endMonth]);
				} else {
					$query->where(function ($subQuery) use ($startMonth, $startYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $startYear)
						->where('pembayaran.bulan_pembayaran', '>=', $startMonth);
					})
					->orWhere(function ($subQuery) use ($endMonth, $endYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $endYear)
						->where('pembayaran.bulan_pembayaran', '<=', $endMonth);
					})
					->orWhere(function ($subQuery) use ($startYear, $endYear) {
						$subQuery->whereBetween('pembayaran.tahun_pembayaran', [$startYear + 1, $endYear - 1]);
					});
				}
			});
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		$data = $data->where('penyewaan.status_penyewaan','A')->orderBy('bayar.id_bayar','ASC')->get();
		// 
		$tagihan = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NOT NULL THEN pembayaran.harga_pembayaran ELSE 0 END) AS tagihan_sudah_bayar'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN pembayaran.harga_pembayaran ELSE 0 END) AS tagihan_belum_bayar')
		);
		if (!empty($request->tanggal_awal)) {
			$tagihan->where(function ($query) use ($request) {
				$startMonth = date('m', strtotime($request->tanggal_awal));
				$startYear = date('Y', strtotime($request->tanggal_awal));
				$endMonth = date('m', strtotime($request->tanggal_akhir));
				$endYear = date('Y', strtotime($request->tanggal_akhir));
				if ($startYear == $endYear) {
					$query->where('pembayaran.tahun_pembayaran', $startYear)
					->whereBetween('pembayaran.bulan_pembayaran', [$startMonth, $endMonth]);
				} else {
					$query->where(function ($subQuery) use ($startMonth, $startYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $startYear)
						->where('pembayaran.bulan_pembayaran', '>=', $startMonth);
					})
					->orWhere(function ($subQuery) use ($endMonth, $endYear) {
						$subQuery->where('pembayaran.tahun_pembayaran', $endYear)
						->where('pembayaran.bulan_pembayaran', '<=', $endMonth);
					})
					->orWhere(function ($subQuery) use ($startYear, $endYear) {
						$subQuery->whereBetween('pembayaran.tahun_pembayaran', [$startYear + 1, $endYear - 1]);
					});
				}
			});
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$tagihan->where('cabang.kode_cabang',$request->access);
		}else{
			$tagihan->whereIn('kamar.id_cabang',session('site'));
		}
		$tagihan = $tagihan->where('penyewaan.status_penyewaan','A')->first();
		return ['data'=>$data,'tagihan'=>$tagihan];
	}
	public static function getNotifikasi($request)
	{
		$result = Penyewaan::join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access_kode)) {
			$result->where('cabang.kode_cabang',$request->access_kode);
		}else{
			$result->whereIn('kamar.id_cabang',session('site'));
		}
		$pembayaran = clone $result;
		$pembayaran = $pembayaran
		->join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('biodata.foto as foto'),
			\DB::RAW('bayar.created_at as created_at'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('SUM(kamar.harga_kamar) as total_tagihan'),
			\DB::RAW('COUNT(pembayaran.id_bayar) as total_bulan')
		)
		->groupBy('users.name','bayar.created_at','bayar.tipe_bayar','biodata.foto','penyewaan.id_penyewaan','penyewaan.kode_penyewaan')
		->where('penyewaan.status_penyewaan','!=','I')
		->where('bayar.status_bayar','Sedang di cek')
		->orderBy('bayar.id_bayar','DESC')
		->get();

		$penyewaan_akan_selesai = clone $result;
		$penyewaan_akan_selesai = $penyewaan_akan_selesai
		->select(
			\DB::raw('DATEDIFF(penyewaan.tanggal_selesai, CURDATE()) as hari_tersisa'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::raw('users.name as name'),
			\DB::raw('kamar.nomor_kamar as nomor_kamar'),
			\DB::raw('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('biodata.foto as foto'),
			\DB::raw('penyewaan.tanggal_selesai as tanggal_selesai'),
		)
		->groupBy('users.name','biodata.foto','penyewaan.id_penyewaan','penyewaan.kode_penyewaan','kamar.nomor_kamar','cabang.nama_cabang','penyewaan.tanggal_selesai')
		->whereDate('penyewaan.tanggal_selesai', '<=', Carbon::now()->addDays(3))
		->where('penyewaan.status_penyewaan', 'A')
		->orderBy('penyewaan.tanggal_selesai','ASC')
		->get();
		// 
		Carbon::setLocale('id');
		$combined = [];
		foreach ($pembayaran as $item) {
			$combined[] = [
				'date' => $item->created_at,
				'name' => $item->name,
				'foto' => $item->foto,
				'keterangan' => '<a href="'.route('detail_penyewan',['kode_penyewaan'=>$item->kode_penyewaan,'id_penyewaan'=>$item->id_penyewaan,'access'=>request()->has('access') ? request()->input('access') : null]).'" class="text-muted">Pembayaran Kos ('.$item->tipe_bayar.')<br>Total: Rp. '. number_format($item->total_tagihan, 0, ',', '.') .' untuk '.$item->total_bulan.' bulan'.'</a>',
				'time' => Carbon::parse($item->created_at)->diffForHumans()
			];
		}
		foreach ($penyewaan_akan_selesai as $item) {
			if ($item->hari_tersisa > 0) {
				$warning = '<div class="spinner-grow spinner-grow-sm spinner-xs text-danger" role="status"></div> (Segara ubah status sewa menjadi selesai.)';
			}elseif ($item->hari_tersisa < 1) {
				$warning = '<div class="spinner-grow spinner-grow-sm spinner-xs text-warning" role="status"></div>';
			}
			$combined[] = [
				'date' => $item->tanggal_selesai,
				'name' => $item->name,
				'foto' => $item->foto,
				'keterangan' => '<a href="'.route('detail_penyewan',['kode_penyewaan'=>$item->kode_penyewaan,'id_penyewaan'=>$item->id_penyewaan,'access'=>request()->has('access') ? request()->input('access') : null]).'" class="text-muted">Penyewaan akan segera berkahir<br>Tanggal akhir sewa: '. tanggal_indonesia($item->tanggal_selesai).'</a>',
				'time' => $item->hari_tersisa.' hari '.$warning
			];
		}
		usort($combined, function ($a, $b) {
			return strtotime($b['date']) - strtotime($a['date']);
		});
		return $combined;
	}
	public static function PushNotifikasiPembayaran($request)
	{
		set_time_limit(120);

		$result_cabang = self::sendReminderToPengurus($request);
		$id_cabang = $result_cabang['detail_sewa'];

    // Ambil token dari pengguna yang memiliki device_token yang valid
		$operator_token = User::join('biodata','biodata.id_user','=','users.id')
		->whereNotNull('users.device_token')
		->where('biodata.id_cabang', 'LIKE', "%$id_cabang->id_cabang%")
		->get(['users.device_token', 'users.name']);

		$serviceAccountPath = public_path('tes-push-90913-firebase-adminsdk-ek41c-0ab6a97461.json');
		$serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
		$accessToken = self::getAccessToken($serviceAccount);

		foreach ($operator_token as $token) {
        // Payload for notification
			$data = [
				'message' => [
					'token' => $token->device_token,
					'notification' => [
						'title' => 'GION Kos',
						'body' => 'Halo '.$token->name.'! Telah diupload pembayaran kos oleh Penghuni, silahkan login untuk mengecek Detail Pembayaran.',
					],
					'android' => [
						'priority' => 'high',
						'notification' => [
                        'click_action' => 'OPEN_ACTIVITY_1', // Ganti dengan action sesuai kebutuhan aplikasi Android
                        'sound' => 'default',  // Suara notifikasi (optional)
                    ]
                ],
                'apns' => [
                	'headers' => [
                		'apns-priority' => '10'
                	]
                ]
            ]
        ];

        // Convert to JSON
        $dataString = json_encode($data);

        // Headers for FCM request
        $headers = [
        	'Authorization: Bearer ' . $accessToken,
        	'Content-Type: application/json',
        ];

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/tes-push-90913/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        if ($response === false) {
        	$error = curl_error($ch);
        	curl_close($ch);
        	throw new Exception('cURL error: ' . $error);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
    }
}

public static function getAccessToken($serviceAccount)
{
	$jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
	$jwtHeader = base64_encode($jwtHeader);

	$now = time();
	$exp = $now + 3600;

	$jwtClaim = json_encode([
		'iss' => $serviceAccount['client_email'],
		'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
		'aud' => 'https://oauth2.googleapis.com/token',
		'iat' => $now,
		'exp' => $exp
	]);
	$jwtClaim = base64_encode($jwtClaim);

	$privateKey = $serviceAccount['private_key'];
	$signature = '';
	openssl_sign($jwtHeader . '.' . $jwtClaim, $signature, $privateKey, 'sha256WithRSAEncryption');
	$jwtSignature = base64_encode($signature);

	$jwt = $jwtHeader . '.' . $jwtClaim . '.' . $jwtSignature;

	$postData = http_build_query([
		'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
		'assertion' => $jwt
	]);

	$opts = [
		'http' => [
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postData
		]
	];

	$context  = stream_context_create($opts);
	$result = file_get_contents('https://oauth2.googleapis.com/token', false, $context);
	$response = json_decode($result, true);

	return $response['access_token'];
}


public static function getReminder($request)
{
	$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
	->join('biodata','biodata.id_user','=','users.id')
	->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
	->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
	->join('biodata as operator','operator.id_user','=','penyewaan.created_by')
	->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
	->select(
		\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
		\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
		\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
		\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
		\DB::RAW('biodata.foto as foto'),
		\DB::RAW('SUM(CASE WHEN pembayaran.id_bayar IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN kamar.harga_kamar ELSE 0 END ELSE 0 END) AS tagihan_belum_bayar'),
		\DB::raw('DATEDIFF(penyewaan.tanggal_selesai, CURDATE()) as hari_tersisa')
	)->groupBy('penyewaan.kode_penyewaan','kamar.nomor_kamar','penyewaan.tanggal_selesai','penyewaan.id_penyewaan','kamar.id_kamar','biodata.foto','penyewaan.id_penyewaan','penyewaan.kode_penyewaan');
	if (Auth::user()->level == 'Super Admin' && !empty($request->access_kode)) {
		$data->where('cabang.kode_cabang',$request->access_kode);
	}else{
		$data->whereIn('kamar.id_cabang',session('site'));
	}
	$data = $data
	->whereDate('penyewaan.tanggal_selesai', '<=', Carbon::now()->addDays(30))
	->where('penyewaan.status_penyewaan', 'A')
	->where('pembayaran.id_bayar', NULL)
	->orderBy('penyewaan.tanggal_selesai','ASC')
	->limit('5')
	->get();
	return $data;
}
public static function getInvoice($id_bayar)
{
	$result = Penyewaan::join('users','users.id','=','penyewaan.id_user')
	->join('biodata','biodata.id_user','=','users.id')
	->leftJoin('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
	->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
	->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar');
	if (Auth::user()->level == 'Penghuni') {
		$result->where('penyewaan.id_user',Auth::user()->id);
	}
	$data = clone $result;
	$data = $data->leftJoin('cabang','cabang.id_cabang','=','kamar.id_cabang')
	->select(
		\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
		\DB::RAW('users.name as name'),
		\DB::RAW('cabang.alamat_cabang as alamat_cabang'),
		\DB::RAW('cabang.nama_cabang as nama_cabang'),
		\DB::RAW('bayar.kode_bayar as kode_bayar'),
		\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
		\DB::RAW('bayar.keterangan_bayar as keterangan_bayar'),
		\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
		\DB::RAW('kamar.harga_kamar as harga_kamar'),
		\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
		\DB::RAW('pembayaran.harga_pembayaran as harga_pembayaran')
	)->groupBy('penyewaan.kode_penyewaan','users.name','pembayaran.harga_pembayaran','bayar.kode_bayar','bayar.tanggal_bayar','kamar.nomor_kamar','kamar.harga_kamar','bayar.keterangan_bayar','cabang.nama_cabang','cabang.alamat_cabang')
	->where('bayar.id_bayar',$id_bayar)
	->get();
	$pembayaran = clone $result;
	$pembayaran = $pembayaran->select(
		\DB::RAW('pembayaran.bulan_pembayaran as bulan_pembayaran'),
		\DB::RAW('pembayaran.tahun_pembayaran as tahun_pembayaran')
	)
	->where('pembayaran.id_bayar','!=',NULL)
	->where('bayar.id_bayar',$id_bayar)
	->get();
	return ['data'=>$data,'pembayaran'=>$pembayaran];
}
}
