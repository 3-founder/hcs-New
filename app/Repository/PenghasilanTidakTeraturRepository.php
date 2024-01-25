<?php

namespace App\Repository;

use App\Models\CabangModel;
use App\Models\ImportPenghasilanTidakTeraturModel;
use App\Models\KaryawanModel;
use App\Models\SpModel;
use Carbon\Carbon;
use App\Models\TunjanganModel;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Support\Facades\DB;

class PenghasilanTidakTeraturRepository
{
    public function getDataBonus($search, $limit=10, $page=1) {
        $kd_cabang = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : 'pusat';
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);
        $bonus = DB::table('penghasilan_tidak_teratur')
                    ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
                    ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->leftJoin('mst_cabang', 'mst_cabang.kd_cabang', 'mst_karyawan.kd_entitas')
                    ->select(
                        'penghasilan_tidak_teratur.is_lock',
                        'penghasilan_tidak_teratur.id',
                        'penghasilan_tidak_teratur.id_tunjangan',
                        'penghasilan_tidak_teratur.nip',
                        'penghasilan_tidak_teratur.kd_entitas',
                        'mst_karyawan.nama_karyawan',
                        'mst_tunjangan.nama_tunjangan',
                        'nominal',
                        'bulan',
                        'penghasilan_tidak_teratur.created_at as new_date',
                        DB::raw('SUM(nominal) as jumlah_nominal'),
                        DB::raw('COUNT(penghasilan_tidak_teratur.id) as total_data'),
                        'tahun',
                        'keterangan',
                        DB::raw("IF(penghasilan_tidak_teratur.kd_entitas != '000', mst_cabang.nama_cabang, 'Pusat' ) as entitas")
                    )
                    ->where('mst_tunjangan.kategori','bonus')
                    ->where(function ($query) use ($search) {
                        $query->where('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
                            ->orWhere('mst_cabang.nama_cabang', 'like', "%$search%");
                    })
                    ->where(function ($query) use ($kd_cabang, $kode_cabang_arr) {
                        if ($kd_cabang != 'pusat') {
                            $query->where('mst_karyawan.kd_entitas', $kd_cabang);
                        }
                    })
                    ->groupBy('mst_tunjangan.id', 'mst_tunjangan.nama_tunjangan', 'new_date', 'penghasilan_tidak_teratur.kd_entitas')
                    ->orderBy('penghasilan_tidak_teratur.kd_entitas')
                    ->orderBy('mst_tunjangan.id', 'ASC')
                    ->paginate($limit);

        return $bonus;
    }

    public function getNameTunjangan($id){
        $data = DB::table('mst_tunjangan')->select('nama_tunjangan')->where('id', $id)->first();
        return $data;
    }

    public function getNameCabang($kdEntitas){
        $data = DB::table('mst_cabang')->select(
            'kd_cabang',
            'nama_cabang'
            )
        ->where('kd_cabang', $kdEntitas)
        ->first();

        return $data;
    }

    public function getDetailBonus($search, $limit=10, $page=1, $id, $tgl, $kd_entitas) {
        $format_tgl = Carbon::parse($tgl)->format('y-m-d');
        $bonus = DB::table('penghasilan_tidak_teratur')
                    ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
                    ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->select(
                        'penghasilan_tidak_teratur.id',
                        'penghasilan_tidak_teratur.id_tunjangan',
                        'penghasilan_tidak_teratur.nip',
                        'mst_karyawan.nama_karyawan',
                        'mst_tunjangan.nama_tunjangan',
                        'nominal',
                        'keterangan',
                        'penghasilan_tidak_teratur.created_at',
                    )
                    ->where('mst_tunjangan.kategori','bonus')
                    ->where(function ($query) use ($search) {
                        $query->where('penghasilan_tidak_teratur.nip', 'like', "%$search%")
                            ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%")
                            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
                            ->orWhere('keterangan', 'like', "%$search%");
                    })
                    ->where('penghasilan_tidak_teratur.kd_entitas', $kd_entitas)
                    ->orderBy('mst_tunjangan.id', 'ASC')
                    ->where('penghasilan_tidak_teratur.id_tunjangan',$id)
                    ->where(DB::raw('DATE(penghasilan_tidak_teratur.created_at)'),$format_tgl)
                    ->paginate($limit);

        return $bonus;
    }
    public function getEditBonus($search, $limit=10, $page=1, $id, $tgl, $kd_entitas) {
        $format_tgl = Carbon::parse($tgl)->format('y-m-d');
        $bonus = DB::table('penghasilan_tidak_teratur')
                    ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
                    ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
                    ->select(
                        'penghasilan_tidak_teratur.id',
                        'penghasilan_tidak_teratur.id_tunjangan',
                        'penghasilan_tidak_teratur.nip',
                        'mst_karyawan.nama_karyawan',
                        'mst_tunjangan.nama_tunjangan',
                        'nominal',
                        'keterangan',
                        'penghasilan_tidak_teratur.created_at',
                    )
                    ->where('mst_tunjangan.kategori','bonus')
                    ->where(function ($query) use ($search) {
                        $query->where('penghasilan_tidak_teratur.nip', 'like', "%$search%")
                            ->orWhere('mst_karyawan.nama_karyawan', 'like', "%$search%")
                            ->orWhere('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                            ->orWhere('nominal', 'like', "%$search%")
                            ->orWhere('keterangan', 'like', "%$search%");
                    })
                    ->where('penghasilan_tidak_teratur.kd_entitas', $kd_entitas)
                    ->orderBy('mst_tunjangan.id', 'ASC')
                    ->where('penghasilan_tidak_teratur.id_tunjangan',$id)
                    ->where(DB::raw('DATE(penghasilan_tidak_teratur.created_at)'),$format_tgl)
                    ->paginate($limit);

        return $bonus;
    }

    public function dataFileExcel() {
        // Berdasarkan karyawan aktif
        // Kepegawaian
        // Berdasarkan kantor
        $is_cabang = auth()->user()->hasRole('cabang');
        $is_pusat = auth()->user()->hasRole('kepegawaian');

        $kd_cabang = DB::table('mst_cabang')
                    ->select('kd_cabang')
                    ->pluck('kd_cabang')
                    ->toArray();
        $karyawan = KaryawanModel::select(
                    'mst_karyawan.nip',
                    'mst_karyawan.nama_karyawan',
                    'mst_karyawan.no_rekening',
                )->when($is_cabang,function($query){
                    $kd_cabang = auth()->user()->kd_cabang;
                    $query->where('kd_entitas', $kd_cabang);
                })->when($is_pusat, function($q2) use ($kd_cabang) {
                        $q2->where(function($q2) use ($kd_cabang) {
                        $q2->whereNotIn('kd_entitas', $kd_cabang)
                            ->orWhere('kd_entitas', null);
                    });
                })
                ->whereNull('tanggal_penonaktifan')
                ->get();
        return $karyawan;
    }

    public function getTHP($nip):int {
        $karyawan = KaryawanModel::where('nip', $nip)
          ->first();
        $dateStart = Carbon::parse($karyawan->tgl_mulai);
        $dateNow = Carbon::now();
        $monthDiff = $dateNow->diffInMonths($dateStart);

        if($monthDiff < 12) {
          return $karyawan->gj_pokok * 2 / $monthDiff;
        } else{
          return $karyawan->gj_pokok * 2;
        }
    }

    public function getTHRId(){
        $tunjangan = TunjanganModel::where('nama_tunjangan', 'like', '%Tunjangan Hari Raya%')
          ->first();
        return $tunjangan->id;
    }

    public function store(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => (int) $data['bulan'],
            'tahun' => (int) $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'] ?? null,
            'created_at' => now()
        ]);
    }

    public function storeUangDuka(array $data)
    {
        return ImportPenghasilanTidakTeraturModel::create([
            'nip' => $data['nip'],
            'id_tunjangan' => $data['id_tunjangan'],
            'bulan' => (int) $data['bulan'],
            'tahun' => (int) $data['tahun'],
            'nominal' => str_replace('.', '', $data['nominal']),
            'keterangan' => $data['keterangan'] . " meninggal",
            'created_at' => now()
        ]);
    }

    public function getPenghasilan($search, $limit=10, $page=1){
        $kd_cabang = auth()->user()->hasRole('cabang') ? auth()->user()->kd_cabang : 'pusat';
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

        $data = DB::table('penghasilan_tidak_teratur')
                ->join('mst_karyawan', 'penghasilan_tidak_teratur.nip', '=', 'mst_karyawan.nip')
                ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', '=', 'mst_tunjangan.id')
                ->leftJoin('mst_cabang', 'mst_cabang.kd_cabang', 'mst_karyawan.kd_entitas')
                ->select(
                    'penghasilan_tidak_teratur.is_lock',
                    'penghasilan_tidak_teratur.id',
                    'penghasilan_tidak_teratur.id_tunjangan',
                    'penghasilan_tidak_teratur.nip',
                    'penghasilan_tidak_teratur.kd_entitas',
                    'penghasilan_tidak_teratur.created_at as tanggal',
                    'penghasilan_tidak_teratur.created_at',
                    'penghasilan_tidak_teratur.bulan',
                    'mst_karyawan.nama_karyawan',
                    'mst_tunjangan.nama_tunjangan',
                    'nominal',
                    'mst_tunjangan.id as tunjangan_id',
                    DB::raw('SUM(nominal) as grand_total'),
                    DB::raw('COUNT(penghasilan_tidak_teratur.id) as total'),
                    'tahun',
                    'keterangan',
                    DB::raw("IF(penghasilan_tidak_teratur.kd_entitas != '000', mst_cabang.nama_cabang, 'Pusat' ) as entitas")
                )
                ->where('mst_tunjangan.kategori','tidak teratur')
                ->where(function ($query) use ($search) {
                    $query->where('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                        ->orWhere('nominal', 'like', "%$search%")
                        ->orWhere('mst_cabang.nama_cabang', 'like', "%$search%");
                })
                ->where(function ($query) use ($kd_cabang, $kode_cabang_arr) {
                    if ($kd_cabang != 'pusat') {
                        $query->where('mst_karyawan.kd_entitas', $kd_cabang);
                    }
                })
                ->groupBy('mst_tunjangan.id', 'penghasilan_tidak_teratur.created_at', 'penghasilan_tidak_teratur.kd_entitas')
                ->orderBy('penghasilan_tidak_teratur.kd_entitas')
                ->orderBy('mst_tunjangan.id', 'ASC')
                ->paginate($limit);
        return $data;
    }

    public function getAllPenghasilan($search, $limit=10, $page=1, $bulan, $createdAt, $idTunjangan, $kd_entitas){
        $createdAt = date('Y-m-d', strtotime($createdAt));
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

        $karyawanRepo = new KaryawanRepository();
        $penghasilan = KaryawanModel::select(
                    'nama_tunjangan',
                    'id_tunjangan',
                    'nominal',
                    'tahun',
                    'bulan',
                    'keterangan',
                    'penghasilan_tidak_teratur.created_at','mst_karyawan.nip',
                    'mst_karyawan.nik',
                    'mst_karyawan.nama_karyawan',
                    'mst_karyawan.kd_bagian',
                    'mst_karyawan.kd_jabatan',
                    'mst_karyawan.kd_entitas',
                    'mst_karyawan.tanggal_penonaktifan',
                    'mst_karyawan.status_jabatan',
                    'mst_karyawan.ket_jabatan',
                    DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"),
                )
                ->join('penghasilan_tidak_teratur', 'mst_karyawan.nip', 'penghasilan_tidak_teratur.nip')
                ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', 'mst_tunjangan.id')
                ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
                ->with('jabatan')
                ->with('bagian')
                ->whereNull('tanggal_penonaktifan')
                ->where('penghasilan_tidak_teratur.id_tunjangan', $idTunjangan)
                ->whereDate('penghasilan_tidak_teratur.created_at', $createdAt)
                ->where('penghasilan_tidak_teratur.kd_entitas', $kd_entitas)
                ->where('penghasilan_tidak_teratur.bulan', $bulan)
                ->where('mst_tunjangan.kategori','tidak teratur')
                ->where(function ($query) use ($search) {
                    $query->where('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                        ->orWhere('nominal', 'like', "%$search%")
                        ->orWhere('c.nama_cabang', 'like', "%$search%");
                })
                // ->where(function ($query) use ($kd_entitas, $kode_cabang_arr) {
                //     if ($kd_entitas != 'pusat') {
                //         $query->where('mst_karyawan.kd_entitas', $kd_entitas);
                //     }
                // })
                ->orderBy('penghasilan_tidak_teratur.kd_entitas')
                ->orderBy('mst_tunjangan.id', 'ASC')
                ->paginate($limit);

        $karyawanRepo->getEntity($penghasilan);

        foreach ($penghasilan as $key => $value) {
            $prefix = match ($value->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            if ($value->jabatan) {
                $jabatan = $value->jabatan->nama_jabatan;
            } else {
                $jabatan = 'undifined';
            }

            $ket = $value->ket_jabatan ? "({$value->ket_jabatan})" : '';

            if (isset($value->entitas->subDiv)) {
                $entitas = $value->entitas->subDiv->nama_subdivisi;
            } elseif (isset($value->entitas->div)) {
                $entitas = $value->entitas->div->nama_divisi;
            } else {
                $entitas = '';
            }

            if ($jabatan == 'Pemimpin Sub Divisi') {
                $jabatan = 'PSD';
            } elseif ($jabatan == 'Pemimpin Bidang Operasional') {
                $jabatan = 'PBO';
            } elseif ($jabatan == 'Pemimpin Bidang Pemasaran') {
                $jabatan = 'PBP';
            } else {
                $jabatan = $value->jabatan ? $value->jabatan->nama_jabatan : 'undifined';
            }

            $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $value?->bagian?->nama_bagian . ' ' . $ket;
            $value->display_jabatan = $display_jabatan;
        }
        return $penghasilan;
    }
    public function getAllPenghasilanEdit($search, $limit=10, $page=1, $bulan, $createdAt, $idTunjangan, $kd_entitas){
        $createdAt = date('Y-m-d', strtotime($createdAt));
        $cabangRepo = new CabangRepository;
        $kode_cabang_arr = $cabangRepo->listCabang(true);

        $karyawanRepo = new KaryawanRepository();
        $penghasilan = KaryawanModel::select(
                    'nama_tunjangan',
                    'id_tunjangan',
                    'nominal',
                    'tahun',
                    'bulan',
                    'keterangan',
                    'penghasilan_tidak_teratur.created_at',
                    'penghasilan_tidak_teratur.id as id_penghasilan',
                    'mst_karyawan.nip',
                    'mst_karyawan.nik',
                    'mst_karyawan.nama_karyawan',
                    'mst_karyawan.kd_bagian',
                    'mst_karyawan.kd_jabatan',
                    'mst_karyawan.kd_entitas',
                    'mst_karyawan.tanggal_penonaktifan',
                    'mst_karyawan.status_jabatan',
                    'mst_karyawan.ket_jabatan',
                    DB::raw("IF((SELECT m.kd_entitas FROM mst_karyawan AS m WHERE m.nip = `mst_karyawan`.`nip` AND m.kd_entitas IN(SELECT mst_cabang.kd_cabang FROM mst_cabang)), 1, 0) AS status_kantor"),
                )
                ->join('penghasilan_tidak_teratur', 'mst_karyawan.nip', 'penghasilan_tidak_teratur.nip')
                ->join('mst_tunjangan', 'penghasilan_tidak_teratur.id_tunjangan', 'mst_tunjangan.id')
                ->leftJoin('mst_cabang as c', 'mst_karyawan.kd_entitas', 'c.kd_cabang')
                ->with('jabatan')
                ->with('bagian')
                ->whereNull('tanggal_penonaktifan')
                ->where('penghasilan_tidak_teratur.id_tunjangan', $idTunjangan)
                ->whereDate('penghasilan_tidak_teratur.created_at', $createdAt)
                ->where('penghasilan_tidak_teratur.kd_entitas', $kd_entitas)
                ->where('penghasilan_tidak_teratur.bulan', $bulan)
                ->where('mst_tunjangan.kategori','tidak teratur')
                ->where(function ($query) use ($search) {
                    $query->where('mst_tunjangan.nama_tunjangan', 'like', "%$search%")
                        ->orWhere('nominal', 'like', "%$search%")
                        ->orWhere('c.nama_cabang', 'like', "%$search%");
                })
                // ->where(function ($query) use ($kd_entitas, $kode_cabang_arr) {
                //     if ($kd_entitas != 'pusat') {
                //         $query->where('mst_karyawan.kd_entitas', $kd_entitas);
                //     }
                // })
                ->orderBy('penghasilan_tidak_teratur.kd_entitas')
                ->orderBy('mst_tunjangan.id', 'ASC')
                ->get();

        $karyawanRepo->getEntity($penghasilan);

        foreach ($penghasilan as $key => $value) {
            $prefix = match ($value->status_jabatan) {
                'Penjabat' => 'Pj. ',
                'Penjabat Sementara' => 'Pjs. ',
                default => '',
            };

            if ($value->jabatan) {
                $jabatan = $value->jabatan->nama_jabatan;
            } else {
                $jabatan = 'undifined';
            }

            $ket = $value->ket_jabatan ? "({$value->ket_jabatan})" : '';

            if (isset($value->entitas->subDiv)) {
                $entitas = $value->entitas->subDiv->nama_subdivisi;
            } elseif (isset($value->entitas->div)) {
                $entitas = $value->entitas->div->nama_divisi;
            } else {
                $entitas = '';
            }

            if ($jabatan == 'Pemimpin Sub Divisi') {
                $jabatan = 'PSD';
            } elseif ($jabatan == 'Pemimpin Bidang Operasional') {
                $jabatan = 'PBO';
            } elseif ($jabatan == 'Pemimpin Bidang Pemasaran') {
                $jabatan = 'PBP';
            } else {
                $jabatan = $value->jabatan ? $value->jabatan->nama_jabatan : 'undifined';
            }

            $display_jabatan = $prefix . ' ' . $jabatan . ' ' . $entitas . ' ' . $value?->bagian?->nama_bagian . ' ' . $ket;
            $value->display_jabatan = $display_jabatan;
        }
        return $penghasilan;
    }

    public function lockBonus(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $tanggal = $data['tanggal'];
        $kd_entitas = $data['entitas'];
        return DB::table('penghasilan_tidak_teratur')->where('id_tunjangan', $idTunjangan)
            ->where(DB::raw('DATE(penghasilan_tidak_teratur.created_at)'), $tanggal)
            ->where('kd_entitas', $kd_entitas)
            ->update([
                'is_lock' => 1
            ]);
    }
    public function unlockBonus(array $data){
        $idTunjangan = $data['id_tunjangan'];
        $tanggal = $data['tanggal'];
        $kd_entitas = $data['entitas'];
        return DB::table('penghasilan_tidak_teratur')->where('id_tunjangan', $idTunjangan)
            ->where(DB::raw('DATE(penghasilan_tidak_teratur.created_at)'), $tanggal)
            ->where('kd_entitas', $kd_entitas)
            ->update([
                'is_lock' => 0
            ]);
    }

    public function lock(array $data)
    {
        $idTunjangan = $data['id_tunjangan'];
        $tanggal = $data['tanggal'];
        $bulan = (int)date("m", strtotime($tanggal));
        $tahun = date("Y", strtotime($tanggal));
        return DB::table('penghasilan_tidak_teratur')->where('id_tunjangan', $idTunjangan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->update([
                'is_lock' => 1
            ]);
    }
    public function unlock(array $data)
    {
        $idTunjangan = $data['id_tunjangan'];
        $tahun = $data['tahun'];
        // $tanggal = $data['tanggal'];
        // $bulan = (int)date("m", strtotime($tanggal));
        // $tahun = date("Y", strtotime($tanggal));
        $createdAt = $data['createdAt'];
        $bln = $data['bulan'];
        $entias = $data['kdEntitas'];
        return DB::table('penghasilan_tidak_teratur')->where('id_tunjangan', $idTunjangan)
            ->where('bulan', $bln)
            ->where('tahun', $tahun)
            ->where('kd_entitas', $entias)
            ->where('created_at', $createdAt)
            ->update([
                'is_lock' => 0
            ]);
    }

    public function TunjanganSelected($id)
    {
        $data = TunjanganModel::find($id);
        return $data;
    }

    public function getCabang($kdCabang){
        $data = CabangModel::select('kd_cabang','nama_cabang')->where('kd_cabang',$kdCabang)->first();
        return $data;
    }
}
