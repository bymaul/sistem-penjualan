@section('title', 'Pengguna')

<x-app-layout>
    <div class="container-fluid">
        <h3 class="text-dark mb-4">Pengguna</h3>
        <div class="card shadow">
            <div class="card-header py-3">
                <div class="row text-center text-sm-start">
                    <div class="col-sm-5 col-12 mb-3 mb-md-0">
                        <p class="text-primary m-0 fw-bold mt-2">Daftar
                            Pengguna</p>
                    </div>
                    <div class="col-sm-7 col-12 mb-2 mb-md-0">
                        <div class="d-sm-flex justify-content-sm-end">
                            <a onclick="addForm('{{ route('user.store') }}')"
                                class="btn btn-primary btn-icon-split"><span class="icon text-white-50"><i
                                        class="fas fa-plus"></i></span>
                                <span class="text">Tambah</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('user.form')

    @push('scripts')
        <script>
            let table;

            $(function() {
                table = $('#dataTable').DataTable({
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('user.data') }}",
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    }, {
                        data: 'name'
                    }, {
                        data: 'email'
                    }, {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                    }]
                });

                $('#userModal').on('submit', function(e) {
                    if (!e.preventDefault()) {
                        $.ajax({
                            url: $('#userModal form').attr('action'),
                            type: 'post',
                            data: $('#userModal form').serialize(),
                            success: function(data) {
                                $('#userModal').modal('hide');
                                table.ajax.reload();
                            },
                            error: function() {
                                alert('Tidak dapat menyimpan data!');
                                return;
                            }
                        });
                    }
                })
            });

            function addForm(url) {
                $('#userModal').modal('show');
                $('#userModalLabel').text('Tambah Pengguna');

                $('#userModal Form')[0].reset();
                $('#userModal Form').attr('action', url);
                $('#userModal [name=_method]').val('post');
                $('#userModal [name=name]').focus();

                $('#userModal [name=password]').attr('required', true);
                $('#userModal [name=password_confirmation]').attr('required', true);
            }

            function editForm(url) {
                $('#userModal').modal('show');
                $('#userModalLabel').text('Perbarui Pengguna');

                $('#userModal Form')[0].reset();
                $('#userModal Form').attr('action', url);
                $('#userModal [name=_method]').val('put');
                $('#userModal [name=name]').focus();

                $('#userModal [name=password]').attr('required', false);
                $('#userModal [name=password_confirmation]').attr('required', false);

                $.get(url)
                    .done((response) => {
                        $('#userModal [name=name]').val(response.name);
                        $('#userModal [name=email]').val(response.email);
                    })
                    .fail((error) => {
                        alert('Tidak dapat menampilkan data!');
                        return;
                    });
            }

            function deleteData(url) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.post(url, {
                            '_method': 'delete',
                            '_token': '{{ csrf_token() }}'
                        })
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((error) => {
                            alert('Tidak dapat menghapus data!');
                            return;
                        })
                }
            }
        </script>
    @endpush
</x-app-layout>
