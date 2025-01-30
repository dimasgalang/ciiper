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
                        <div class="row justify-content-between align-items-center" style="margin-left: 5px; margin-right: 5px;">
                            <h6 class="m-0 font-weight-bold text-primary">Order Master Data</h6>
                            <a href="{{ route('ordermaster.export') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                                    class="fas fa-plus fa-sm text-white-50"></i> Export Excel</a>
                        </div>
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
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 8;">
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
                                        <th>MR</th>
                                        <th>Sketch</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordermasters as $ordermaster)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ordermaster->order_trans }}</td>
                                        <td>{{ $ordermaster->season_cat }} {{ $ordermaster->season_year }}</td>
                                        <td>{{ $ordermaster->buyer_name }}</td>
                                        <td>{{ $ordermaster->brand_name }}</td>
                                        <td>{{ $ordermaster->style_name }}</td>
                                        <td>{{ $ordermaster->po_master }}</td>
                                        <td>{{ $ordermaster->qty_order }}</td>
                                        <td>{{ $ordermaster->qty_ocf }}</td>
                                        <td>{{ $ordermaster->sum_raf_qty }}</td>
                                        <td>{{ $ordermaster->fu_name }}</td>
                                        <td><center><img id="sketch" src="{{ asset('/sketch/' . $ordermaster->sketch_file) }}" style="width: 200px;"></center></td>
                                        <td>{{ $ordermaster->remark }}</td>
                                        <td>
                                            <a href="/ordermaster/find/{{ $ordermaster->id }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a id="show-orderlist" class="btn btn-primary btn-circle btn-sm btn-show-orderlist" data-orderlist-url="{{ route('ordermaster.orderlist', $ordermaster->order_trans) }}" data-rafproduction-url="{{ route('ordermaster.rafproduction', $ordermaster->order_trans) }}" data-rafcutting-url="{{ route('ordermaster.rafcutting', $ordermaster->order_trans) }}" data-rafsewing-url="{{ route('ordermaster.rafsewing', $ordermaster->order_trans) }}" data-rafiron-url="{{ route('ordermaster.rafiron', $ordermaster->order_trans) }}" data-rafpacking-url="{{ route('ordermaster.rafpacking', $ordermaster->order_trans) }}" data-fab-url="{{ route('ordermaster.fab', $ordermaster->order_trans) }}" data-shipment-url="{{ route('ordermaster.shipment', $ordermaster->order_trans) }}" data-style-url="{{ route('ordermaster.style', $ordermaster->order_trans) }}" data-productionplanning-url="{{ route('ordermaster.productionplanning', $ordermaster->order_trans) }}"  data-show-orderlist-link="{{ $ordermaster->order_trans }}" data-show-orderlist-title="{{ $ordermaster->order_trans }} - {{ $ordermaster->po_master }}" data-show-image="{{ asset('/sketch/' . $ordermaster->sketch_file) }}">
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
                        <h5 id="orderlist-title" class="modal-title" id="exampleModalLabel"></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tab">
                            <button id="tabSketch" class="tablinks" onclick="openModal(event, 'Sketch')">Sketch</button>
                            <button class="tablinks" onclick="openModal(event, 'Order List')">Order List</button>
                            <button class="tablinks" onclick="openModal(event, 'Production Planning')">Production Planning</button>
                            <button class="tablinks" onclick="openModal(event, 'Cutting')">Cutting</button>
                            <button class="tablinks" onclick="openModal(event, 'Sewing')">Sewing</button>
                            <button class="tablinks" onclick="openModal(event, 'Iron')">Iron</button>
                            <button class="tablinks" onclick="openModal(event, 'Packing')">Packing</button>
                            <!-- <button class="tablinks" onclick="openModal(event, 'RAF Production')">RAF Production</button> -->
                            <button class="tablinks" onclick="openModal(event, 'Fabrication')">Fabrication</button>
                            <button class="tablinks" onclick="openModal(event, 'Shipment')">Shipment</button>
                        </div>
                          
                        <!-- Tab content -->
                        <div id="Sketch" class="tabcontent">
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
                                        <a id="updateSketchLink" class="dropdown-item" href="" target="_blank">Update Sketch</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <center><img id="sketch-PIC" src="" style="width: 80%;"></center>
                                </div>
                                <div class="col-xl-8 col-md-6 mb-4">
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
                                </div>
                            </div>
                        </div>

                        <div id="Order List" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('orderlist.create') }}" target="_blank">Create Order List</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
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

                        <div id="Production Planning" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('productionplanning.create') }}" target="_blank">Create Production Planning</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-production-planning" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Sample</th>
                                                    <th>MI</th>
                                                    <th>Acc Fab Cart</th>
                                                    <th>Fab</th>
                                                    <th>Acc</th>
                                                    <th>Bordir Approve</th>
                                                    <th>Pattern</th>
                                                    <th>Sample Test</th>
                                                    <th>Marker</th>
                                                    <th>Pilot Run</th>
                                                    <th>PPM</th>
                                                    <th>Start Cut.</th>
                                                    <th>Finish Cut.</th>
                                                    <th>Start Sew.</th>
                                                    <th>Finish Sew.</th>
                                                    <th>Finish Pack.</th>
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

                        <div id="Cutting" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('rafproduction.create') }}" target="_blank">Create RAF</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>Search :</label>
                                        <input class="date form-control" type="text" id="searchCutting" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>From Date :</label>
                                        <input class="date form-control" type="date" id="fromdateCutting" value="">
                                    </div>
                                    <br>
                                    <button id='filter-data-cutting' type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>To Date :</label>
                                        <input class="date form-control" type="date" id="todateCutting" value="">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-raf-cutting" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>DC PO Qty</th>
                                                    <th>RAF Date</th>
                                                    <th>RAF Qty</th>
                                                    <th>Total Raf</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot align="right">
                                                <tr><th colspan="5"></th><th></th><th></th></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="Sewing" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('rafproduction.create') }}" target="_blank">Create RAF</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>Search :</label>
                                        <input class="date form-control" type="text" id="searchSewing" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>From Date :</label>
                                        <input class="date form-control" type="date" id="fromdateSewing" value="">
                                    </div>
                                    <br>
                                    <button id='filter-data-sewing' type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>To Date :</label>
                                        <input class="date form-control" type="date" id="todateSewing" value="">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-raf-sewing" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>DC PO Qty</th>
                                                    <th>RAF Date</th>
                                                    <th>RAF Qty</th>
                                                    <th>Total Raf</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="Iron" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('rafproduction.create') }}" target="_blank">Create RAF</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>Search :</label>
                                        <input class="date form-control" type="text" id="searchIron" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>From Date :</label>
                                        <input class="date form-control" type="date" id="fromdateIron" value="">
                                    </div>
                                    <br>
                                    <button id='filter-data-iron' type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>To Date :</label>
                                        <input class="date form-control" type="date" id="todateIron" value="">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-raf-iron" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>DC PO Qty</th>
                                                    <th>RAF Date</th>
                                                    <th>RAF Qty</th>
                                                    <th>Total Raf</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="Packing" class="tabcontent">
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
                                        <a class="dropdown-item" href="{{ route('rafproduction.create') }}" target="_blank">Create RAF</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div>
                                        <label>Search :</label>
                                        <input class="date form-control" type="text" id="searchPacking" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>From Date :</label>
                                        <input class="date form-control" type="date" id="fromdatePacking" value="">
                                    </div>
                                    <br>
                                    <button id='filter-data-packing' type="submit" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>To Date :</label>
                                        <input class="date form-control" type="date" id="todatePacking" value="">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-12 col-md-6 mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-modal" id="table-raf-packing" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lot</th>
                                                    <th>PO Buyer</th>
                                                    <th>DC PO Qty</th>
                                                    <th>RAF Date</th>
                                                    <th>RAF Qty</th>
                                                    <th>Total Raf</th>
                                                    <th>Balance</th>
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
                                        <a id="updateFabricationLink" class="dropdown-item" href="" target="_blank">Update Fabrication</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="dataFabric">
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <div>
                                        <label>FABRIC MILL :</label>
                                        <textarea  class="form-control" type="text" id="order_list-fabmill_name" value="" readonly rows="5"></textarea>
                                    </div>
                                    <br>
                                    <div>
                                        <label>FABRICATION :</label>
                                        <textarea  class="form-control" type="text" id="order_list-fabrication" value="" readonly rows="5"></textarea>
                                    </div>
                                    <br>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
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
                                        <a class="dropdown-item" href="{{ route('shipment.create') }}" target="_blank">Create Shipment</a>
                                        <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                    </div>
                                </div>
                            </div>
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
                                    <table class="table table-bordered table-modal" id="table-raf-production" width="100%" cellspacing="0">
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
<script>
    $( document ).ready(function() {
        var date = new Date();
        var firstDay = date.getFullYear() + '-' + ('0' + (date.getMonth(),1)).slice(-2) + '-' + ('0' + 1).slice(-2);
        var lastDay = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
        document.getElementById("fromdateCutting").value = firstDay;
        document.getElementById("todateCutting").value = lastDay;
        document.getElementById("fromdateSewing").value = firstDay;
        document.getElementById("todateSewing").value = lastDay;
        document.getElementById("fromdateIron").value = firstDay;
        document.getElementById("todateIron").value = lastDay;
        document.getElementById("fromdatePacking").value = firstDay;
        document.getElementById("todatePacking").value = lastDay;
    });
</script>

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
    $(function () {
        $('body').on('click', '#show-orderlist', function() {
            // $("#dataSketch").css("display", "none");
            // document.getElementById("dataSketch").style.display = 'none';
            var jsonOrderList = $(this).data('orderlist-url');
            // var jsonRafProduction = $(this).data('rafproduction-url'); 
            var jsonRafCutting = $(this).data('rafcutting-url'); 
            var jsonRafSewing = $(this).data('rafsewing-url'); 
            var jsonRafIron = $(this).data('rafiron-url'); 
            var jsonRafPacking = $(this).data('rafpacking-url'); 
            var jsonFab = $(this).data('fab-url'); 
            var jsonShipment = $(this).data('shipment-url'); 
            var jsonStyle = $(this).data('style-url'); 
            var jsonProductionPlanning = $(this).data('productionplanning-url'); 
            var sketchIMG = $(this).data('show-image');
            $('#sketch-PIC').attr('src', sketchIMG);
            $.get(jsonOrderList, function (data) {
                $('#orderlistModal').modal('show');
                $('#table-orderlist').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonOrderList,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'factory_name', name: 'factory_name' },
                        { data: 'lot_no', name: 'lot_no' },
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'dcpo_dzn', name: 'dcpo_dzn' },
                        { data: 'dcpo_qty', name: 'dcpo_qty' },
                        { data: 'sum_raf_qty', name: 'sum_raf_qty' },
                        { data: 'balance', name: 'balance' },
                        { data: 'ex_factory_date', name: 'ex_factory_date' },
                        { data: 'vsl_date', name: 'vsl_date' },
            
                    ]
                });
                $('#table-production-planning').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonProductionPlanning,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'has_sample', name: 'has_sample' },
                        { data: 'has_mi', name: 'has_mi' },
                        { data: 'has_cart', name: 'has_cart' },
                        { data: 'fab_date', name: 'fab_date' },
                        { data: 'acc_date', name: 'acc_date' },
                        { data: 'bordir_approve', name: 'bordir_approve' },
                        { data: 'pattern_date', name: 'pattern_date' },
                        { data: 'sampletest_date', name: 'sampletest_date' },
                        { data: 'marker_date', name: 'marker_date' },
                        { data: 'pilotrun_date', name: 'pilotrun_date' },
                        { data: 'ppm_date', name: 'ppm_date' },
                        { data: 'startcut_date', name: 'startcut_date' },
                        { data: 'finishcut_date', name: 'finishcut_date' },
                        { data: 'startsew_date', name: 'startsew_date' },
                        { data: 'finishsew_date', name: 'finishsew_date' },
                        { data: 'finishpack_date', name: 'finishpack_date' },
                        { data: 'remark', name: 'remark' },
            
                    ]
                });

                $(function () {
                    var tableCutting = $('#table-raf-cutting').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        dom: 'rtip',
                        ajax: {
                            url: jsonRafCutting,
                            data: function (d) {
                                d.fromdateCutting = document.getElementById('fromdateCutting').value,
                                d.todateCutting = document.getElementById('todateCutting').value,
                                d.searchCutting = document.getElementById('searchCutting').value
                            }
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'lot_no', name: 'lot_no', orderable: false },
                            { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            { data: 'raf_date', name: 'raf_date', orderable: false },
                            { data: 'raf_qty', name: 'raf_qty', orderable: false },
                            { data: 'totalraf', name: 'totalraf', orderable: false },
                            { data: 'balance', name: 'balance', orderable: false },
                        ],
                    });
                    $('#filter-data-cutting').click(function(){
                        tableCutting.draw();
                    });
                });

                $(function () {
                    var tableSewing = $('#table-raf-sewing').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        dom: 'rtip',
                        ajax: {
                            url: jsonRafSewing,
                            data: function (d) {
                                d.fromdateSewing = document.getElementById('fromdateSewing').value,
                                d.todateSewing = document.getElementById('todateSewing').value,
                                d.searchSewing = document.getElementById('searchSewing').value
                            }
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'lot_no', name: 'lot_no', orderable: false },
                            { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            { data: 'raf_date', name: 'raf_date', orderable: false },
                            { data: 'raf_qty', name: 'raf_qty', orderable: false },
                            { data: 'totalraf', name: 'totalraf', orderable: false },
                            { data: 'balance', name: 'balance', orderable: false },
                        ],
                    });
                    $('#filter-data-sewing').click(function(){
                        tableSewing.draw();
                    });
                });

                $(function () {
                    var tableIron = $('#table-raf-iron').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        dom: 'rtip',
                        ajax: {
                            url: jsonRafIron,
                            data: function (d) {
                                d.fromdateIron = document.getElementById('fromdateIron').value,
                                d.todateIron = document.getElementById('todateIron').value,
                                d.searchIron = document.getElementById('searchIron').value
                            }
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'lot_no', name: 'lot_no', orderable: false },
                            { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            { data: 'raf_date', name: 'raf_date', orderable: false },
                            { data: 'raf_qty', name: 'raf_qty', orderable: false },
                            { data: 'totalraf', name: 'totalraf', orderable: false },
                            { data: 'balance', name: 'balance', orderable: false },
                        ],
                    });
                    $('#filter-data-iron').click(function(){
                        tableIron.draw();
                    });
                });

                $(function () {
                    var tablePacking = $('#table-raf-packing').DataTable({
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        dom: 'rtip',
                        ajax: {
                            url: jsonRafPacking,
                            data: function (d) {
                                d.fromdatePacking = document.getElementById('fromdatePacking').value,
                                d.todatePacking = document.getElementById('todatePacking').value,
                                d.searchPacking = document.getElementById('searchPacking').value
                            }
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'lot_no', name: 'lot_no', orderable: false },
                            { data: 'pobuyer_no', name: 'pobuyer_no', orderable: false },
                            { data: 'dcpo_qty', name: 'dcpo_qty', orderable: false },
                            { data: 'raf_date', name: 'raf_date', orderable: false },
                            { data: 'raf_qty', name: 'raf_qty', orderable: false },
                            { data: 'totalraf', name: 'totalraf', orderable: false },
                            { data: 'balance', name: 'balance', orderable: false },
                        ],
                    });
                    $('#filter-data-packing').click(function(){
                        tablePacking.draw();
                    });
                });

                $('#table-shipment').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: jsonShipment,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'pobuyer_no', name: 'pobuyer_no' },
                        { data: 'market_name', name: 'market_name' },
                        { data: 'shipmode_name', name: 'shipmode_name' },
                        { data: 'ship_qty', name: 'ship_qty' },
                        { data: 'ship_date', name: 'ship_date' },
                        { data: 'remark', name: 'remark' },
            
                    ]
                });
            });
            $.get(jsonFab, function (data) {
                var link;
                if (data.length > 0) {
                    $('#order_list-fabmill_name').text(data[0]?.fabmill_name);
                    $('#order_list-fabrication').text(data[0]?.fabrication);
                    $('#order_list-po_fab').text(data[0]?.po_fab);
                    $('#order_list-etd').text(data[0]?.etd);
                    $(document).ready(function() {
                        link = "/fabrication/find/" + data[0]?.id;
                        $('#updateFabricationLink').text("Update Fabrication");
                        $('#updateFabricationLink').attr('href', link);
                    });
                } else {
                    link = "/fabrication/create/";
                    $('#updateFabricationLink').text("Create Fabrication");
                    $('#updateFabricationLink').attr('href', link);
                    $('#order_list-fabmill_name').text("");
                    $('#order_list-fabrication').text("");
                    $('#order_list-po_fab').text("");
                    $('#order_list-etd').text("");
                }
            });
            $.get(jsonStyle, function (data) {
                if (data.length > 0) {
                    $('#order_list-style_name').val(data[0].style_name);
                    $('#order_list-style_desc').val(data[0].style_desc);
                    $(document).ready(function() {
                        var link = "/style/find/" + data[0].id;
                        $('#updateSketchLink').attr('href', link);
                    });
                } else {

                }
            });
        });
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