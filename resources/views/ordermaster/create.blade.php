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
                                    <option></option>
                                    @foreach($buyers as $buyer)
                                    <option value="{{ $buyer->buyer_no }}">{{ $buyer->buyer_no }} - {{ $buyer->buyer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Brand :</label>
                                <select class="form-control" id="brand_no" name="brand_no" disabled>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Style :</label>
                                <select class="form-control" id="style_no" name="style_no" disabled>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Master PO : </label>
                                @if((substr($ordermasters->po_no,0,4)) == (date('y') . date('n') . 'E'))
                                <input class="form-control" type="text" id="po_no" name="po_no" value="{{ date('y') . date('n') . 'E' . str_pad(substr($ordermasters->po_no,-4) + 1,4,'0',STR_PAD_LEFT) }}" readonly>
                                @else
                                <input class="form-control" type="text" id="po_no" name="po_no" value="{{ date('y') . date('n') . 'E' . str_pad(1,4,'0',STR_PAD_LEFT) }}" readonly>
                                @endif
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
                                    <option></option>
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
<script type="text/javascript">
    $("#season_no").select2({
          allowClear: true,
          placeholder: 'Choose Season',
    });
    $("#buyer_no").select2({
          allowClear: true,
          placeholder: 'Choose Buyer',
    });
    $(document).on("change", "#buyer_no", function(e){
        e.preventDefault();
        var buyer_no = $(this).val();
        if (buyer_no) {
            $.ajax({
                url: '/ordermaster/fetchbrand/'+buyer_no,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#brand_no').empty();
                    $('#brand_no').append('<option></option>');
                    $.each(data, function(key, value) {
                        $('#brand_no').append('<option value="'+ value.brand_no +'">'+ value.brand_no + ' - ' + value.brand_name +'</option>');
                    });
                    $('#brand_no').removeAttr('disabled');
                }
            });
        } else{
            $('#brand_no').empty();
            $('#brand_no').attr('disabled','disabled');
        }
    });
    $("#brand_no").select2({
          allowClear: true,
          placeholder: 'Choose Brand',
    });
    $(document).on("change", "#brand_no", function(e){
        e.preventDefault();
        var brand_no = $(this).val();
        if (brand_no) {
            $.ajax({
                url: '/ordermaster/fetchstyle/'+brand_no,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#style_no').empty();
                    $('#style_no').append('<option></option>');
                    $.each(data, function(key, value) {
                        $('#style_no').append('<option value="'+ value.style_no +'">'+ value.style_no + ' - ' + value.style_name + '</option>');
                    });
                    $('#style_no').removeAttr('disabled');
                }
            });
        } else{
            $('#style_no').empty();
            $('#style_no').attr('disabled','disabled');
        }
    });
    $("#style_no").select2({
          allowClear: true,
          placeholder: 'Choose Style',
    });
    $("#fu_no").select2({
          allowClear: true,
          placeholder: 'Choose MR / Follow Up',
    });
</script>
</html>