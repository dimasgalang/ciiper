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
                    <h1 class="h3 mb-0 text-gray-800">Create Production Planning</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Production Planning</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('productionplanning.store') }}" enctype="multipart/form-data">
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
                                <label>Plan No :</label>
                                @if($setupincements->last_number ?? '')
                                <input class="form-control" type="text" id="plan_no" name="plan_no" value="{{ 'PPL' . str_pad(intval(substr($setupincements->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="plan_no" name="plan_no" value="{{ 'PPL' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Order Master :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                    <option value="{{ $ordermaster->order_trans }}">{{ $ordermaster->order_trans . '-' . $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List :</label>
                                @if($lastorderlist->last_number ?? '')
                                <input class="form-control" type="text" id="order_list" name="order_list" value="{{ 'ORL' . str_pad(intval(substr($lastorderlist->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="order_list" name="order_list" value="{{ 'ORL' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Factory :</label>
                                <select class="form-control" id="factory_no" name="factory_no">
                                    <option></option>
                                    @foreach($factorys as $factory)
                                    <option value="{{ $factory->factory_no }}">{{ $factory->factory_no }} - {{ $factory->factory_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><hr>
                            <h3>Order</h3>
                            <div class="row">
                                <div class="col-xl-3">
                                    <div>
                                        <label id="dcpo_left">DC PO Qty :</label>
                                        <input class="form-control" type="number" id="dcpo_qty" name="dcpo_qty" min="1" max="0" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Wash Type :</label>
                                        <select class="form-control" id="wash_no" name="wash_no">
                                            @foreach($washtypes as $washtype)
                                            <option value="{{ $washtype->wash_no }}">{{ $washtype->wash_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Target Qty :</label>
                                        <input class="date form-control" type="number" id="target_qty" name="target_qty" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Carton Qty :</label>
                                        <input class="form-control" type="number" id="carton_qty" name="carton_qty" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Bordir Type :</label>
                                        <select class="form-control" id="bordir_no" name="bordir_no">
                                            @foreach($bordirtypes as $bordirtype)
                                            <option value="{{ $bordirtype->bordir_no }}">{{ $bordirtype->bordir_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Production Day :</label>
                                        <input class="date form-control" type="number" id="production_day" name="production_day" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Lot :</label>
                                        <input class="form-control" type="text" id="lot_no" name="lot_no" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Ex Factory Date :</label>
                                        <input class="date form-control" type="date" id="ex_factory_date" name="ex_factory_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Line :</label>
                                        <input class="date form-control" type="number" id="line" name="line" required>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>PO Buyer :</label>
                                        <input class="form-control" type="text" id="pobuyer_no" name="pobuyer_no" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Ship Date :</label>
                                        <input class="date form-control" type="date" id="vsl_date" name="vsl_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>SMV :</label>
                                        <input class="date form-control" type="decimal" id="smv" name="smv" required>
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
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>MI :</label>
                                        <select class="form-control" id="has_mi" name="has_mi">
                                            <option></option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Fab Cart :</label>
                                        <select class="form-control" id="has_fab_cart" name="has_fab_cart">
                                            <option></option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div>
                                        <label>Acc Cart :</label>
                                        <select class="form-control" id="has_acc_cart" name="has_acc_cart">
                                            <option></option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br><hr>
                            <h3>Planning Accesories</h3>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div id="accesoriesSew">
                                        <label>Accesories Sewing :</label>
                                        <div class="row">
                                            <div class="col-xl-5">
                                                <select class="form-control accesories_sew" id="accesories_sew" name="accesories_sew[]" >
                                                    @foreach($accesoriessewings as $accesoriessewing)
                                                    <option value="{{ $accesoriessewing->accesories_no }}">{{ $accesoriessewing->accesories_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <input class="form-control item_date_sew" type="date" id="item_date_sew" name="item_date_sew[]" required>
                                            </div>
                                            <div class="col-xl-3">
                                                <button type="button" class="btn btn-primary btn-block" onclick="addRecordsAccSewing()">Add</button>
                                            </div>
                                        </div><br>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div id="accesoriesPack">
                                        <label>Accesories Sewing :</label>
                                        <div class="row">
                                            <div class="col-xl-5">
                                                <select class="form-control accesories_pack" id="accesories_pack" name="accesories_pack[]" >
                                                    @foreach($accesoriespackings as $accesoriespacking)
                                                    <option value="{{ $accesoriespacking->accesories_no }}">{{ $accesoriespacking->accesories_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <input class="form-control item_date_pack" type="date" id="item_date_pack" name="item_date_pack[]" required>
                                            </div>
                                            <div class="col-xl-3">
                                                <button type="button" class="btn btn-primary btn-block" onclick="addRecordsAccPacking()">Add</button>
                                            </div>
                                        </div><br>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br><hr>
                            <h3>Planning Date</h3>
                            <div class="row">
                                <div class="col-xl-4">
                                    <div>
                                        <label>Fabric :</label>
                                        <input class="form-control" type="date" id="fab_date" name="fab_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Acc :</label>
                                        <input class="form-control" type="date" id="acc_date" name="acc_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Bordir Approve :</label>
                                        <input class="form-control" type="date" id="bordir_approve" name="bordir_approve" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Pattern :</label>
                                        <input class="form-control" type="date" id="pattern_date" name="pattern_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Sample Test :</label>
                                        <input class="form-control" type="date" id="sampletest_date" name="sampletest_date" required>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div>
                                        <label>Req Marker :</label>
                                        <input class="form-control" type="date" id="reqmarker_date" name="reqmarker_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Marker :</label>
                                        <input class="form-control" type="date" id="marker_date" name="marker_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Pilot Run :</label>
                                        <input class="form-control" type="date" id="pilotrun_date" name="pilotrun_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>PPM :</label>
                                        <input class="form-control" type="date" id="ppm_date" name="ppm_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Start Cutting :</label>
                                        <input class="form-control" type="date" id="startcut_date" name="startcut_date" required>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div>
                                        <label>Finish Cutting :</label>
                                        <input class="form-control" type="date" id="finishcut_date" name="finishcut_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Start Sewing :</label>
                                        <input class="form-control" type="date" id="startsew_date" name="startsew_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Finish Sewing :</label>
                                        <input class="form-control" type="date" id="finishsew_date" name="finishsew_date" required>
                                    </div>
                                    <br>
                                    <div>
                                        <label>Finish Packing :</label>
                                        <input class="form-control" type="date" id="finishpack_date" name="finishpack_date" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Create</button>
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
    $("#has_sample").select2({
          allowClear: true,
          placeholder: 'Choose Is Has Sample?',
    });
    $("#has_mi").select2({
          allowClear: true,
          placeholder: 'Choose Is Has MI?',
    });
    $("#has_fab_cart").select2({
          allowClear: true,
          placeholder: 'Choose Is Has Fab Cart?',
    });
    $("#has_acc_cart").select2({
          allowClear: true,
          placeholder: 'Choose Is Has Acc Cart?',
    });
    $("#factory_no").select2({
          allowClear: true,
          placeholder: 'Choose Factory',
    });
    $(document).on("change", "#order_trans", function(e){
        e.preventDefault();
        var order_trans = $(this).val();
        if (order_trans) {
            $.ajax({
                url: '/orderlist/fetchorderleft/'+order_trans,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $.each(data, function(key, value) {
                        $('#dcpo_left').text('DC PO Qty : ' + value.qty_left);
                        $('#dcpo_qty').attr("max",value.qty_left);
                    });
                }
            });
        } else{
            $('#dcpo_left').text('DC PO Qty : ' + value.qty_left);
            $('#dcpo_qty').attr("max",value.qty_left);
        }
    });
</script>
<script type="text/javascript">
    function addRecordsAccSewing() {
        $("#accesoriesSew").append('<div class="row"><br><br><div class="col-xl-5"><select class="form-control accesories_sew" id="accesories_sew" name="accesories_sew[]">@foreach($accesoriessewings as $accesoriessewing)<option value="{{ $accesoriessewing->accesories_no }}">{{ $accesoriessewing->accesories_name }}</option>@endforeach</select></div><div class="col-xl-4"><input class="form-control item_date_sew" type="date" id="item_date_sew" name="item_date_sew[]" required></div><div class="col-xl-3"><button type="button" class="btn btn-danger btn-block removeThisSewing">Remove</button></div></div>');
        $('.accesories_sew').select2({
            allowClear: true,
            placeholder: 'Choose Accesories',
        });
    }

    $(document).on('click', '.removeThisSewing', function() {
        $(this).parent().parent().remove();
    })
    $('.accesories_sew').select2({
        allowClear: true,
        placeholder: 'Choose Accesories',
    });


    
    function addRecordsAccPacking() {
        $("#accesoriesPack").append('<div class="row"><br><br><div class="col-xl-5"><select class="form-control accesories_pack" id="accesories_pack" name="accesories_pack[]">@foreach($accesoriespackings as $accesoriespacking)<option value="{{ $accesoriespacking->accesories_no }}">{{ $accesoriespacking->accesories_name }}</option>@endforeach</select></div><div class="col-xl-4"><input class="form-control item_date_pack" type="date" id="item_date_pack" name="item_date_pack[]" required></div><div class="col-xl-3"><button type="button" class="btn btn-danger btn-block removeThisPacking">Remove</button></div></div>');
        $('.accesories_pack').select2({
            allowClear: true,
            placeholder: 'Choose Accesories',
        });
    }

    $(document).on('click', '.removeThisPacking', function() {
        $(this).parent().parent().remove();
    })
    $('.accesories_pack').select2({
        allowClear: true,
        placeholder: 'Choose Accesories',
    });
</script>
</html>