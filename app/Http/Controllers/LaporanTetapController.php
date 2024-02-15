<?php

namespace App\Http\Controllers;

use App\Exports\RekapTetapExport;
use App\Models\CabangModel;
use App\Repository\LaporanTetapRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTetapController extends Controller
{
    private $repo;
    private $cabang;

    public function __construct()
    {
        $this->repo = new LaporanTetapRepository;
        $this->cabang = CabangModel::get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 10;
        $page = $request->has('page') ? $request->get('page') : 1;
        $year = $request->get('tahun');
        $month = $request->get('bulan');
        if ($year == 0 || $month == 0) {
            $request->validate([
                'tahun' => 'not_in:0',
                'bulan' => 'not_in:0',
            ]);
        }
        Session::put('year', $year);
        Session::put('month', $month);

        $kantor = null;
        if ($request->has('cabang')) {
            $kantor = $request->cabang;
        } else {
            if(auth()->user()->hasRole('cabang')){
                $kantor = auth()->user()->kd_cabang;
            } else {
                $kantor = '000';
            }
        }
        Session::put('kantor', $kantor);

        $kategori = $request->has('kategori') ? $request->get('kategori') : null;
        Session::put('kategori', $kategori);
        $search = $request->get('q');
        $search = str_replace("'", "\'", $search);
        $data = $request->has('tahun') && $request->has('bulan') ? $this->repo->get($kantor, $kategori, $search, $limit, false, intval($year), intval($month)) : null;
        $footer = $request->has('tahun') && $request->has('bulan') ? $this->repo->getTotal($kantor, $kategori, $search, $limit, false, intval($year), intval($month)) : null;
        $cabang = $this->cabang;
        return view('rekap-tetap.index', [
            'cabang' => $cabang,
            'data' => $data,
            'grandTotal' => $footer,
            'is_cetak' => false,
        ]);
    }

    public function cetak(){
        $year = Session::get('year');
        $month = Session::get('month');
        $kantor = Session::get('kantor');
        $kategori = Request()->get('kategori');
        $limit = null;
        $search = null;
        $data = $this->repo->get($kantor, $kategori, $search, $limit, true, intval($year), intval($month));
        $grandtotal = $this->repo->getTotal($kantor, $kategori, $search, $limit, false, intval($year), intval($month));
        $month_name = getMonth($month);

        $showKantor = '';
        if($kantor == '000')
            $showKantor = 'Kantor Pusat';
        else if($kantor == 'keseluruhan')
            $showKantor = 'Keseluruhan';
        else
            $showKantor = 'Kantor ' . CabangModel::where('kd_cabang', $kantor)->first()?->nama_cabang;

        $filename = 'Laporan Rekap ' . $showKantor .' '. $month_name . ' Tahun ' . $year.' ('.$kategori.')';
        return Excel::download(new RekapTetapExport($data, $grandtotal), $filename . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
