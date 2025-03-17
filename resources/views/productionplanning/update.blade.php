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
                    <h1 class="h3 mb-0 text-gray-800">Update Production Planning</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Production Planning</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('productionplanning.update') }}" enctype="multipart/form-data">
                            @csrf
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $productionplannings[0]->id }}" readonly>
                                <input class="form-control" type="hidden" id="idorder" name="idorder" value="{{ $productionplannings[0]->idorder }}" readonly>
                            </div>
                            <div>
                                <label>Plan No :</label>
                                <input class="form-control" type="text" id="plan_no" name="plan_no" value="{{ $productionplannings[0]->plan_no }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Order Trans :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                        <option value="{{ $ordermaster->order_trans }}" {{ $productionplannings[0]->order_trans == $ordermaster->order_trans  ? 'selected' : ''}}>{{ $ordermaster->order_trans }} - {{ $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List :</label>
                                <select class="form-control" id="order_list" name="order_list">
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_list }}" {{ $productionplannings[0]->order_list == $orderlist->order_list  ? 'selected' : ''}}>{{ $orderlist->order_list }} - {{ $orderlist->pobuyer_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Factory :</label>
                                <select class="form-control" id="factory_no" name="factory_no">
                                    <option></option>
                                    @foreach($factorys as $factory)
                                        <option value="{{ $factory->factory_no }}" {{ $productionplannings[0]->factory_no == $factory->factory_no  ? 'selected' : ''}}>{{ $factory->factory_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><hr>
                            <h3>Order</h3>
                            <div class="row">
                                <div class="col-xl-3">
                                    <div>
                                        <label id="dcpo_left">DC PO Qty :</label>
                                        <input class="form-control" type="number" id="dcpo_qty" name="dcpo_qty" value="{{ $productionplannings[0]->dcpo_qty }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Wash Type :</label>
                                        <select class="form-control" id="wash_no" name="wash_no">
                                            @foreach($washtypes as $washtype)
                                            <option value="{{ $washtype->wash_no }}" {{ $productionplannings[0]->wash_no == $washtype->wash_no  ? 'selected' : ''}}>{{ $washtype->wash_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Target Qty :</label>
                                        <input class="date form-control" type="number" id="target_qty" name="target_qty" value="{{ $productionplannings[0]->target_qty }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Carton Qty :</label>
                                        <input class="form-control" type="number" id="carton_qty" name="carton_qty" value="{{ $productionplannings[0]->carton_qty }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Bordir Type :</label>
                                        <select class="form-control" id="bordir_no" name="bordir_no">
                                            @foreach($bordirtypes as $bordirtype)
                                            <option value="{{ $bordirtype->bordir_no }}" {{ $productionplannings[0]->bordir_no == $bordirtype->bordir_no  ? 'selected' : ''}}>{{ $bordirtype->bordir_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Production Day :</label>
                                        <input class="date form-control" type="number" id="production_day" name="production_day" value="{{ $productionplannings[0]->production_day }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Lot :</label>
                                        <input class="form-control" type="text" id="lot_no" name="lot_no" value="{{ $productionplannings[0]->lot_no }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Ex Factory Date :</label>
                                        <input class="date form-control" type="date" id="ex_factory_date" name="ex_factory_date" value="{{ $productionplannings[0]->ex_factory_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Line :</label>
                                        <input class="date form-control" type="number" id="line" name="line" value="{{ $productionplannings[0]->line }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>PO Buyer :</label>
                                        <input class="form-control" type="text" id="pobuyer_no" name="pobuyer_no" value="{{ $productionplannings[0]->pobuyer_no }}" required readonly>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Ship Date :</label>
                                        <input class="date form-control" type="date" id="vsl_date" name="vsl_date" value="{{ $productionplannings[0]->vsl_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>SMV :</label>
                                        <input class="date form-control" type="decimal" id="smv" name="smv" value="{{ $productionplannings[0]->smv }}" required>
                                    </div>
                                </div>
                            </div>
                            <br><hr>
                            <h3>Cart</h3>
                            <div class="row">
                                <div class="col-xl-3">
                                    <div>
                                        <label>Sample :</label>
                                        <select class="form-control" id="has_sample" name="has_sample">
                                            <option></option>
                                            <option value="Yes" {{ $productionplannings[0]->has_sample == 'Yes'  ? 'selected' : ''}}>Yes</option>
                                            <option value="No" {{ $productionplannings[0]->has_sample == 'No'  ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>MI :</label>
                                        <select class="form-control" id="has_mi" name="has_mi">
                                            <option></option>
                                            <option value="Yes" {{ $productionplannings[0]->has_mi == 'Yes'  ? 'selected' : ''}}>Yes</option>
                                            <option value="No" {{ $productionplannings[0]->has_mi == 'No'  ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Fab Cart :</label>
                                        <select class="form-control" id="has_fab_cart" name="has_fab_cart">
                                            <option></option>
                                            <option value="Yes" {{ $productionplannings[0]->has_fab_cart == 'Yes'  ? 'selected' : ''}}>Yes</option>
                                            <option value="No" {{ $productionplannings[0]->has_fab_cart == 'No'  ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Acc Cart :</label>
                                        <select class="form-control" id="has_acc_cart" name="has_acc_cart">
                                            <option></option>
                                            <option value="Yes" {{ $productionplannings[0]->has_acc_cart == 'Yes'  ? 'selected' : ''}}>Yes</option>
                                            <option value="No" {{ $productionplannings[0]->has_acc_cart == 'No'  ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br><hr>
                            <h3>Planning Date</h3>
                            <div class="row">
                                <div class="col-xl-4">
                                    <div>
                                        <label>Fabric :</label>
                                        <input class="form-control" type="date" id="fab_date" name="fab_date" value="{{ $productionplannings[0]->fab_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Acc :</label>
                                        <input class="form-control" type="date" id="acc_date" name="acc_date" value="{{ $productionplannings[0]->acc_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Bordir Approve :</label>
                                        <input class="form-control" type="date" id="bordir_approve" name="bordir_approve" value="{{ $productionplannings[0]->bordir_approve }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Pattern :</label>
                                        <input class="form-control" type="date" id="pattern_date" name="pattern_date" value="{{ $productionplannings[0]->pattern_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Sample Test :</label>
                                        <input class="form-control" type="date" id="sampletest_date" name="sampletest_date" value="{{ $productionplannings[0]->sampletest_date }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div>
                                        <label>Req Marker :</label>
                                        <input class="form-control" type="date" id="reqmarker_date" name="reqmarker_date" value="{{ $productionplannings[0]->reqmarker_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Marker :</label>
                                        <input class="form-control" type="date" id="marker_date" name="marker_date" value="{{ $productionplannings[0]->marker_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Pilot Run :</label>
                                        <input class="form-control" type="date" id="pilotrun_date" name="pilotrun_date" value="{{ $productionplannings[0]->pilotrun_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>PPM :</label>
                                        <input class="form-control" type="date" id="ppm_date" name="ppm_date" value="{{ $productionplannings[0]->ppm_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Start Cutting :</label>
                                        <input class="form-control" type="date" id="startcut_date" name="startcut_date" value="{{ $productionplannings[0]->startcut_date }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div>
                                        <label>Finish Cutting :</label>
                                        <input class="form-control" type="date" id="finishcut_date" name="finishcut_date" value="{{ $productionplannings[0]->finishcut_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Start Sewing :</label>
                                        <input class="form-control" type="date" id="startsew_date" name="startsew_date" value="{{ $productionplannings[0]->startsew_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Finish Sewing :</label>
                                        <input class="form-control" type="date" id="finishsew_date" name="finishsew_date" value="{{ $productionplannings[0]->finishsew_date }}" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Finish Packing :</label>
                                        <input class="form-control" type="date" id="finishpack_date" name="finishpack_date" value="{{ $productionplannings[0]->finishpack_date }}" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark" value="{{ $productionplannings[0]->remark }}">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

@include('layout.footer')
</body>
<script type="text/javascript">
    $("#order_trans").select2({
          allowClear: true,
          placeholder: 'Choose Master PO',
    });
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose Order List',
    });
    $("#has_sample").select2({
          allowClear: true,
          placeholder: 'Choose',
    });
    $("#has_mi").select2({
          allowClear: true,
          placeholder: 'Choose',
    });
    $("#has_fab_cart").select2({
          allowClear: true,
          placeholder: 'Choose',
    });
    $("#has_acc_cart").select2({
          allowClear: true,
          placeholder: 'Choose',
    });
    $("#factory_no").select2({
          allowClear: true,
          placeholder: 'Choose Factory',
    });
</script>
</html>