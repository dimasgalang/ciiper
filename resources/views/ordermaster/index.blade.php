<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
<!-- Page Wrapper -->
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
                    <h1 class="h3 mb-0 text-gray-800">Order Master List</h1>
                    <div>
                    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#importModal"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Import Order Master</a>
                    <a href="{{ route('ordermaster.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Order Master</a>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Master Data</h6>
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
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order Trans</th>
                                        <th>Season</th>
                                        <th>Buyer</th>
                                        <th>Brand</th>
                                        <th>Style</th>
                                        <th>Master PO</th>
                                        <th>Qty</th>
                                        <th>OCF</th>
                                        <th>GMT</th>
                                        <th>SBD</th>
                                        <th>MR</th>
                                        <th>Wash Type</th>
                                        <th>Sketch</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordermasters as $ordermaster)
                                    <tr>
                                        <td>{{ $ordermaster->id }}</td>
                                        <td>{{ $ordermaster->order_trans }}</td>
                                        <td>{{ $ordermaster->season_cat }}</td>
                                        <td>{{ $ordermaster->buyer_name }}</td>
                                        <td>{{ $ordermaster->brand_name }}</td>
                                        <td>{{ $ordermaster->style_name }}</td>
                                        <td>{{ $ordermaster->po_no }}</td>
                                        <td>{{ $ordermaster->qty_order }}</td>
                                        <td>{{ $ordermaster->qty_ocf }}</td>
                                        <td>{{ $ordermaster->qty_gmt }}</td>
                                        <td>{{ $ordermaster->qty_sbd }}</td>
                                        <td>{{ $ordermaster->fu_no }}</td>
                                        <td>{{ $ordermaster->wash_type }}</td>
                                        <td>{{ $ordermaster->remark }}</td>
                                        <td>{{ $ordermaster->sketch_file }}</td>
                                        <td align="center">
                                            <a id="show-detail" class="btn btn-primary btn-circle btn-sm btn-show-detail" data-url="{{ route('ordermaster.detail', $ordermaster->order_trans) }}" data-show-link="{{ $ordermaster->order_trans }}" data-show-title="{{ $ordermaster->order_trans }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                            <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $ordermaster->id }}" data-delete-name="{{ $ordermaster->ordermaster_name }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Import Master Order Master</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <form action="{{ route('ordermaster.import') }}" method="POST" enctype="multipart/form-data">
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
                        <div class="row">
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-modal" id="table-orderlist" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Order List</th>
                                                <th>Factory</th>
                                                <th>Lot</th>
                                                <th>PO Buyer</th>
                                                <th>DC PO Qty (Dzn)</th>
                                                <th>DC PO Qty (Pcs)</th>
                                                <th>Ex Factory</th>
                                                <th>Vsl Date</th>
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

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script>
    $('.btn-show-detail').on('click', function () {
        $("#detail-title").text($(this).data('show-title'));
    });
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus ordermaster ' + $(this).data('delete-name') + '?');
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '#show-detail', function() {
            var json = $(this).data('url'); 
            $.get(json, function (data) {
                $('#detailModal').modal('show');
                $('#table-orderlist').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: json,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'order_list', name: 'order_list' },
                        { data: 'factory_no', name: 'factory_no' },
                        { data: 'lot_no', name: 'lot_no' },
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'dcpo_dzn', name: 'dcpo_dzn' },
                        { data: 'dcpo_qty', name: 'dcpo_qty' },
                        { data: 'ex_factory_date', name: 'ex_factory_date' },
                        { data: 'vsl_date', name: 'vsl_date' },
            
                    ]
                });
            })
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
       
     });
    </script>
</html>