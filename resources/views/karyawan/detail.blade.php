@extends('layouts.template')
@section('content')
    <div class="card-header">
        <div class="card-header">
            <h5 class="card-title">Detail Data Karyawan</h5>
            <p class="card-title"><a href="">Manajemen Karyawan</a> > <a href="{{ route('karyawan.index') }}">Karyawan</a> > Detail</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('karyawan.show', $karyawan->nip) }}" method="POST" enctype="multipart/form-data" name="karyawan" class="form-group">
            @csrf
            @method('PUT')
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Biodata Diri Karyawan</h6>
                </div>
            </div>
            @php
                $no = 1;
                function rupiah($angka){
                    $hasil_rupiah = number_format($angka, 0, ",", ".");
                    return $hasil_rupiah;
                }
                $totalGaji = $karyawan->gaji_pokok + $karyawan->gaji_penyesuaian;
                $tj = DB::table('tunjangan_karyawan')
                    ->join('mst_tunjangan', 'mst_tunjangan.id', '=', 'tunjangan_karyawan.id_tunjangan')
                    ->where('nip', $karyawan->nip)
                    ->get();
            @endphp
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NIP</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->nip }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NIK</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->nik }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Nama Karyawan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->nama_karyawan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Tempat, Tanggal Lahir</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->tmp_lahir }}, {{ $karyawan->tgl_lahir->format('d F Y') }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Agama</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan?->agama?->agama ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Jenis Kelamin</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->jk }}">
                </div>
            </div>
            @php
                if ($karyawan->status == 'K' || $karyawan->status == 'Kawin') {
                    $status = 'Kawin';
                } elseif ($karyawan->status == 'TK' || $karyawan->status == 'Belum Kawin') {
                    $status = 'Belum Kawin';
                }
            @endphp
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Pernikahan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control"  value="{{ $status ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Kewarganegaraan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control"  value="{{ $karyawan->kewarganegaraan }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Alamat KTP</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->alamat_ktp }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Alamat Sekarang</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->alamat_sek ?? '-' }}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>No Rekening & NPWP</h6>
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Nomor Rekening</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->no_rekening ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">NPWP</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ npwp($karyawan->npwp) ?? '-' }}">
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Karyawan</h6>
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Kantor</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->type == 2 ? "Cabang {$karyawan->entitas->cab->nama_cabang}" : 'Pusat' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->jabatan->nama_jabatan }}">
                </div>
            </div>
            @if(isset($karyawan->entitas->div))
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">Divisi</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->div->nama_divisi }}">
                    </div>
                </div>
            @endif
            @if(isset($karyawan->entitas->subDiv))
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-2">Sub Divisi</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control" value="{{ $karyawan->entitas->subDiv->nama_subdivisi }}">
                    </div>
                </div>
            @endif
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Pangkat</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->panggol?->pangkat ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Golongan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->panggol?->golongan ?? '-' }}">
                </div>
            </div>
            @if($karyawan->bagian)
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Bagian</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->bagian->nama_bagian }}">
                </div>
            </div>
            @endif
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Karyawan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->status_karyawan}}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Status Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->status_jabatan}}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Keterangan Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->ket_jabatan ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Tanggal Mulai</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan?->tgl_mulai?->format('d F Y') ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">SK Pengangkatan</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->skangkat ?? '-' }}">
                </div>
            </div>
            @php
                use Carbon\Carbon;
                $mulaKerja = Carbon::create($karyawan->tgl_mulai);
                $waktuSekarang = Carbon::now();

                $hitung = $waktuSekarang->diff($mulaKerja);
                $masaKerja = $hitung->format('%y Tahun | %m Bulan | %d Hari');

            @endphp
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Masa Kerja</label>
                <div class="col-sm-10">
                    @if (isset($karyawan->tgl_mulai) != null)
                        <input type="text" disabled class="form-control" value="{{ $masaKerja }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <hr>
            <div class="row m-0 ">
                <div class="col-lg-12">
                    <h6>Data Tunjangan Karyawan</h6>
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Kartu Peserta Jamsostek (KPJ)</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->kpj ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-0">Jaminan Kesehatan Nasional (JKN)</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="{{ $karyawan->jkn ?? '-' }}">
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Gaji Pokok</label>
                <div class="col-sm-10">
                    @if (isset($karyawan->gj_pokok) != null)
                        <input type="text" disabled class="form-control" value="{{ rupiah($karyawan->gj_pokok) }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <div class="row m-0 mt-2">
                <label class="col-sm-2 mt-2">Gaji Penyesuaian</label>
                <div class="col-sm-10">
                    @if (isset($karyawan->gj_penyesuaian) != null)
                        <input type="text" disabled class="form-control" value="{{ rupiah($karyawan->gj_penyesuaian) }}">
                    @else
                        <input type="text" disabled class="form-control" value="-">
                    @endif
                </div>
            </div>
            <br>
                @if (isset($tj))
                    @foreach ($tj as $item)
                        <div class="row m-0 mt-2">
                            <label class="col-sm-2 mt-2">Tunjangan {{ $no++ }}</label>
                            <div class="col-sm-5">
                                <input type="text" disabled class="form-control" value="{{ $item->nama_tunjangan }}">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" disabled class="form-control" value="Rp. {{ rupiah($item->nominal) }}">
                            </div>
                        </div>
                        @php
                            if($item->nama_tunjangan != 'DPP'){
                                $totalGaji += $item->nominal;
                            }
                        @endphp
                    @endforeach
                @endif
            <br>
            <div class="row m-0 mt-2">
                <label for="" class="col-sm-2 mt-2">Total Gaji</label>
                <div class="col-sm-10">
                    <input type="text" disabled class="form-control" value="Rp. {{ rupiah($totalGaji) }}">
                </div>
            </div>

            @if ($karyawan->status == 'Kawin' && $suis != null)
                <hr>
                <div class="row m-0 ">
                    <div class="col-lg-12">
                        <h6>Data Keluarga</h6>
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Status Pasangan</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->enum }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Nama</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->nama }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">SK Tunjangan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" disabled value="{{ $suis->sk_tunjangan }}">
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->tgl_lahir }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Alamat</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->alamat }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Pekerjaan</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->pekerjaan }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>
                <div class="row m-0 mt-2">
                    <label class="col-sm-2 mt-0">Jumlah Anak</label>
                    <div class="col-sm-10">
                        @if (isset($suis) != null)
                            <input type="text" disabled class="form-control" value="{{ $suis->jml_anak }}">
                        @else
                            <input type="text" disabled class="form-control" value="-">
                        @endif
                    </div>
                </div>

                @if (count($data_anak) > 0)
                    <br>
                    @foreach ($data_anak as $key => $item)
                        @php
                            $index = ($key == 0) ? 'Pertama' : 'Kedua';
                        @endphp
                        <div class="row m-0 mt-2">
                            <label class="col-sm-2 mt-0">Nama Anak {{ $index }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{{ $item->nama }}">
                            </div>
                        </div>
                        <div class="row m-0 mt-2">
                            <label class="col-sm-2 mt-0">Tanggal Lahir Anak {{ $index }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{{ $item->tgl_lahir }}">
                            </div>
                        </div>
                        <div class="row m-0 mt-2">
                            <label class="col-sm-2 mt-0">SK Tunjangan Anak {{ $index }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{{ $item->sk_tunjangan }}">
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
            <br>
            <hr>

            <div class="row m-0 mt-3">
                <div class="col-lg-12">
                    <h6 class="mt-2">Histori</h6>
                </div>
            </div>

            <div class="row m-0 mt-2">
                <div class="col-12">
                    <p class="m-0 mt-2 text-muted">Histori Penjabat Sementara</p>
                    <div class="table-responsive overflow-hidden pt-2">
                        <table class="table text-center cell-border stripe" id="pjs-table" style="width: 100%; word-break: break-all;">
                            <thead>
                                <tr>
                                    <th style="background-color: #CCD6A6; text-align: center;">No SK</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Jabatan</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Mulai</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Berakhir</th>
                                    <th style="background-color: #CCD6A6; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pjs as $data)
                                <tr>
                                    <td>{{ $data->no_sk }}</td>
                                    <td>{{ jabatanLengkap($data) }}</td>
                                    <td>{{ $data->tanggal_mulai->format('d M Y') }}</td>
                                    <td>{{ $data->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ !$data->tanggal_berakhir ? 'Aktif' : 'Nonaktif' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <a href="/karyawan">
                    <button type="button" class="btn btn-info">Kembali</button>
                </a>
            </div>
        </form>
    </div>
@endsection

@push('script')
<script>
    $('#pjs-table').DataTable();
</script>
@endpush
