@section('title', 'Penjualan')

<x-app-layout>
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3 class="text-dark mb-4">Penjualan</h3>
        <div class="card shadow">
            <div class="card-header py-3">
                <div class="row text-center text-sm-start">
                    <div class="col-sm-5 col-12 mb-3 mb-md-0">
                        <p class="text-primary m-0 fw-bold mt-2">Daftar
                            Penjualan</p>
                    </div>
                    <div class="col-sm-7 col-12 mb-2 mb-md-0">
                        <div class="d-sm-flex justify-content-sm-end">
                            <a href="{{ route('transaction.index') }}" class="btn btn-primary btn-icon-split"><span
                                    class="icon text-white-50"><i class="fas fa-plus"></i></span>
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
                                <th width="15%">Tanggal</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th width="20%">Kasir</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @includeIf('sale.detail')

    @push('scripts')
        <script>
            const toastTrigger = document.getElementById('liveToastBtn')
            const toastLiveExample = document.getElementById('liveToast')
            if (toastTrigger) {
                toastTrigger.addEventListener('click', () => {
                    const toast = new bootstrap.Toast(toastLiveExample)

                    toast.show()
                })
            }


            let table, detailTable;

            $(function() {
                table = $('#dataTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "{{ route('sale.data') }}",
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        searchable: false
                    }, {
                        data: 'date'
                    }, {
                        data: 'total_items'
                    }, {
                        data: 'total_price'
                    }, {
                        data: 'cashier'
                    }, {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        sortable: false
                    }]
                });

                detailTable = $('#detailDataTable').DataTable({
                    bSort: false,
                    dom: 'Brt',
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: 'code'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'price'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'subtotal'
                        },
                    ]
                })
            });

            function showDetail(url) {
                $('#detailModal').modal('show');

                detailTable.ajax.url(url);
                detailTable.ajax.reload();
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
