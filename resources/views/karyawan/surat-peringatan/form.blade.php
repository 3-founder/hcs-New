@include('vendor.select2')
    @csrf
    <div class="row m-0">
        <div class="col-md-4 form-group">
            <label for="">Karyawan:</label>
            <select name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" @disabled($ro ?? null)></select>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" disabled>
        </div>
        <div class="col-md-4 form-group">
            <label for="kantor">Kantor</label>
            <input type="text" class="form-control" id="kantor" disabled>
        </div>
        <div class="col-md-4 form-group">
            <label for="no_sp">No. SP</label>
            <input type="text" name="no_sp" id="no_sp" class="form-control @error('no_sp') is-invalid @enderror" value="{{ $sp?->no_sp }}" @disabled($ro ?? null) autofocus>

            @error('no_sp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 form-group">
            <label for="tanggal_sp">Tanggal SP</label>
            @if (!$ro)
                <input type="date" name="tanggal_sp" id="tanggal_sp" class="form-control @error('tanggal_sp') is-invalid @enderror" >
            @else
                <input type="text" class="form-control" value="{{ $sp?->tanggal_sp?->format('d M Y') }}" disabled>
            @endif

            @error('tanggal_sp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 form-group">
            <label for="pelanggaran">Pelanggaran</label>
            <input type="text" name="pelanggaran" id="pelanggaran" class="form-control @error('pelanggaran') is-invalid @enderror" value="{{ $sp?->pelanggaran }}" @disabled($ro ?? null)>

            @error('pelanggaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 form-group">
            <label for="sanksi">Sanksi</label>
            <input type="text" name="sanksi" id="sanksi" class="form-control @error('sanksi') is-invalid @enderror" value="{{ $sp?->sanksi }}" @disabled($ro ?? null)>

            @error('sanksi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if ($ro)
            <div class="col-md-12">
                <label for="file_sk">Dokumen SK</label>
                @if ($sp->file_sk != null)
                    @php
                        $fileparts = pathinfo(asset('..') . '/upload/sp/'. $sp->file_sk);
                    @endphp
                    @if ($fileparts['extension'] == 'pdf')
                        <iframe src="{{ asset('/upload/sp/'. $sp->file_sk) }}" width="100%" height="650px"></iframe>
                    @else
                        <img src="{{ asset('/upload/sp/' . $sp->file_sk) }}" alt="" width="100%">
                    @endif
                @else
                    <input type="text" class="form-control" disabled value="-">
                @endif
            </div>
        @else
            <div class="col-md-4 form-group">
                <label for="file_sk">Dokumen SK</label>
                <div class="custom-file col-md-12">
                    <input type="file" name="file_sk" class="custom-file-input" id="validatedCustomFile" accept=".pdf">
                    <label class="custom-file-label overflow-hidden" for="validatedCustomFile">Choose file(.pdf) ...</label>
                </div>  
                @error('file_sk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endif
    </div>

    <div class="row m-0">
        <div class="col-md-12 text-left">
            @if($ro)
                <a href="{{ route('surat-peringatan.index') }}" class="btn btn-primary">Kembali</a>
            @else
                <button type="submit" class="btn btn-primary">Simpan</button>
            @endif
        </div>
    </div>

@push('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const nipSelect = $('#nip').select2({
        ajax: {
            url: '{{ route('api.select2.karyawan') }}',
            data: function(params) {
                return {
                    search: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true,
        },
        templateResult: function(data) {
            if(data.loading) return data.text;
            return $(`
                <span>${data.nama}<br><span class="text-secondary">${data.id} - ${data.jabatan}</span></span>
            `);
        }
    });

    @isset($sp)
        nipSelect.append(`
            <option value="{{$sp->karyawan?->nip}}">{{$sp->karyawan?->nip}} - {{$sp->karyawan?->nama_karyawan}}</option>
        `).trigger('change');
        $(window).on('load', function(){
            nipSelect.trigger('select2:select')
        })
    @endisset

    $('#nip').on('select2:select', function() {
        const nip = $(this).val();
        let jabatan = '';

        $.ajax({
            url: `{{ route('api.karyawan') }}?nip=${nip}`,
            dataType: 'JSON',
            success(res) {
                const entitas = res.data.entitas;
                const bagian = res.data.bagian?.nama_bagian || '';
                jabatan = res.data?.nama_jabatan || '';

                $('#kantor').val('Pusat');

                if(Object.hasOwn(entitas, 'subDiv')) {
                    $('#jabatan').val(`${jabatan} ${bagian} ${entitas.subDiv.nama_subdivisi}`);
                    return;
                }

                if(Object.hasOwn(entitas, 'div')) {
                    $('#jabatan').val(`${jabatan} ${bagian} ${entitas.div.nama_divisi}`);
                    return;
                }

                if(Object.hasOwn(entitas, 'cab')) {
                    $('#jabatan').val(`${jabatan} ${bagian}`);
                    $('#kantor').val(entitas.cab.nama_cabang);
                    return;
                }

                $('#jabatan').val(`${jabatan} ${bagian}`);
            }
        });
    });

    $("#validatedCustomFile").on('change', function(e){
        var ext = this.value.match(/\.([^\.]+)$/)[1];
        if(ext != 'pdf'){
            Swal.fire({
                title: 'Terjadi Kesalahan.',
                text: 'File harus PDF',
                icon: 'error'
            })
        }
    })
    
    document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var name = document.getElementById("validatedCustomFile").files[0].name;
            var ext = name.match(/\.([^\.]+)$/)[1];
            var nextSibling = e.target.nextElementSibling
            if(ext == 'pdf'){
                nextSibling.innerText = name
            } else {
                nextSibling.innerText = ''
                $("#validatedCustomFile").val('Choose File(.pdf) ...')
            }
        });
</script>
@endpush
