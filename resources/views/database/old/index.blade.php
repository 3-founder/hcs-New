@extends('layouts.template')

@section('content')
<div class="card-header">
    <h5 class="card-title">Database</h5>
    <p class="card-title"><a href="/">Setting </a> > <a href="{{ route('database.index') }}">Database</a>
</div>

<div class="card-body">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4">Daftar Backup Database</h6>
                    @if (count($database->rollbacks) > 0)
                        <button class="btn btn-primary rounded" data-toggle="modal" data-target="#rollback-modal">Rollback Database</button>
                    @endif
                    <div class="table-responsive overflow-hidden pt-4">
                        <table class="table whitespace-nowrap" id="backup-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Backup</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($database->backups as $backup)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>{{ $backup['time']->format('d M Y - H:i') }}</td>
                                    <td>
                                        @if ($database->position['name'] != substr($backup['name'], '0', '-4'))
                                        <a href="{{ route('database.restore', $backup['id']) }}" class="btn btn-sm btn-primary btn-restore">
                                            Restore
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @isset($database->position['type'])
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4">Informasi Database</h6>
                    <ul class="list-group">
                        <li class="list-group-item">
                            @php
                                $type = $database->position['type'];
                                $stClass = $type == 'backups' ? 'primary' : 'success';
                                $stWord = $type == 'backups' ? 'backup' : 'rollback';
                            @endphp

                            Status: <span class="badge badge-{{ $stClass }}">{{ $stWord }}</span>
                        </li>
                        <li class="list-group-item">
                            Nama: <small class="text-muted">{{ $database->position['name'] }}</small>
                        </li>
                        <div class="list-group-item text-center">
                            <form action="{{ route('database.checkout') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" id="checkout-btn">Checkout</button>
                            </form>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        @endisset
    </div>
</div>

@include('database.modal')
@endsection

@push('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#backup-table').DataTable();

    $('#backup-table').on('click', '.btn-restore', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');

        Swal.fire({
            icon: 'question',
            title: 'Apakah anda yakin?',
            text: 'Aksi akan mereset semua data aplikasi berdasarkan backup terpilih',
        })
        .then((result) => {
            if(result.isConfirmed) window.location.href = url;
        });
    });

    $('#checkout-btn').click(function(e) {
        e.preventDefault();
        const form = $(this).parent();

        Swal.fire({
            icon: 'question',
            title: 'Apakah anda yakin?',
            text: 'Aksi akan melakukan checkout permanen pada database',
        })
        .then((result) => {
            if(result.isConfirmed) form.submit();
        });
    });
</script>
@endpush
