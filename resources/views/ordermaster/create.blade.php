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
                    <h1 class="h3 mb-0 text-gray-800">Create Order Master</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Order Master</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('ordermaster.store') }}" enctype="multipart/form-data">
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
                                <label>Order Trans :</label>
                                <input class="form-control" type="text" id="order_trans" name="order_trans" value="{{ 'ORM' . str_pad($ordermasters->id + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Season :</label>
                                <select class="form-control" id="season_no" name="season_no">
                                    @foreach($seasons as $season)
                                    <option value="{{ $season->season_no }}">{{ $season->season_no }} - {{ $season->season_cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Buyer :</label>
                                <select class="form-control" id="buyer_no" name="buyer_no">
                                    @foreach($buyers as $buyer)
                                    <option value="{{ $buyer->buyer_no }}">{{ $buyer->buyer_no }} - {{ $buyer->buyer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Brand :</label>
                                <select class="form-control" id="brand_no" name="brand_no">
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->brand_no }}">{{ $brand->brand_no }} - {{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Style :</label>
                                <select class="form-control" id="style_no" name="style_no">
                                    @foreach($styles as $style)
                                    <option value="{{ $style->style_no }}">{{ $style->style_no }} - {{ $style->style_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Master PO :</label>
                                <input class="form-control" type="text" id="po_no" name="po_no">
                            </div>
                            <br>
                            <div>
                                <label>QTY Order :</label>
                                <input class="form-control" type="text" id="qty_order" name="qty_order">
                            </div>
                            <br>
                            <div>
                                <label>QTY OCF :</label>
                                <input class="form-control" type="text" id="qty_ocf" name="qty_ocf">
                            </div>
                            <br>
                            <div>
                                <label>QTY GMT :</label>
                                <input class="form-control" type="text" id="qty_gmt" name="qty_gmt">
                            </div>
                            <br>
                            <div>
                                <label>QTY SBD :</label>
                                <input class="form-control" type="text" id="qty_sbd" name="qty_sbd">
                            </div>
                            <br>
                            <div>
                                <label>MR / Follow Up :</label>
                                <select class="form-control" id="fu_no" name="fu_no">
                                    @foreach($followups as $followup)
                                    <option value="{{ $followup->fu_no }}">{{ $followup->fu_no }} - {{ $followup->fu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Wash Type :</label>
                                <input class="form-control" type="text" id="wash_type" name="wash_type">
                            </div>
                            <br>
                            <div>
                                <label>Sketch Image :</label>
                                <br>
                                <input class="file" type="file" id="sketch_file" name="sketch_file">
                            </div>
                            <br>
                            <!-- <div>
                                <label>Sketch :</label>
                                <input class="form-control" type="text" id="sketch_file" name="sketch_file">
                            </div>
                            <br> -->
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $("#season_no").select2({
          allowClear: true
    });
    $("#buyer_no").select2({
          allowClear: true
    });
    $("#brand_no").select2({
          allowClear: true
    });
    $("#style_no").select2({
          allowClear: true
    });
    $("#fu_no").select2({
          allowClear: true
    });
</script>
</html>