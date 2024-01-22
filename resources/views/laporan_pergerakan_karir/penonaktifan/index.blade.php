@extends('layouts.app-template')

@section('content')
        <div class="head mt-5">
            <div class="heading">
                <h2 class="card-title">Laporan Penonaktifan</h2>
            </div>
            <div class="breadcrumb">
                <a href="#" class="text-sm text-gray-500">Laporan</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="#" class="text-sm text-gray-500 font-bold">Laporan Pergerakan Karir</a>
                <i class="ti ti-circle-filled text-theme-primary"></i>
                <a href="{{ route('laporan-penonaktifan.index') }}" class="text-sm text-gray-500 font-bold">Laporan Penonaktifan</a>
               </div>  
        </div>
        <div class="body-pages">
            <div class="card">
                <form id="form" action="" method="get">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Dari</label>
                                <input type="date" name="start_date" id="start_date" class="form-input"
                                    value="{{ old('start_date', Request::get('start_date')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-box">
                                <label for="">Sampai</label>
                                <input type="date" name="end_date" id="end_date" class="form-input"
                                    value="{{ old('end_date', Request::get('end_date')) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pt-5">
                            <button class="btn btn-primary" type="submit"><i class="ti ti-filter"></i>Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
            @isset($data)
            <div class="row mt-1">
                <div class="table-wrapping">
                    <div class="layout-component">
                        <div class="shorty-table">
                            <label for="">Show</label>
                            <select name="page_length" id="page_length" class="form-input">
                                <option value="10"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 10 ? 'selected' : '' }} @endisset>
                                10</option>
                            <option value="20"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 20 ? 'selected' : '' }} @endisset>
                                20</option>
                            <option value="50"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 50 ? 'selected' : '' }} @endisset>
                                50</option>
                            <option value="100"
                                @isset($_GET['page_length']) {{ $_GET['page_length'] == 100 ? 'selected' : '' }} @endisset>
                                100</option>
                            </select>
                            <label for="">entries</label>
                        </div>
                    </div>
                    <table class="tables"  id="table_export">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    NIP
                                </th>
                                <th>
                                    NIK
                                </th>
                                <th>
                                    Nama Karyawan
                                </th>
                                <th>
                                    Kantor Terakhir
                                </th>
                                <th>
                                    Jabatan Terakhir
                                </th>
                                <th>
                                    kategori penonaktifan
                                </th>
                                <th>
                                    Tanggal Penonaktifan
                                </th>
                            </tr>
                        </thead>
                        @php
                        $i = 1;
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 10;
                        $pagination = \App\Helpers\Pagination::generateNumber($page, $page_length);
                        if ($pagination) {
                            $i = $pagination['iteration'];
                        }
                    @endphp
                        <tbody>
                            @php
                            $i = 1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    {{ $item->nip }}
                                </td>
                                <td>
                                    {{ $item->nik }}
                                </td>
                                <td>
                                    {{ $item->nama_karyawan }}
                                </td>
                                <td>{{ $item->entitas->type == 2 ? $item->entitas->cab->nama_cabang : 'Pusat' }}
                                </td>
                                @php
                                    $prefix = match ($item->status_jabatan) {
                                        'Penjabat' => 'Pj. ',
                                        'Penjabat Sementara' => 'Pjs. ',
                                        default => '',
                                    };

                                    $jabatan = $item->jabatan->nama_jabatan;

                                    $ket = $item->ket_jabatan ? "({$item->ket_jabatan})" : '';

                                    if (isset($item->entitas->subDiv)) {
                                        $entitas = $item->entitas->subDiv->nama_subdivisi;
                                    } elseif (isset($item->entitas->div)) {
                                        $entitas = $item->entitas->div->nama_divisi;
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
                                        $jabatan = $item->jabatan->nama_jabatan;
                                    }
                                @endphp
                                <td>{{ $prefix . $jabatan }} {{ $entitas }}
                                    {{ $item?->bagian?->nama_bagian }} {{ $ket }}</td>
                                <td>{{ $item->kategori_penonaktifan ?? '-' }}</td>
                                <td>
                                    <span style="display: none;">{{ date('Ymd', strtotime($item->tanggal_penonaktifan)) }}</span>
                                    {{ $item->tanggal_penonaktifan != null ? date('d M Y', strtotime($item->tanggal_penonaktifan)) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="table-footer">
                        <div class="showing">
                            Showing {{ $start }} to {{ $end }} of {{ $data->total() }} entries
                        </div>
                        <div>
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $data->links('pagination::tailwind') }}
                        @endif
                        </div>
                    </div> --}}
                </div>
            </div>
        </form>
            @endisset
        </div>

@endsection

@push('extraScript')
<script src="{{ asset('style/assets/js/table2excel.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
    <script>
        $('#page_length').on('change', function() {
            $('#form').submit()
        })

        var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;

        $("#table_export").DataTable({
            dom: "Bfrtip",
            iDisplayLength: -1,
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    filename: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    message: 'Rekapitulasi Beban Asuransi\n ' + start_date + ' ' + end_date,
                    text: 'Excel',
                    header: true,
                    footer: true,
                    customize: function(xlsx, row) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    filename: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    text: 'PDF',
                    footer: true,
                    paperSize: 'A4',
                    orientation: 'landscape',
                    customize: function(doc) {
                        var now = new Date();
                        var jsDate = now.getDate() + ' / ' + (now.getMonth() + 1) + ' / ' + now
                            .getFullYear();

                        doc.styles.tableHeader.fontSize = 10;
                        doc.defaultStyle.fontSize = 9;
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';

                        doc.content[1].margin = [0, 0, 0, 0];
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join(
                            '*').split('');

                        doc['footer'] = (function(page, pages) {
                            return {
                                columns: [{
                                        alignment: 'left',
                                        text: ['Created on: ', {
                                            text: jsDate.toString()
                                        }]
                                    },
                                    {
                                        alignment: 'right',
                                        text: ['Page ', {
                                            text: page.toString()
                                        }, ' of ', {
                                            text: pages.toString()
                                        }]
                                    }
                                ],
                                margin: 20
                            }
                        });

                    }
                },
                {
                    extend: 'print',
                    title: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    filename: 'Laporan Pergerakan Karir - Penonaktifan (Dari ' + start_date + ' Sampai ' + end_date +
                        ')',
                    text: 'print',
                    footer: true,
                    paperSize: 'A4',
                    customize: function(win) {
                        var last = null;
                        var current = null;
                        var bod = [];

                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');

                        style.type = 'text/css';
                        style.media = 'print';

                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(win.document.createTextNode(css));
                        }

                        head.appendChild(style);

                        $(win.document.body).find('h1')
                            .css('text-align', 'center')
                            .css('font-size', '16pt')
                            .css('margin-top', '20px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10pt')
                            .css('width', '1000px')
                            .css('border', '#bbbbbb solid 1px');
                        $(win.document.body).find('tr:nth-child(odd) th').each(function(index) {
                            $(this).css('text-align', 'center');
                        });
                    }
                }
            ]
        });

        $(".buttons-excel").attr("class", "btn btn-success mb-2");
        $(".buttons-pdf").attr("class", "btn btn-success mb-2");
        $(".buttons-print").attr("class", "btn btn-success mb-2");

</script>
@endpush
