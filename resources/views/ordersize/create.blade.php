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
                    <h1 class="h3 mb-0 text-gray-800">Create Order Size</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Order Size</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('ordersize.store') }}" enctype="multipart/form-data">
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
                                <label>Order Size No :</label>
                                @if($setupincements->last_number ?? '')
                                <input class="form-control" type="text" id="order_size_no" name="order_size_no" value="{{ 'ORZ' . str_pad(intval(substr($setupincements->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="order_size_no" name="order_size_no" value="{{ 'ORZ' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Order Master / Master PO :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                    <option value="{{ $ordermaster->order_trans }}">{{ $ordermaster->order_trans }} - {{ $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List :</label>
                                <select class="form-control" id="order_list" name="order_list" disabled>
                                    <option></option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Size No :</label>
                                <select class="form-control" id="size_no" name="size_no">
                                    <option></option>
                                    @foreach($sizes as $size)
                                    <option value="{{ $size->size_no }}">{{ $size->size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label id="qty_left">Order Size Qty :</label>
                                <input class="form-control" type="number" id="qty" name="qty" min="1" max="1">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <button id="submit" type="submit" class="btn btn-primary btn-block">Create</button>
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
    $("#chatid").select2({
          allowClear: true
    });
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose Order List',
    });
    $("#size_no").select2({
          allowClear: true,
          placeholder: 'Choose Size',
    });
    $("#order_trans").select2({
          allowClear: true,
          placeholder: 'Choose Master PO',
    });
    $(document).on("change", "#order_trans", function(e){
        e.preventDefault();
        var order_trans = $(this).val();
        if (order_trans) {
            $.ajax({
                url: '/ordersize/fetchorderlist/'+order_trans,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#order_list').empty();
                    $('#order_list').append('<option></option>');
                    $.each(data, function(key, value) {
                        $('#order_list').append('<option value="'+ value.order_list +'">'+ value.lot_no + ' - ' + value.pobuyer_no +'</option>');
                    });
                    $('#order_list').removeAttr('disabled');
                }
            });
        } else{
            $('#order_list').empty();
            $('#order_list').attr('disabled','disabled');
        }
    });
    $(document).on("change", "#order_list", function(e){
        e.preventDefault();
        var order_list = $(this).val();
        if (order_list) {
            $.ajax({
                url: '/ordersize/dcpoleft/'+order_list,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            $('#qty_left').text('Order Size Qty : ' + value.dcpo_left);
                            $('#qty').attr("max",value.dcpo_left);
                        });
                    } else {
                        $('#qty_left').text('Order Size Qty : ' + 0);
                        $('#qty').attr("max",0);
                    }
                }
            });
        } else{
            $('#qty_left').text('Order Size Qty : ' + value.dcpo_left);
            $('#qty').attr("max",value.dcpo_left);
        }
    });
    $("#submit").click(function() {
        $(this).hide();
    });
</script>
</html>