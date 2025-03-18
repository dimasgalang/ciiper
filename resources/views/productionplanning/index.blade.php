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
                    <h1 class="h3 mb-0 text-gray-800">Production Planning List</h1>
                    <div>
                    <!-- <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#importModal"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Import Production Planning</a> -->
                    <a href="{{ route('productionplanning.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Production Planning</a>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
                        <h6 class="m-0 font-weight-bold text-primary">Production Planning Data</h6>
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
                        <div>
                            <table class="table-responsive table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Master PO</th>
                                        <th>PO Buyer</th>
                                        <th>Sample</th>
                                        <th>MI</th>
                                        <th>Fab Cart</th>
                                        <th>Fab</th>
                                        <th>Acc Cart</th>
                                        <th>Acc Sewing</th>
                                        <th>Acc Packing</th>
                                        <th>Bordir Approve</th>
                                        <th>Pattern</th>
                                        <th>Sample Test</th>
                                        <th>Req Marker</th>
                                        <th>Marker</th>
                                        <th>Pilot Run</th>
                                        <th>PPM</th>
                                        <th>Start Cut.</th>
                                        <th>Finish Cut.</th>
                                        <th>Start Sew.</th>
                                        <th>Finish Sew.</th>
                                        <th>Finish Pack.</th>
                                        <th bgcolor="lightyellow">RAF Cut</th>
                                        <th bgcolor="lightyellow">Balance Cut</th>
                                        <th bgcolor="lightyellow">RAF Sew</th>
                                        <th bgcolor="lightyellow">Balance Sew</th>
                                        <th bgcolor="lightyellow">RAF Iron</th>
                                        <th bgcolor="lightyellow">Balance Iron</th>
                                        <th bgcolor="lightyellow">RAF Pack</th>
                                        <th bgcolor="lightyellow">Balance Pack</th>
                                        <th bgcolor="lightyellow">Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productionplannings as $productionplanning)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $productionplanning->po_master }}</td>
                                        <td>{{ $productionplanning->pobuyer_no }}</td>

                                        <!-- Average Table -->
                                        <!-- @if ($productionplanning->average_sample < 50)
                                        <td align="center"><a id="show-detail-sample" class="btn btn-danger btn-icon-split btn-sm btn-show-detail-sample" data-url-sample="{{ route('proplandetail.fetchdetailsample', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_sample }}%</span>
                                        </a></td>
                                        @elseif (($productionplanning->average_sample >= 50) && ($productionplanning->average_sample < 100))
                                        <td align="center"><a id="show-detail-sample" class="btn btn-warning btn-icon-split btn-sm btn-show-detail-sample" data-url-sample="{{ route('proplandetail.fetchdetailsample', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_sample }}%</span>
                                        </a></td>
                                        @elseif ($productionplanning->average_sample == 100)
                                        <td align="center"><a id="show-detail-sample" class="btn btn-success btn-icon-split btn-sm btn-show-detail-sample" data-url-sample="{{ route('proplandetail.fetchdetailsample', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_sample }}%</span>
                                        </a></td>
                                        @endif

                                        
                                        @if ($productionplanning->average_mi < 50)
                                        <td align="center"><a id="show-detail-mi" class="btn btn-danger btn-icon-split btn-sm btn-show-detail-mi" data-url-mi="{{ route('proplandetail.fetchdetailmi', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_mi }}%</span>
                                        </a></td>
                                        @elseif (($productionplanning->average_mi >= 50) && ($productionplanning->average_mi < 100))
                                        <td align="center"><a id="show-detail-mi" class="btn btn-warning btn-icon-split btn-sm btn-show-detail-mi" data-url-mi="{{ route('proplandetail.fetchdetailmi', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_mi }}%</span>
                                        </a></td>
                                        @elseif ($productionplanning->average_mi == 100)
                                        <td align="center"><a id="show-detail-mi" class="btn btn-success btn-icon-split btn-sm btn-show-detail-mi" data-url-mi="{{ route('proplandetail.fetchdetailmi', $productionplanning->order_list) }}" data-show-title-sample="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_mi }}%</span>
                                        </a></td>
                                        @endif

                                        
                                        @if ($productionplanning->average_fabric < 50)
                                        <td align="center"><a id="show-detail-fabric" class="btn btn-danger btn-icon-split btn-sm btn-show-detail-fabric" data-url-fabric="{{ route('proplandetail.fetchdetailfabric', $productionplanning->order_list) }}" data-show-title-fabric="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_fabric }}%</span>
                                        </a></td>
                                        @elseif (($productionplanning->average_fabric >= 50) && ($productionplanning->average_fabric < 100))
                                        <td align="center"><a id="show-detail-fabric" class="btn btn-warning btn-icon-split btn-sm btn-show-detail-fabric" data-url-fabric="{{ route('proplandetail.fetchdetailfabric', $productionplanning->order_list) }}" data-show-title-fabric="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_fabric }}%</span>
                                        </a></td>
                                        @elseif ($productionplanning->average_fabric == 100)
                                        <td align="center"><a id="show-detail-fabric" class="btn btn-success btn-icon-split btn-sm btn-show-detail-fabric" data-url-fabric="{{ route('proplandetail.fetchdetailfabric', $productionplanning->order_list) }}" data-show-title-fabric="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_fabric }}%</span>
                                        </a></td>
                                        @endif -->

                                        <!-- @if (((now()->diffInDays("2025-03-03")) <= 7) && ((now()->diffInDays("2025-03-03")) > 3) && (now() < $productionplanning->fab_date))
                                        @endif -->
                                        <!-- @if (((now()->diffInDays($productionplanning->fab_date)) <= 7) && ((now()->diffInDays($productionplanning->fab_date)) > 3) && (now() < $productionplanning->fab_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->fab_date)) <= 3) && ((now()->diffInDays($productionplanning->fab_date)) > 0) && (now() < $productionplanning->fab_date)) || (now() > $productionplanning->fab_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                        @endif -->

                                        <!-- Stop Average Table -->
                                        @if($productionplanning->has_sample == 'Yes')
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
                                        @if($productionplanning->has_mi == 'Yes')
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
                                        @if($productionplanning->has_fab_cart == 'Yes')
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
                                        <td>{{ $productionplanning->fab_date }}</td>
                                        
                                        @if($productionplanning->has_acc_cart == 'Yes')
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
                                        <!-- @if ($productionplanning->average_acc < 50)
                                        <td align="center"><a id="show-detail-acc" class="btn btn-danger btn-icon-split btn-sm btn-show-detail-acc" data-url-acc="{{ route('proplandetail.fetchdetailacc', $productionplanning->order_list) }}" data-show-title-acc="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_acc }}</span>
                                        </a></td>
                                        @elseif (($productionplanning->average_acc >= 50) && ($productionplanning->average_acc < 100))
                                        <td align="center"><a id="show-detail-acc" class="btn btn-warning btn-icon-split btn-sm btn-show-detail-acc" data-url-acc="{{ route('proplandetail.fetchdetailacc', $productionplanning->order_list) }}" data-show-title-acc="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_acc }}</span>
                                        </a></td>
                                        @elseif ($productionplanning->average_acc == 100)
                                        <td align="center"><a id="show-detail-acc" class="btn btn-success btn-icon-split btn-sm btn-show-detail-acc" data-url-acc="{{ route('proplandetail.fetchdetailacc', $productionplanning->order_list) }}" data-show-title-acc="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">{{ $productionplanning->average_acc }}</span>
                                        </a></td>
                                        @endif -->

                                        <!-- Acc Date -->
                                        <!-- @if (((now()->diffInDays($productionplanning->acc_date)) <= 7) && ((now()->diffInDays($productionplanning->acc_date)) > 3) && (now() < $productionplanning->acc_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->acc_date)) <= 3) && ((now()->diffInDays($productionplanning->acc_date)) > 0) && (now() < $productionplanning->acc_date)) || (now() > $productionplanning->acc_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                        @endif -->

                                        <td align="center"><a id="show-detail-sew" class="btn btn-success btn-icon-split btn-sm btn-show-detail-sew" data-url-sew="{{ route('proplandetail.fetchaccsew', $productionplanning->order_list) }}" data-show-title-sew="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">Detail</span>
                                        </a></td>
                                        <td align="center"><a id="show-detail-pack" class="btn btn-success btn-icon-split btn-sm btn-show-detail-pack" data-url-pack="{{ route('proplandetail.fetchaccpack', $productionplanning->order_list) }}" data-show-title-pack="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}">
                                            <span class="text">Detail</span>
                                        </a></td>
                                        
                                        
                                        @if (((now()->diffInDays($productionplanning->bordir_approve)) <= 7) && ((now()->diffInDays($productionplanning->bordir_approve)) > 3) && (now() < $productionplanning->bordir_approve))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->bordir_approve)) <= 3) && ((now()->diffInDays($productionplanning->bordir_approve)) > 0) && (now() < $productionplanning->bordir_approve)) || (now() > $productionplanning->bordir_approve))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @endif


                                        
                                        @if (((now()->diffInDays($productionplanning->pattern_date)) <= 7) && ((now()->diffInDays($productionplanning->pattern_date)) > 3) && (now() < $productionplanning->pattern_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->pattern_date)) <= 3) && ((now()->diffInDays($productionplanning->pattern_date)) > 0) && (now() < $productionplanning->pattern_date)) || (now() > $productionplanning->pattern_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @endif
                                        
                                        @if (((now()->diffInDays($productionplanning->sampletest_date)) <= 7) && ((now()->diffInDays($productionplanning->sampletest_date)) > 3) && (now() < $productionplanning->sampletest_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->sampletest_date)) <= 3) && ((now()->diffInDays($productionplanning->sampletest_date)) > 0) && (now() < $productionplanning->sampletest_date)) || (now() > $productionplanning->sampletest_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->reqmarker_date)) <= 7) && ((now()->diffInDays($productionplanning->reqmarker_date)) > 3) && (now() < $productionplanning->reqmarker_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->reqmarker_date)) <= 3) && ((now()->diffInDays($productionplanning->reqmarker_date)) >= 0) && (now() < $productionplanning->reqmarker_date)) || (now() > $productionplanning->reqmarker_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @endif
                                        
                                        @if (((now()->diffInDays($productionplanning->marker_date)) <= 7) && ((now()->diffInDays($productionplanning->marker_date)) > 3) && (now() < $productionplanning->marker_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->marker_date)) <= 3) && ((now()->diffInDays($productionplanning->marker_date)) >= 0) && (now() < $productionplanning->marker_date)) || (now() > $productionplanning->marker_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->pilotrun_date)) <= 7) && ((now()->diffInDays($productionplanning->pilotrun_date)) > 3) && (now() < $productionplanning->pilotrun_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->pilotrun_date)) <= 3) && ((now()->diffInDays($productionplanning->pilotrun_date)) > 0) && (now() < $productionplanning->pilotrun_date)) || (now() > $productionplanning->pilotrun_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->ppm_date)) <= 7) && ((now()->diffInDays($productionplanning->ppm_date)) > 3) && (now() < $productionplanning->ppm_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->ppm_date)) <= 3) && ((now()->diffInDays($productionplanning->ppm_date)) > 0) && (now() < $productionplanning->ppm_date)) || (now() > $productionplanning->ppm_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->startcut_date)) <= 7) && ((now()->diffInDays($productionplanning->startcut_date)) > 3) && (now() < $productionplanning->startcut_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->startcut_date)) <= 3) && ((now()->diffInDays($productionplanning->startcut_date)) > 0) && (now() < $productionplanning->startcut_date)) || (now() > $productionplanning->startcut_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishcut_date)) <= 7) && ((now()->diffInDays($productionplanning->finishcut_date)) > 3) && (now() < $productionplanning->finishcut_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishcut_date)) <= 3) && ((now()->diffInDays($productionplanning->finishcut_date)) > 0) && (now() < $productionplanning->finishcut_date)) || (now() > $productionplanning->finishcut_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->startsew_date)) <= 7) && ((now()->diffInDays($productionplanning->startsew_date)) > 3) && (now() < $productionplanning->startsew_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->startsew_date)) <= 3) && ((now()->diffInDays($productionplanning->startsew_date)) > 0) && (now() < $productionplanning->startsew_date)) || (now() > $productionplanning->startsew_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishsew_date)) <= 7) && ((now()->diffInDays($productionplanning->finishsew_date)) > 3) && (now() < $productionplanning->finishsew_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishsew_date)) <= 3) && ((now()->diffInDays($productionplanning->finishsew_date)) > 0) && (now() < $productionplanning->finishsew_date)) || (now() > $productionplanning->finishsew_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishpack_date)) <= 7) && ((now()->diffInDays($productionplanning->finishpack_date)) > 3) && (now() < $productionplanning->finishpack_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishpack_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishpack_date)) <= 3) && ((now()->diffInDays($productionplanning->finishpack_date)) > 0) && (now() < $productionplanning->finishpack_date)) || (now() > $productionplanning->finishpack_date))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->finishpack_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->finishpack_date)) }}</td>
                                        @endif
                                        
                                        <td bgcolor="lightyellow">{{ $productionplanning->sum_raf_cut }}</td>
                                        @if ($productionplanning->balance_cut < 0)
                                        <td bgcolor="lightyellow" style="color:red">{{ $productionplanning->balance_cut }}</td>
                                        @else
                                        <td bgcolor="lightyellow" style="color:green">{{ $productionplanning->balance_cut }}</td>
                                        @endif
                                        <td bgcolor="lightyellow">{{ $productionplanning->sum_raf_sew }}</td>
                                        
                                        @if ($productionplanning->balance_sew < 0)
                                        <td bgcolor="lightyellow" style="color:red">{{ $productionplanning->balance_sew }}</td>
                                        @else
                                        <td bgcolor="lightyellow" style="color:green">{{ $productionplanning->balance_sew }}</td>
                                        @endif
                                        <td bgcolor="lightyellow">{{ $productionplanning->sum_raf_iron }}</td>
                                        @if ($productionplanning->balance_iron < 0)
                                        <td bgcolor="lightyellow" style="color:red">{{ $productionplanning->balance_iron }}</td>
                                        @else
                                        <td bgcolor="lightyellow" style="color:green">{{ $productionplanning->balance_iron }}</td>
                                        @endif
                                        <td bgcolor="lightyellow">{{ $productionplanning->sum_raf_pack }}</td>
                                        @if ($productionplanning->balance_pack < 0)
                                        <td bgcolor="lightyellow" style="color:red">{{ $productionplanning->balance_pack }}</td>
                                        @else
                                        <td bgcolor="lightyellow" style="color:green">{{ $productionplanning->balance_pack }}</td>
                                        @endif
                                        <td bgcolor="lightyellow">{{ $productionplanning->remark }}</td>
                                        <td class="text-center">
                                            @if (request()->get('void') == 'false' || request()->get('void') == '')
                                            <a href="{{ route('productionplanning.find', ['id' => $productionplanning->id]) }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $productionplanning->id }}" data-delete-name="{{ $productionplanning->pobuyer_no }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </a> -->
                                            <a class="btn btn-danger btn-circle btn-sm btn-void-record" data-void-link="{{ route('productionplanning.void', ['id' => $productionplanning->id]) }}" data-void-name="{{ $productionplanning->pobuyer_no }}" data-toggle="modal" data-target="#voidModal">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            @elseif (request()->get('void') == 'true')
                                            <a class="btn btn-success btn-circle btn-sm btn-restore-record" data-restore-link="{{ route('productionplanning.restore', ['id' => $productionplanning->id]) }}" data-restore-name="{{ $productionplanning->pobuyer_no }}" data-toggle="modal" data-target="#restoreModal">
                                                <i class="fas fa-history"></i>
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

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Import Production Planning</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <form action="{{ route('productionplanning.import') }}" method="POST" enctype="multipart/form-data">
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
                        <h5 id="detail-title" class="modal-title" id="exampleModalLabel">Cart Detail</h5>
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
                                    <a class="dropdown-item" href="{{ route('proplandetail.create') }}" target="_blank">Create Production Planning Detail</a>
                                    <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-modal table-sm" id="table-detail" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Category</th>
                                                <th>Percentage</th>
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

        <div class="modal fade" id="accModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="detail-title" class="modal-title" id="exampleModalLabel">Accesories Detail</h5>
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
                                    <a class="dropdown-item" href="{{ route('proplandetail.create') }}" target="_blank">Create Production Planning Detail</a>
                                    <!-- <a class="dropdown-item" href="#">Export Excel</a> -->
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-modal table-sm" id="table-acc-detail" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Accesories</th>
                                                <th>Date</th>
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
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus Production Planning ' + $(this).data('delete-name') + '?');
    });
    $('.btn-void-record').on('click', function () {
            $('#btn-confirm-void').attr('href', $(this).data('void-link'));
            $("#modal-text-record-void").text('Apakah anda yakin ingin menghapus Production Planning ' + $(this).data('void-name') + '?');
    });
    $('.btn-restore-record').on('click', function () {
            $('#btn-confirm-restore').attr('href', $(this).data('restore-link'));
            $("#modal-text-record-restore").text('Apakah anda yakin ingin mengembalikan Production Planning ' + $(this).data('restore-name') + '?');
    });
</script>
<script type="text/javascript">
    $('.btn-show-detail-sample').on('click', function () {
        $("#detail-title").text($(this).data('show-title-sample'));
    });
    $('.btn-show-detail-mi').on('click', function () {
        $("#detail-title").text($(this).data('show-title-mi'));
    });
    $('.btn-show-detail-fabric').on('click', function () {
        $("#detail-title").text($(this).data('show-title-fabric'));
    });
    $('.btn-show-detail-acc').on('click', function () {
        $("#detail-title").text($(this).data('show-title-acc'));
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-sample', function() {
            var jsonSample = $(this).data('url-sample');
            $.get(jsonSample, function (data) {
                $('#detailModal').modal('show');
                var tableSample = $('#table-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonSample,
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'item', name: 'item', orderable: false },
                            { data: 'category_name', name: 'category_name', orderable: false },
                            { data: 'percentage', name: 'percentage', orderable: false },
                            { data: 'remark', name: 'remark', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-mi', function() {
            var jsonMI = $(this).data('url-mi');
            $.get(jsonMI, function (data) {
                $('#detailModal').modal('show');
                var tableMI = $('#table-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonMI,
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'item', name: 'item', orderable: false },
                            { data: 'category_name', name: 'category_name', orderable: false },
                            { data: 'percentage', name: 'percentage', orderable: false },
                            { data: 'remark', name: 'remark', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-fabric', function() {
            var jsonFabric = $(this).data('url-fabric');
            $.get(jsonFabric, function (data) {
                $('#detailModal').modal('show');
                var tableFabric = $('#table-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonFabric,
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'item', name: 'item', orderable: false },
                            { data: 'category_name', name: 'category_name', orderable: false },
                            { data: 'percentage', name: 'percentage', orderable: false },
                            { data: 'remark', name: 'remark', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-acc', function() {
            var jsonAcc = $(this).data('url-acc');
            $.get(jsonAcc, function (data) {
                $('#detailModal').modal('show');
                var tableAcc = $('#table-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonAcc,
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'item', name: 'item', orderable: false },
                            { data: 'category_name', name: 'category_name', orderable: false },
                            { data: 'percentage', name: 'percentage', orderable: false },
                            { data: 'remark', name: 'remark', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-sew', function() {
            var jsonAccDetail = $(this).data('url-sew');
            $.get(jsonAccDetail, function (data) {
                $('#accModal').modal('show');
                var tableAccDetail = $('#table-acc-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonAccDetail, 
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'accesories_name', name: 'accesories_name', orderable: false },
                            { data: 'item_date_formated', name: 'item_date_formated', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-pack', function() {
            var jsonPackDetail = $(this).data('url-pack');
            $.get(jsonPackDetail, function (data) {
                $('#accModal').modal('show');
                var tablePackDetail = $('#table-acc-detail').DataTable({
                        destroy: true,
                        processing: true,
                        responsive: true,
                        ajax: jsonPackDetail, 
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            { data: 'accesories_name', name: 'accesories_name', orderable: false },
                            { data: 'item_date_formated', name: 'item_date_formated', orderable: false },
                        ],
                    });
            })
        });
    });
</script>
</html>