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
                                        <th>#</th>
                                        <th>Master PO</th>
                                        <th>PO Buyer</th>
                                        <th>Sample</th>
                                        <th>Sample Date</th>
                                        <th>MI</th>
                                        <th>MI Date</th>
                                        <th>Fab. Cart</th>
                                        <th>Fab Date</th>
                                        <th>Acc. Cart</th>
                                        <th>Acc. Date</th>
                                        <th>Acc. Sewing</th>
                                        <th>Acc. Packing</th>
                                        <th>Emb. Approve</th>
                                        <th>Pattern</th>
                                        <th>Sample Test</th>
                                        <th>Req. Marker</th>
                                        <th>Marker</th>
                                        <th>Pilot Run</th>
                                        <th>PPM</th>
                                        <th>Start Cut.</th>
                                        <th>Finish Cut.</th>
                                        <th>Start Sew.</th>
                                        <th>Finish Sew.</th>
                                        <th>Finish Pack.</th>
                                        <th bgcolor="lightyellow">Cut. Output</th>
                                        <th bgcolor="lightyellow">Balance Cut.</th>
                                        <th bgcolor="lightyellow">Sew. Output</th>
                                        <th bgcolor="lightyellow">Balance Sew.</th>
                                        <th bgcolor="lightyellow">Iron Output</th>
                                        <th bgcolor="lightyellow">Balance Iron</th>
                                        <th bgcolor="lightyellow">Pack. Output</th>
                                        <th bgcolor="lightyellow">Balance Pack.</th>
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
                                                <a class="btn btn-success btn-circle btn-sm btn-update-sample-record" data-update-sample-link="{{ route('productionplanning.updatesample', ['id' => $productionplanning->id]) }}" data-update-id-sample="{{ $productionplanning->id }}" data-update-sample-name="{{ $productionplanning->pobuyer_no }}" data-update-sample-has="{{ $productionplanning->has_sample }}" data-update-sample-date="{{ $productionplanning->sample_date }}" data-toggle="modal" data-target="#updateSampleModal">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm btn-update-sample-record" data-update-sample-link="{{ route('productionplanning.updatesample', ['id' => $productionplanning->id]) }}" data-update-id-sample="{{ $productionplanning->id }}" data-update-sample-name="{{ $productionplanning->pobuyer_no }}" data-update-sample-has="{{ $productionplanning->has_sample }}" data-update-sample-date="{{ $productionplanning->sample_date }}" data-toggle="modal" data-target="#updateSampleModal">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if($productionplanning->sample_date)
                                            @if (((now()->diffInDays($productionplanning->sample_date)) <= 7) && ((now()->diffInDays($productionplanning->sample_date)) > 3) && (now() < $productionplanning->sample_date))
                                            <td style="color:orange">{{ date('dmy', strtotime($productionplanning->sample_date)) }}</td>
                                            @elseif ((((now()->diffInDays($productionplanning->sample_date)) <= 3) && ((now()->diffInDays($productionplanning->sample_date)) > 0) && (now() < $productionplanning->sample_date)))
                                            <td style="color:red">{{ date('dmy', strtotime($productionplanning->sample_date)) }}</td>
                                            @else
                                            <td>{{ date('dmy', strtotime($productionplanning->sample_date)) }}</td>
                                            @endif
                                        @else
                                        <td>{{ $productionplanning->sample_date }}</td>
                                        @endif
                                        @if($productionplanning->has_mi == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm btn-update-mi-record" data-update-mi-link="{{ route('productionplanning.updatemi', ['id' => $productionplanning->id]) }}" data-update-id-mi="{{ $productionplanning->id }}" data-update-mi-name="{{ $productionplanning->pobuyer_no }}" data-update-mi-has="{{ $productionplanning->has_mi }}" data-update-mi-date="{{ $productionplanning->mi_date }}" data-toggle="modal" data-target="#updateMIModal">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm btn-update-mi-record" data-update-mi-link="{{ route('productionplanning.updatemi', ['id' => $productionplanning->id]) }}" data-update-id-mi="{{ $productionplanning->id }}" data-update-mi-name="{{ $productionplanning->pobuyer_no }}" data-update-mi-has="{{ $productionplanning->has_mi }}" data-update-mi-date="{{ $productionplanning->mi_date }}" data-toggle="modal" data-target="#updateMIModal">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if($productionplanning->mi_date)
                                            @if (((now()->diffInDays($productionplanning->mi_date)) <= 7) && ((now()->diffInDays($productionplanning->mi_date)) > 3) && (now() < $productionplanning->mi_date))
                                            <td style="color:orange">{{ date('dmy', strtotime($productionplanning->mi_date)) }}</td>
                                            @elseif ((((now()->diffInDays($productionplanning->mi_date)) <= 3) && ((now()->diffInDays($productionplanning->mi_date)) > 0) && (now() < $productionplanning->mi_date)))
                                            <td style="color:red">{{ date('dmy', strtotime($productionplanning->mi_date)) }}</td>
                                            @else
                                            <td>{{ date('dmy', strtotime($productionplanning->mi_date)) }}</td>
                                            @endif
                                        @else
                                        <td>{{ $productionplanning->mi_date }}</td>
                                        @endif
                                        @if($productionplanning->has_fab_cart == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm btn-update-fab-record" data-update-fab-link="{{ route('productionplanning.updatefab', ['id' => $productionplanning->id]) }}" data-update-id-fab="{{ $productionplanning->id }}" data-update-fab-name="{{ $productionplanning->pobuyer_no }}" data-update-fab-has="{{ $productionplanning->has_fab_cart }}" data-update-fab-date="{{ $productionplanning->fab_date }}" data-toggle="modal" data-target="#updateFabModal">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm btn-update-fab-record" data-update-fab-link="{{ route('productionplanning.updatefab', ['id' => $productionplanning->id]) }}" data-update-id-fab="{{ $productionplanning->id }}" data-update-fab-name="{{ $productionplanning->pobuyer_no }}" data-update-fab-has="{{ $productionplanning->has_fab_cart }}" data-update-fab-date="{{ $productionplanning->fab_date }}" data-toggle="modal" data-target="#updateFabModal">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if($productionplanning->fab_date)
                                            @if (((now()->diffInDays($productionplanning->fab_date)) <= 7) && ((now()->diffInDays($productionplanning->fab_date)) > 3) && (now() < $productionplanning->fab_date))
                                            <td style="color:orange">{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                            @elseif ((((now()->diffInDays($productionplanning->fab_date)) <= 3) && ((now()->diffInDays($productionplanning->fab_date)) > 0) && (now() < $productionplanning->fab_date)))
                                            <td style="color:red">{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                            @else
                                            <td>{{ date('dmy', strtotime($productionplanning->fab_date)) }}</td>
                                            @endif
                                        @else
                                        <td>{{ $productionplanning->fab_date }}</td>
                                        @endif
                                        
                                        @if($productionplanning->has_acc_cart == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm btn-update-acc-record" data-update-acc-link="{{ route('productionplanning.updateacc', ['id' => $productionplanning->id]) }}" data-update-id-acc="{{ $productionplanning->id }}" data-update-acc-name="{{ $productionplanning->pobuyer_no }}" data-update-acc-has="{{ $productionplanning->has_acc_cart }}" data-update-acc-date="{{ $productionplanning->acc_date }}" data-toggle="modal" data-target="#updateAccModal">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm btn-update-acc-record" data-update-acc-link="{{ route('productionplanning.updateacc', ['id' => $productionplanning->id]) }}" data-update-id-acc="{{ $productionplanning->id }}" data-update-acc-name="{{ $productionplanning->pobuyer_no }}" data-update-acc-has="{{ $productionplanning->has_acc_cart }}" data-update-acc-date="{{ $productionplanning->acc_date }}" data-toggle="modal" data-target="#updateAccModal">
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
                                        @if($productionplanning->acc_date)
                                            @if (((now()->diffInDays($productionplanning->acc_date)) <= 7) && ((now()->diffInDays($productionplanning->acc_date)) > 3) && (now() < $productionplanning->acc_date))
                                            <td style="color:orange">{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                            @elseif ((((now()->diffInDays($productionplanning->acc_date)) <= 3) && ((now()->diffInDays($productionplanning->acc_date)) > 0) && (now() < $productionplanning->acc_date)) || (now() > $productionplanning->acc_date))
                                            <td style="color:red">{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                            @else
                                            <td>{{ date('dmy', strtotime($productionplanning->acc_date)) }}</td>
                                            @endif
                                        @else
                                        <td>{{ $productionplanning->acc_date }}</td>
                                        @endif

                                        <td align="center"><a id="show-detail-sew" class="btn btn-success btn-icon-split btn-sm btn-show-detail-sew" data-url-sew="{{ route('proplanacc.fetchaccsew', $productionplanning->order_list) }}" data-show-title-sew="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}" data-url-add-sew="{{ route('proplanacc.add', ['order_trans' => $productionplanning->order_trans, 'order_list' => $productionplanning->order_list]) }}">
                                            <span class="text">Detail</span>
                                        </a></td>
                                        <td align="center"><a id="show-detail-pack" class="btn btn-success btn-icon-split btn-sm btn-show-detail-pack" data-url-pack="{{ route('proplanacc.fetchaccpack', $productionplanning->order_list) }}" data-show-title-pack="{{ $productionplanning->order_list }} - {{ $productionplanning->pobuyer_no }}" data-url-add-pack="{{ route('proplanacc.add', ['order_trans' => $productionplanning->order_trans, 'order_list' => $productionplanning->order_list]) }}">
                                            <span class="text">Detail</span>
                                        </a></td>
                                        
                                        
                                        @if (((now()->diffInDays($productionplanning->bordir_approve)) <= 7) && ((now()->diffInDays($productionplanning->bordir_approve)) > 3) && (now() < $productionplanning->bordir_approve))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->bordir_approve)) <= 3) && ((now()->diffInDays($productionplanning->bordir_approve)) > 0) && (now() < $productionplanning->bordir_approve)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->bordir_approve)) }}</td>
                                        @endif


                                        
                                        @if (((now()->diffInDays($productionplanning->pattern_date)) <= 7) && ((now()->diffInDays($productionplanning->pattern_date)) > 3) && (now() < $productionplanning->pattern_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->pattern_date)) <= 3) && ((now()->diffInDays($productionplanning->pattern_date)) > 0) && (now() < $productionplanning->pattern_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->pattern_date)) }}</td>
                                        @endif
                                        
                                        @if (((now()->diffInDays($productionplanning->sampletest_date)) <= 7) && ((now()->diffInDays($productionplanning->sampletest_date)) > 3) && (now() < $productionplanning->sampletest_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->sampletest_date)) <= 3) && ((now()->diffInDays($productionplanning->sampletest_date)) > 0) && (now() < $productionplanning->sampletest_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->sampletest_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->reqmarker_date)) <= 7) && ((now()->diffInDays($productionplanning->reqmarker_date)) > 3) && (now() < $productionplanning->reqmarker_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->reqmarker_date)) <= 3) && ((now()->diffInDays($productionplanning->reqmarker_date)) >= 0) && (now() < $productionplanning->reqmarker_date)) )
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->reqmarker_date)) }}</td>
                                        @endif
                                        
                                        @if (((now()->diffInDays($productionplanning->marker_date)) <= 7) && ((now()->diffInDays($productionplanning->marker_date)) > 3) && (now() < $productionplanning->marker_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->marker_date)) <= 3) && ((now()->diffInDays($productionplanning->marker_date)) >= 0) && (now() < $productionplanning->marker_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->marker_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->pilotrun_date)) <= 7) && ((now()->diffInDays($productionplanning->pilotrun_date)) > 3) && (now() < $productionplanning->pilotrun_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->pilotrun_date)) <= 3) && ((now()->diffInDays($productionplanning->pilotrun_date)) > 0) && (now() < $productionplanning->pilotrun_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->pilotrun_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->ppm_date)) <= 7) && ((now()->diffInDays($productionplanning->ppm_date)) > 3) && (now() < $productionplanning->ppm_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->ppm_date)) <= 3) && ((now()->diffInDays($productionplanning->ppm_date)) > 0) && (now() < $productionplanning->ppm_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->ppm_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->startcut_date)) <= 7) && ((now()->diffInDays($productionplanning->startcut_date)) > 3) && (now() < $productionplanning->startcut_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->startcut_date)) <= 3) && ((now()->diffInDays($productionplanning->startcut_date)) > 0) && (now() < $productionplanning->startcut_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->startcut_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishcut_date)) <= 7) && ((now()->diffInDays($productionplanning->finishcut_date)) > 3) && (now() < $productionplanning->finishcut_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishcut_date)) <= 3) && ((now()->diffInDays($productionplanning->finishcut_date)) > 0) && (now() < $productionplanning->finishcut_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->finishcut_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->startsew_date)) <= 7) && ((now()->diffInDays($productionplanning->startsew_date)) > 3) && (now() < $productionplanning->startsew_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->startsew_date)) <= 3) && ((now()->diffInDays($productionplanning->startsew_date)) > 0) && (now() < $productionplanning->startsew_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->startsew_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishsew_date)) <= 7) && ((now()->diffInDays($productionplanning->finishsew_date)) > 3) && (now() < $productionplanning->finishsew_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishsew_date)) <= 3) && ((now()->diffInDays($productionplanning->finishsew_date)) > 0) && (now() < $productionplanning->finishsew_date)))
                                        <td style="color:red">{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @else
                                        <td>{{ date('dmy', strtotime($productionplanning->finishsew_date)) }}</td>
                                        @endif

                                        @if (((now()->diffInDays($productionplanning->finishpack_date)) <= 7) && ((now()->diffInDays($productionplanning->finishpack_date)) > 3) && (now() < $productionplanning->finishpack_date))
                                        <td style="color:orange">{{ date('dmy', strtotime($productionplanning->finishpack_date)) }}</td>
                                        @elseif ((((now()->diffInDays($productionplanning->finishpack_date)) <= 3) && ((now()->diffInDays($productionplanning->finishpack_date)) > 0) && (now() < $productionplanning->finishpack_date)))
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
        <div class="modal fade" id="updateSampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="update-sample-title" class="modal-title" id="exampleModalLabel">Update Sample</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('productionplanning.updatesample') }}" method="POST">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" id="update_id_sample" name="update_id_sample">
                                    <label>Has Sample?</label>
                                    <select class="form-control" id="update_has_sample" name="update_has_sample">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <br>
                                    <label>Sample Date :</label>
                                    <input class="form-control" type="date" id="update_sample_date" name="update_sample_date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateMIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="update-mi-title" class="modal-title" id="exampleModalLabel">Update MI</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('productionplanning.updatemi') }}" method="POST">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" id="update_id_mi" name="update_id_mi">
                                    <label>Has MI?</label>
                                    <select class="form-control" id="update_has_mi" name="update_has_mi">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <br>
                                    <label>MI Date :</label>
                                    <input class="form-control" type="date" id="update_mi_date" name="update_mi_date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateFabModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="update-fab-title" class="modal-title" id="exampleModalLabel">Update Fab Cart</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('productionplanning.updatefab') }}" method="POST">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" id="update_id_fab" name="update_id_fab">
                                    <label>Has Fab Cart?</label>
                                    <select class="form-control" id="update_has_fab" name="update_has_fab">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <br>
                                    <label>Fab Date :</label>
                                    <input class="form-control" type="date" id="update_fab_date" name="update_fab_date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateAccModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="update-acc-title" class="modal-title" id="exampleModalLabel">Update Acc Cart</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('productionplanning.updateacc') }}" method="POST">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" id="update_id_acc" name="update_id_acc">
                                    <label>Has Acc Cart?</label>
                                    <select class="form-control" id="update_has_acc" name="update_has_acc">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <br>
                                    <label>Acc Date :</label>
                                    <input class="form-control" type="date" id="update_acc_date" name="update_acc_date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Confirm</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>


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
                                    <a class="dropdown-item" href="{{ route('proplanacc.create') }}" target="_blank">Create Production Planning Detail</a>
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
                                    <a id="proplanacc_add" class="dropdown-item" href="{{ route('proplanacc.create') }}" target="_blank">Create Production Planning Detail</a>
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
                                                <th>Qty</th>
                                                <th>Unit</th>
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
    $('.btn-update-sample-record').on('click', function () {
        var has_sample = $(this).data('update-sample-has');
        var sample_date = $(this).data('update-sample-date');
        var id = $(this).data('update-id-sample');
        if (has_sample == "Yes") {
            document.getElementById("update_has_sample").options.selectedIndex = 0;
        } else {
            document.getElementById("update_has_sample").options.selectedIndex = 1;
        }
        $('#update_has_sample').change(function(){
            if ($(this).val() == "Yes") {
                $('#update_sample_date').prop('required',true);
            } else {
                $('#update_sample_date').removeAttr('required');
                $("#update_sample_date").val(null);
            }
        })
        $("#update_id_sample").val(id);
        $("#update_sample_date").val(sample_date);
        $("#modal-text-record-update-sample").text('Apakah anda yakin ingin mengupdate Sample Production Planning ' + $(this).data('update-sample-name') + '?');
    });
    $('.btn-update-mi-record').on('click', function () {
        var has_mi = $(this).data('update-mi-has');
        var mi_date = $(this).data('update-mi-date');
        var id = $(this).data('update-id-mi');
        if (has_mi == "Yes") {
            document.getElementById("update_has_mi").options.selectedIndex = 0;
        } else {
            document.getElementById("update_has_mi").options.selectedIndex = 1;
        }
        
        $('#update_has_mi').change(function(){
            if ($(this).val() == "Yes") {
                $('#update_mi_date').prop('required',true);
            } else {
                $('#update_mi_date').removeAttr('required');
                $("#update_mi_date").val(null);
            }
        })
        $("#update_id_mi").val(id);
        $("#update_mi_date").val(mi_date);
        $("#modal-text-record-update-mi").text('Apakah anda yakin ingin mengupdate MI Production Planning ' + $(this).data('update-mi-name') + '?');
    });
    
    $('.btn-update-fab-record').on('click', function () {
        var has_fab_cart = $(this).data('update-fab-has');
        var fab_date = $(this).data('update-fab-date');
        var id = $(this).data('update-id-fab');
        if (has_fab_cart == "Yes") {
            document.getElementById("update_has_fab").options.selectedIndex = 0;
        } else {
            document.getElementById("update_has_fab").options.selectedIndex = 1;
        }
        $('#update_has_fab').change(function(){
            if ($(this).val() == "Yes") {
                $('#update_fab_date').prop('required',true);
            } else {
                $('#update_fab_date').removeAttr('required');
                $("#update_fab_date").val(null);
            }
        })
        $("#update_id_fab").val(id);
        $("#update_fab_date").val(fab_date);
        $("#modal-text-record-update-fab").text('Apakah anda yakin ingin mengupdate Fab Cart Production Planning ' + $(this).data('update-fab-name') + '?');
    });

    $('.btn-update-acc-record').on('click', function () {
        var has_acc_cart = $(this).data('update-acc-has');
        var acc_date = $(this).data('update-acc-date');
        var id = $(this).data('update-id-acc');
        if (has_acc_cart == "Yes") {
            document.getElementById("update_has_acc").options.selectedIndex = 0;
        } else {
            document.getElementById("update_has_acc").options.selectedIndex = 1;
        }
        $('#update_has_acc').change(function(){
            if ($(this).val() == "Yes") {
                $('#update_acc_date').prop('required',true);
            } else {
                $('#update_acc_date').removeAttr('required');
                $("#update_acc_date").val(null);
            }
        })
        $("#update_id_acc").val(id);
        $("#update_acc_date").val(fab_date);
        $("#modal-text-record-update-acc").text('Apakah anda yakin ingin mengupdate Acc Cart Production Planning ' + $(this).data('update-acc-name') + '?');
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
            // document.getElementById("proplanacc_add").href = $(this).data('url-add-sew');
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
                            { data: 'qty', name: 'qty', orderable: false },
                            { data: 'accesories_unit', name: 'accesories_unit', orderable: false },
                        ],
                    });
            })
        });
    });
    $(document).ready(function () {
        $('body').on('click', '#show-detail-pack', function() {
            var jsonPackDetail = $(this).data('url-pack');
            // document.getElementById("proplanacc_add").href = $(this).data('url-add-pack');
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
                            { data: 'qty', name: 'qty', orderable: false },
                            { data: 'accesories_unit', name: 'accesories_unit', orderable: false },
                        ],
                    });
            })
        });
    });
</script>
</html>