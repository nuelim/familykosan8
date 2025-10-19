<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\Cabang;
use App\Models\Kamar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use DataTables;
use File; // Pastikan File di-import

class KamarController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}

	public function index(Request $request)
	{
		$fasilitas = Fasilitas::all();
		$cabang = Cabang::where('status_cabang','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$cabang->where('kode_cabang',$request->access);
		}else if (Auth::user()->level == 'Operator' && empty($request->access)) {
			$cabang->whereIn('id_cabang',session('site'));
		}
		$cabang = $cabang->get();
		if ($request->ajax()) {
			$data = Kamar::getKamar($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_kamar.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_kamar.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.kamar.index',compact('fasilitas','cabang'))->with('label_cabang', $this->label_cabang);
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_cabang' => 'required',
			'nomor_kamar' => 'required',
			'harga_kamar' => 'required',
			'jenis_kamar' => 'required'
		];
		$validateMessage += [
			'id_cabang.required' => 'Cabang harus dipilih.',
			'nomor_kamar.required' => 'Nomor Kamar harus diisi.',
			'harga_kamar.required' => 'Harga Kamar harus diisi.',
			'jenis_kamar.required' => 'Jenis Kamar harus dipilih.'
		];
		if (isset($request->fasilitas)) {
			foreach ($request->fasilitas as $key => $value) {
				$validateRules += [
					'fasilitas.*.id_fasilitas' => 'required',
				];
				$validateMessage += [
					'fasilitas.*.id_fasilitas.required' => 'Fasilitas harus dipilih.'
				];
			}
		}
		if (isset($request->gambar)) {
			foreach ($request->gambar as $key => $values) {
				$validateRules += [
					'gambar.*.foto' => 'required',
					'gambar.*.tipe' => 'required'
				];
				$validateMessage += [
					'gambar.*.foto.required' => 'Gambar harus diupload.',
					'gambar.*.tipe.required' => 'Tipe Gambar harus dipilih.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$harga_kamar = preg_replace("/[^aZ0-9]/", "", $request->harga_kamar);

			$data = New Kamar();
			$data -> id_cabang = $request->id_cabang;
			$data -> nama_kamar = $request->nama_kamar;
			$data -> nomor_kamar = $request->nomor_kamar;
			$data -> jenis_kamar = $request->jenis_kamar;
			$data -> harga_kamar = $harga_kamar;
			$data -> jenis_kamar = $request->jenis_kamar;
			$data -> keterangan_kamar = $request->keterangan_kamar;
			$data -> created_by = Auth::user()->id;
			$data -> save();

			if (isset($request->fasilitas)) {
				$id_fasilitas_array = [];
				foreach ($request->fasilitas as $key => $value) {
					if (in_array($value['id_fasilitas'], $id_fasilitas_array)) {
						return response()->json(['status'=>'warning','message'=>'Fasilitas tidak boleh sama.']);
					}
					$id_fasilitas_array[] = $value['id_fasilitas'];
					DB::table('kamar_fasilitas')->insert([
						'id_kamar'=>$data->id_kamar,
						'id_fasilitas'=>$value['id_fasilitas']
					]);
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Fasilitas Kamar Kos harus ditambahkan minimal 1.']);
			}
			if (isset($request->gambar)) {
                $countUtama = 0;
                foreach ($request->gambar as $key => $values) {
                    if ($values['tipe'] == 'utama') {
                        $countUtama++;
                    }
                    $ambil = $values['foto'];
                    $name = $ambil->getClientOriginalName();
                    $namaFileBaru = uniqid();
                    $namaFileBaru .= $name;

                    try {
                        Storage::disk('hosting_public')->putFileAs('gambar_kamar', $ambil, $namaFileBaru);
                    } catch (\Exception $e) {
                        // Fallback jika disk 'hosting_public' gagal
                        $ambil->move(public_path("gambar_kamar"), $namaFileBaru);
                    }

                    DB::table('kamar_foto')->insert([
                        'id_kamar' => $data->id_kamar,
                        'foto' => $namaFileBaru,
                        'tipe' => $values['tipe']
                    ]);
                }
				if ($countUtama > 1) {
					return response()->json(['status' => 'warning', 'message' => 'Hanya boleh ada satu gambar dengan tipe utama.']);
				} elseif ($countUtama == 0) {
					return response()->json(['status' => 'warning', 'message' => 'Harus ada gambar utama kamar kos.']);
				}
			}else{
				return response()->json(['status'=>'warning','message'=>'Gambar Kamar Kos harus ditambahkan minimal 1.']);
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Kamar berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_kamar)
	{
		$result = Kamar::getEditKamar($id_kamar);
		$data = $result['data'];
		$kamar_fasilitas = $result['kamar_fasilitas'];
		$kamar_foto = $result['kamar_foto'];
		return response()->json(['data'=>$data,'kamar_fasilitas'=>$kamar_fasilitas,'kamar_foto'=>$kamar_foto]);
	}
    
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_cabang' => 'required',
			'nomor_kamar' => 'required',
			'harga_kamar' => 'required',
			'jenis_kamar' => 'required'
		];
		$validateMessage += [
			'id_cabang.required' => 'Cabang harus dipilih.',
			'nomor_kamar.required' => 'Nomor Kamar harus diisi.',
			'harga_kamar.required' => 'Harga Kamar harus diisi.',
			'jenis_kamar.required' => 'Jenis Kamar harus dipilih.'
		];
		if (isset($request->fasilitas)) {
			foreach ($request->fasilitas as $key => $value) {
				$validateRules += [
					'fasilitas.*.id_fasilitas' => 'required',
				];
				$validateMessage += [
					'fasilitas.*.id_fasilitas.required' => 'Fasilitas harus dipilih.'
				];
			}
		}
		if (isset($request->gambar)) {
			foreach ($request->gambar as $key => $values) {
				$validateRules += [
					'gambar.*.tipe' => 'required'
				];
				$validateMessage += [
					'gambar.*.tipe.required' => 'Tipe Gambar harus dipilih.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$harga_kamar = preg_replace("/[^aZ0-9]/", "", $request->harga_kamar);

			$data = Kamar::where('id_kamar',$request->id_kamar)->first();
			$data -> id_cabang = $request->id_cabang;
			$data -> nama_kamar = $request->nama_kamar;
			$data -> nomor_kamar = $request->nomor_kamar;
			$data -> jenis_kamar = $request->jenis_kamar;
			$data -> harga_kamar = $harga_kamar;
			$data -> jenis_kamar = $request->jenis_kamar;
			$data -> keterangan_kamar = $request->keterangan_kamar;
			$data -> updated_by = Auth::user()->id;
			$data -> save();

			if (isset($request->fasilitas)) {
				$id_fasilitas_array = [];
				foreach ($request->fasilitas as $key => $value) {
					if (in_array($value['id_fasilitas'], $id_fasilitas_array)) {
						return response()->json(['status'=>'warning','message'=>'Fasilitas tidak boleh sama.']);
					}
					$id_fasilitas_array[] = $value['id_fasilitas'];
					if ($value['id_kamar_fasilitas'] == '') {
						$exists = DB::table('kamar_fasilitas')
									->where('id_kamar', $data->id_kamar)
									->where('id_fasilitas', $value['id_fasilitas'])
									->exists(); 

						if (!$exists) {
							DB::table('kamar_fasilitas')->insert([
								'id_kamar'=>$data->id_kamar,
								'id_fasilitas'=>$value['id_fasilitas']
							]);
						}
					}else{
						$exists = DB::table('kamar_fasilitas')
									->where('id_kamar', $data->id_kamar)
									->where('id_fasilitas', $value['id_fasilitas'])
									->where('id_kamar_fasilitas', '!=', $value['id_kamar_fasilitas'])
									->exists();
						
						if ($exists) {
							return response()->json(['status'=>'warning','message'=>'Fasilitas tidak boleh sama (konflik data update).']);
						}

						DB::table('kamar_fasilitas')->where('id_kamar_fasilitas',$value['id_kamar_fasilitas'])->update([
							'id_fasilitas'=>$value['id_fasilitas']
						]);
					}
				}
			}
            
			if (!empty($request->id_fasilitas_del)) {
				$id_fasilitas_del = explode(",", $request->id_fasilitas_del);
				DB::table('kamar_fasilitas')->whereIn('id_kamar_fasilitas',$id_fasilitas_del)->delete();
			}


			if (isset($request->gambar)) {
                
                $adaUtamaBaru = false;
                foreach ($request->gambar as $key => $values) {
                    if ($values['tipe'] == 'utama') {
                        $adaUtamaBaru = true;
                        break;
                    }
                }

                if ($adaUtamaBaru) {
                    DB::table('kamar_foto')->where('id_kamar', $data->id_kamar)->update(['tipe' => 'lainnya']);
                }

				foreach ($request->gambar as $key => $values) {
					
					if ($values['id_kamar_foto'] == '') {
                        
                        if (array_key_exists('foto', $values) && !empty($values['foto'])) {
    						$ambil=$values['foto'];
    						$name=$ambil->getClientOriginalName();
    						$namaFileBaru = uniqid();
    						$namaFileBaru .= $name;

                            try {
    						    Storage::disk('hosting_public')->putFileAs('gambar_kamar', $ambil, $namaFileBaru);
                            } catch (\Exception $e) {
                                $ambil->move(public_path("gambar_kamar"), $namaFileBaru);
                            }

    						DB::table('kamar_foto')->insert([
    							'id_kamar'=>$data->id_kamar,
    							'foto'=>$namaFileBaru,
    							'tipe'=>$values['tipe']
    						]);
                        }

					} else {
						DB::table('kamar_foto')->where('id', $values['id_kamar_foto'])->update([
							'tipe' => $values['tipe']
						]);
					}
				}
			}
            
			if (!empty($request->id_foto_del)) {
				$id_foto_del = explode(",", $request->id_foto_del);
                
                $foto_to_delete = DB::table('kamar_foto')->whereIn('id', $id_foto_del)->get();
                
                foreach($foto_to_delete as $foto) {
                    try {
                        if (Storage::disk('hosting_public')->exists('gambar_kamar/' . $foto->foto)) {
                            Storage::disk('hosting_public')->delete('gambar_kamar/' . $foto->foto);
                        }
                    } catch (\Exception $e) {
                        $lokasi_file = public_path("gambar_kamar/".$foto->foto);
                        if (File::exists($lokasi_file)) {
                            File::delete($lokasi_file);
                        }
                    }
                }
                
				DB::table('kamar_foto')->whereIn('id',$id_foto_del)->delete();
			}

            $cekUtama = DB::table('kamar_foto')->where('id_kamar', $data->id_kamar)->where('tipe', "utama")->count();
            if ($cekUtama == 0) {
                $fotoPertama = DB::table('kamar_foto')->where('id_kamar', $data->id_kamar)->orderBy('id', 'asc')->first();
                if ($fotoPertama) {
                    DB::table('kamar_foto')->where('id', $fotoPertama->id)->update(["tipe" => "utama"]);
                }
            }
            
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Kamar berhasil diupdate !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}

	public function delete($id_kamar)
	{
		try {
			DB::beginTransaction();
            
            $foto_to_delete = DB::table('kamar_foto')->where('id_kamar', $id_kamar)->get();
            foreach($foto_to_delete as $foto) {
                try {
                    if (Storage::disk('hosting_public')->exists('gambar_kamar/' . $foto->foto)) {
                        Storage::disk('hosting_public')->delete('gambar_kamar/' . $foto->foto);
                    }
                } catch (\Exception $e) {
                    $lokasi_file = public_path("gambar_kamar/".$foto->foto);
                    if (File::exists($lokasi_file)) {
                        File::delete($lokasi_file);
                    }
                }
            }

			DB::table('kamar_foto')->where('id_kamar', $id_kamar)->delete();
			DB::table('kamar_fasilitas')->where('id_kamar', $id_kamar)->delete();
			$data = Kamar::where('id_kamar',$id_kamar)->first();

			if ($data) {
				$data -> delete();
			} else {
				DB::rollBack();
				return response()->json(['status' => 'false', 'message' => 'Kamar tidak ditemukan.']);
			}

			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Kamar berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}