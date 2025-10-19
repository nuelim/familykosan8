<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    // use HasFactory;
	protected $table="cabang";
	protected $primaryKey="id_cabang";

	protected $fillable = [
        'nama_cabang',
        'kode_cabang',
        'alamat_cabang',
        'link_cabang',
        'status_cabang',
        'created_by',
    ];


	public static function getLabelCabang($request)
	{
		$label_cabang = Cabang::where('kode_cabang', $request->access)->first();
		return $label_cabang;
		
	}

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_cabang');
    }
}
