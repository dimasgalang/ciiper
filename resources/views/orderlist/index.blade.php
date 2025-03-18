<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
<!-- Page Wrapper -->
@include('sweetalert::alert')
<div id="wrapper">
@include('layout.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            @include('layout.navbar')
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Order List</h1>
                    <div>
                    <!-- <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#importModal"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Import Order List</a> -->
                    <a href="{{ route('orderlist.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Order List</a>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
                        <h6 class="m-0 font-weight-bold text-primary">Order List Data</h6>
                        <form method="GET" id="form-void">
                                <select name="void" id="void" class="form-control" onchange="document.getElementById('form-void').submit()" style="width: 300px;">
                                    <option disabled selected hidden>Select Status</option>
                                    <option value="false" {{ app('request')->input('void') == 'false'  ? 'selected' : ''}}>Active</option>
                                    <option value="true" {{ app('request')->input('void') == 'true'  ? 'selected' : ''}}>Void</option>
                                </select>
                        </form>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif

                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif

                        @if ($message = Session::get('warning'))
                        <div class="alert alert-warning alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif

                        @if ($message = Session::get('info'))
                        <div class="alert alert-info alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>	
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order List</th>
                                        <th>Master PO</th>
                                        <th>Factory</th>
                                        <th>Lot</th>
                                        <th>PO Buyer</th>
                                        <th>DC PO Qty (Dzn)</th>
                                        <th>DC PO Qty (Pcs)</th>
                                        <th>Carton Qty (Pcs)</th>
                                        <th>Ex Factory</th>
                                        <th>Vsl Date</th>
                                        <th>Wash Type</th>
                                        <th>Bordir Type</th>
                                        <th>Line</th>
                                        <th>SMV</th>
                                        <th>Target Qty</th>
                                        <th>Production Day</th>
                                        <th>Status</th>
                                        <!-- <th>Fabric Mill</th>
                                        <th>Fabrication</th>
                                        <th>PO Fabric</th>
                                        <th>ETD</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderlists as $orderlist)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $orderlist->order_list }}</td>
                                        <td>{{ $orderlist->po_master }}</td>
                                        <td>{{ $orderlist->factory_name }}</td>
                                        <td>{{ $orderlist->lot_no }}</td>
                                        <td>{{ $orderlist->pobuyer_no }}</td>
                                        <td>{{ number_format($orderlist->dcpo_qty/12,2) }}</td>
                                        <td>{{ $orderlist->dcpo_qty }}</td>
                                        <td>{{ $orderlist->carton_qty }}</td>
                                        <td>{{ date('dmy', strtotime($orderlist->ex_factory_date)) }}</td>
                                        <td>{{ date('dmy', strtotime($orderlist->vsl_date)) }}</td>
                                        <td>{{ $orderlist->wash_type }}</td>
                                        <td>{{ $orderlist->bordir_type }}</td>
                                        <td>{{ $orderlist->line }}</td>
                                        <td>{{ $orderlist->smv }}</td>
                                        <td>{{ $orderlist->target_qty }}</td>
                                        <td>{{ $orderlist->production_day }}</td>
                                        @if($orderlist->status == 'Finish')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            @if($orderlist->status !== 'Finish')
                                            @if (request()->get('void') == 'false' || request()->get('void') == '')
                                            <a class="btn btn-success btn-circle btn-sm btn-change-record" data-change-link="{{ route('orderlist.change', ['id' => $orderlist->id]) }}" data-change-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#changeModal">
                                                <i class="fas fa-check-square"></i>
                                            </a>
                                            <a href="{{ route('orderlist.find', ['id' => $orderlist->id]) }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a id="show-detail" class="btn btn-primary btn-circle btn-sm btn-show-detail" data-url="{{ route('orderlist.showordersize', $orderlist->order_list) }}" data-show-link="{{ $orderlist->order_list }}" data-show-title="{{ $orderlist->order_list }} - {{ $orderlist->pobuyer_no }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                            <!-- <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $orderlist->id }}" data-delete-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i> -->
                                            <a class="btn btn-danger btn-circle btn-sm btn-void-record" data-void-link="{{ route('orderlist.void', ['id' => $orderlist->id]) }}" data-void-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#voidModal">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            @elseif (request()->get('void') == 'true')
                                            <a class="btn btn-success btn-circle btn-sm btn-restore-record" data-restore-link="{{ route('orderlist.restore', ['id' => $orderlist->id]) }}" data-restore-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#restoreModal">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            @endif
                                            </a>
                                            @else
                                            @if (request()->get('void') == 'false' || request()->get('void') == '')
                                            <a id="show-detail" class="btn btn-primary btn-circle btn-sm btn-show-detail" data-url="{{ route('orderlist.showordersize', $orderlist->order_list) }}" data-show-link="{{ $orderlist->order_list }}" data-show-title="{{ $orderlist->order_list }} - {{ $orderlist->pobuyer_no }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                            <!-- <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $orderlist->id }}" data-delete-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i> -->
                                            <a class="btn btn-danger btn-circle btn-sm btn-void-record" data-void-link="{{ route('orderlist.void', ['id' => $orderlist->id]) }}" data-void-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#voidModal">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            @elseif (request()->get('void') == 'true')
                                            <a class="btn btn-success btn-circle btn-sm btn-restore-record" data-restore-link="{{ route('orderlist.restore', ['id' => $orderlist->id]) }}" data-restore-name="{{ $orderlist->order_list }}" data-toggle="modal" data-target="#restoreModal">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            @endif
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="delete-title" class="modal-title" id="exampleModalLabel">Delete Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm" href=""><button class="btn btn-primary" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="voidModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="void-title" class="modal-title" id="exampleModalLabel">Void Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record-void"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm-void" href=""><button class="btn btn-danger" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="restore-title" class="modal-title" id="exampleModalLabel">Restore Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record-restore"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm-restore" href=""><button class="btn btn-success" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="change-title" class="modal-title" id="exampleModalLabel">Finish Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record-change"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm-change" href=""><button class="btn btn-primary" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Import List Order List</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <form action="{{ route('orderlist.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>PILIH FILE</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="detail-title" class="modal-title" id="exampleModalLabel">Data Karyawan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-row align-items-center justify-content-between" style="margin-top: 10px;">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-blue-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                    <!-- <div class="dropdown-header">Action:</div> -->
                                    <a class="dropdown-item" href="{{ route('ordersize.create') }}" target="_blank">Create Order Size</a>
                                    <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-modal table-sm" id="table-order-size" width="100%" cellspacing="0">
                                        <thead>
                                            <!-- <tr>
                                                <th>No</th>
                                                <th>Lot</th>
                                                <th>PO Buyer</th>
                                                <th>DC PO Qty</th>
                                                <th>Size</th>
                                                <th>Size Qty</th>
                                                <th>Total Qty</th>
                                            </tr> -->
                                            <tr>
                                                <th>No</th>
                                                <th>Lot</th>
                                                <th>PO Buyer</th>
                                                <th>DC PO Qty</th>
                                                <th>XS</th>
                                                <th>S</th>
                                                <th>M</th>
                                                <th>L</th>
                                                <th>XL</th>
                                                <th>XXL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


@include('layout.footer')
</body>
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>

<!-- Page level custom scripts -->
<script>
    $('.btn-show-detail').on('click', function () {
        $("#detail-title").text($(this).data('show-title'));
    });
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus orderlist ' + $(this).data('delete-name') + '?');
    });
    $('.btn-change-record').on('click', function () {
            $('#btn-confirm-change').attr('href', $(this).data('change-link'));
            $("#modal-text-record-change").text('Apakah anda yakin ingin mengubah status Order List ' + $(this).data('change-name') + ' menjadi Finish?');
    });
    $('.btn-void-record').on('click', function () {
            $('#btn-confirm-void').attr('href', $(this).data('void-link'));
            $("#modal-text-record-void").text('Apakah anda yakin ingin menghapus Order List ' + $(this).data('void-name') + '?');
    });
    $('.btn-restore-record').on('click', function () {
            $('#btn-confirm-restore').attr('href', $(this).data('restore-link'));
            $("#modal-text-record-restore").text('Apakah anda yakin ingin mengembalikan Order List ' + $(this).data('restore-name') + '?');
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '#show-detail', function() {
            var jsonOrderSize = $(this).data('url');
            $.get(jsonOrderSize, function (data) {
                $('#detailModal').modal('show');
                var tableOrderSize = $('#table-order-size').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonOrderSize,
                        columns: [
                            // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            // { data: 'lot_no', name: 'lot_no', orderable: false },
                            // { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            // { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            // { data: 'size', name: 'size', orderable: false },
                            // { data: 'qty', name: 'qty', orderable: false },
                            // { data: 'sum_qty', name: 'sum_qty', orderable: false },
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'lot_no', name: 'lot_no', orderable: false },
                            { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            { data: 'XS', name: 'XS', orderable: false },
                            { data: 'S', name: 'S', orderable: false },
                            { data: 'M', name: 'M', orderable: false },
                            { data: 'L', name: 'L', orderable: false },
                            { data: 'XL', name: 'XL', orderable: false },
                            { data: 'XXL', name: 'XXL', orderable: false },
                        ],
                    });
            })
        });
    });
</script>
</html>