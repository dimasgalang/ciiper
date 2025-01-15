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
                                        <!-- <th>Order Trans</th> -->
                                        <th>Season</th>
                                        <th>Buyer</th>
                                        <th>Brand</th>
                                        <th>Style</th>
                                        <th>Master PO</th>
                                        <th>Qty</th>
                                        <!-- <th>OCF</th> -->
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
                                        <!-- <td>{{ $ordermaster->order_trans }}</td> -->
                                        <td>{{ $ordermaster->season_cat }}</td>
                                        <td>{{ $ordermaster->buyer_name }}</td>
                                        <td>{{ $ordermaster->brand_name }}</td>
                                        <td>{{ $ordermaster->style_name }}</td>
                                        <td>{{ $ordermaster->po_no }}</td>
                                        <td>{{ $ordermaster->qty_order }}</td>
                                        <!-- <td>{{ $ordermaster->qty_ocf }}</td> -->
                                        <td>{{ $ordermaster->sum_raf_qty }}</td>
                                        <td>{{ $ordermaster->qty_sbd }}</td>
                                        <td>{{ $ordermaster->fu_name }}</td>
                                        <td>{{ $ordermaster->wash_type }}</td>
                                        <td><center><img id="sketch" src="{{ asset('/sketch/' . $ordermaster->sketch_file) }}" style="width: 200px;"></center></td>
                                        <td>{{ $ordermaster->remark }}</td>
                                        <td>
                                            <a href="/ordermaster/find/{{ $ordermaster->id }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a id="show-orderlist" class="btn btn-primary btn-circle btn-sm btn-show-orderlist" data-orderlist-url="{{ route('ordermaster.orderlist', $ordermaster->order_trans) }}" data-raf-url="{{ route('ordermaster.rafproduction', $ordermaster->order_trans) }}" data-fab-url="{{ route('ordermaster.fab', $ordermaster->order_trans) }}" data-shipment-url="{{ route('ordermaster.shipment', $ordermaster->order_trans) }}" data-style-url="{{ route('ordermaster.style', $ordermaster->order_trans) }}" data-show-orderlist-link="{{ $ordermaster->order_trans }}" data-show-orderlist-title="{{ $ordermaster->order_trans }}" data-show-image="{{ asset('/sketch/' . $ordermaster->sketch_file) }}">
                                                <i class="fas fa-info"></i>
                                            </a>
                                            <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $ordermaster->id }}" data-delete-name="{{ $ordermaster->order_trans }}" data-toggle="modal" data-target="#deleteModal">
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

        <div class="modal fade" id="orderlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="orderlist-title" class="modal-title" id="exampleModalLabel">Data Karyawan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tab">
                            <button class="tablinks" onclick="openModal(event, 'Sketch')">Sketch</button>
                            <button class="tablinks" onclick="openModal(event, 'Order List')">Order List</button>
                            <button class="tablinks" onclick="openModal(event, 'RAF Production')">RAF Production</button>
                            <button class="tablinks" onclick="openModal(event, 'Fabrication')">Fabrication</button>
                            <button class="tablinks" onclick="openModal(event, 'Shipment')">Shipment</button>
                        </div>
                          
                        <!-- Tab content -->
                        <div id="Sketch" class="tabcontent">
                            <br>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>STYLE NAME :</label>
                                        <input class="form-control" type="text" id="order_list-style_name" value="" readonly>
                                    </div>
                                    <br>
                                    <div>
                                        <label>STYLE DESC :</label>
                                        <input class="form-control" type="text" id="order_list-style_desc" value="" readonly>
                                    </div>
                                    <br>
                                    <center><img id="sketch-PIC" src="" style="width: 80%;"></center>
                                </div>
                            </div>
                        </div>

                        <div id="Order List" class="tabcontent">
                            <br>
                            <div class="row">
                                <!-- <div class="col-xl-3 col-md-6 mb-4">
                                    <center><img id="sketch-PIC" src="" style="width: 200px;"></center>
                                </div> -->
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-orderlist" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Factory</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>DC PO Qty (Dzn)</th>
                                                    <th>DC PO Qty (Pcs)</th>
                                                    <th>RAF Qty (Pcs)</th>
                                                    <th>Balance</th>
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
                          
                        <div id="RAF Production" class="tabcontent">
                            <br>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-raf" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>RAF No</th>
                                                    <th>RAF Date</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>RAF Qty</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                          
                        <div id="Fabrication" class="tabcontent">
                            <br>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>FABRIC MILL :</label>
                                        <input class="form-control" type="text" id="order_list-fabmill_name" value="" readonly>
                                    </div>
                                    <br>
                                    <div>
                                        <label>FABRICATION :</label>
                                        <textarea  class="form-control" type="text" id="order_list-fabrication" value="" readonly rows="20"></textarea>
                                    </div>
                                    <br>
                                    <div>
                                        <label>PO FABRIC :</label>
                                        <textarea class="form-control" type="text" id="order_list-po_fab" value="" readonly rows="5"></textarea>
                                    </div>
                                    <br>
                                    <div>
                                        <label>ETD :</label>
                                        <textarea class="form-control" type="text" id="order_list-etd" value="" readonly rows="5"></textarea>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div id="Shipment" class="tabcontent">
                            <br>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-shipment" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>PO Buyer</th>
                                                    <th>Market</th>
                                                    <th>Ship Mode</th>
                                                    <th>Ship Qty</th>
                                                    <th>Ship Date</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
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

        <!-- Modal Raf -->
        <div class="modal fade" id="rafModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="raf-title" class="modal-title" id="exampleModalLabel">Data Karyawan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-modal" id="table-raf" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Order List</th>
                                                <th>PO Buyer</th>
                                                <th>Market</th>
                                                <th>Ship Mode</th>
                                                <th>Ship Qty</th>
                                                <th>Ship Date</th>
                                                <th>Remark</th>
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
    $('.btn-show-orderlist').on('click', function () {
        $("#orderlist-title").text($(this).data('show-orderlist-title'));
    });
    $('.btn-show-raf').on('click', function () {
        $("#raf-title").text($(this).data('show-raf-title'));
    });
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus order master ' + $(this).data('delete-name') + '?');
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '#show-orderlist', function() {
            $('#orderlistModal').modal('show');
            var jsonOrderList = $(this).data('orderlist-url');
            var jsonRaf = $(this).data('raf-url'); 
            var jsonFab = $(this).data('fab-url'); 
            var jsonShipment = $(this).data('shipment-url'); 
            var jsonStyle = $(this).data('style-url'); 
            var sketchIMG = $(this).data('show-image');
            $('#sketch-PIC').attr('src', sketchIMG);
            $.get(jsonOrderList, function (data) {
                $('#table-orderlist').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonOrderList,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'factory_no', name: 'factory_no' },
                        { data: 'lot_no', name: 'lot_no' },
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'dcpo_dzn', name: 'dcpo_dzn' },
                        { data: 'dcpo_qty', name: 'dcpo_qty' },
                        { data: 'raf_qty', name: 'raf_qty' },
                        { data: 'balance', name: 'balance' },
                        { data: 'ex_factory_date', name: 'ex_factory_date' },
                        { data: 'vsl_date', name: 'vsl_date' },
            
                    ]
                });
                $('#table-raf').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonRaf,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'raf_no', name: 'raf_no' },
                        { data: 'raf_date', name: 'raf_date' },
                        { data: 'lot_no', name: 'lot_no' },
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'raf_qty', name: 'raf_qty' },
                        { data: 'remark', name: 'remark' },
            
                    ]
                });

                $('#table-shipment').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonShipment,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'market_name', name: 'market_name' },
                        { data: 'ship_name', name: 'ship_name' },
                        { data: 'ship_qty', name: 'ship_qty' },
                        { data: 'ship_date', name: 'ship_date' },
                        { data: 'remark', name: 'remark' },
            
                    ]
                });
            });
            $.get(jsonFab, function (data) {
                $('#order_list-fabmill_name').val(data[0].fabmill_name);
                $('#order_list-fabrication').text(data[0].fabrication);
                $('#order_list-po_fab').text(data[0].po_fab);
                $('#order_list-etd').text(data[0].etd);
            });
            $.get(jsonStyle, function (data) {
                $('#order_list-style_name').val(data[0].style_name);
                $('#order_list-style_desc').val(data[0].style_desc);
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
       
     });
</script>
<script>
function openModal(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
</html>