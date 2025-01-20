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
                                <label>Order Master :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    @foreach($ordermasters as $ordermaster)
                                    <option value="{{ $ordermaster->order_trans }}">{{ $ordermaster->order_trans . '-' . $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List :</label>
                                <select class="form-control" id="order_list" name="order_list">
                                    @foreach($orderlists as $orderlist)
                                    <option value="{{ $orderlist->order_list }}">{{ $orderlist->order_list }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Sample :</label>
                                <select class="form-control" id="has_sample" name="has_sample">
                                    <option></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>MI :</label>
                                <select class="form-control" id="has_mi" name="has_mi">
                                    <option></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Cart :</label>
                                <select class="form-control" id="has_cart" name="has_cart">
                                    <option></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <br>
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
                            <br>
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
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark" required>
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
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose Order List',
    });
    $("#has_sample").select2({
          allowClear: true,
          placeholder: 'Choose Is Has Sample?',
    });
    $("#has_mi").select2({
          allowClear: true,
          placeholder: 'Choose Is Has MI?',
    });
    $("#has_cart").select2({
          allowClear: true,
          placeholder: 'Choose Is Has Cart?',
    });
</script>
</html>