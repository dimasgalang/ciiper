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
                    <h1 class="h3 mb-0 text-gray-800">Create Shipment</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Shipment</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('shipment.store') }}">
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
                                <label>Ship No :</label>
                                @if($setupincements->last_number ?? '')
                                <input class="form-control" type="text" id="ship_no" name="ship_no" value="{{ 'SHI' . str_pad(intval(substr($setupincements->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="ship_no" name="ship_no" value="{{ 'SHI' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Master PO :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                    <option value="{{ $ordermaster->order_trans }}">{{ $ordermaster->order_trans }} - {{ $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>PO Buyer :</label>
                                <select class="form-control" id="order_list" name="order_list" disabled required>
                                    <option></option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Market : </label>
                                <select class="form-control" id="market_no" name="market_no" required>
                                    <option></option>
                                    @foreach($markets as $market)
                                    <option value="{{ $market->market_no }}">{{ $market->market_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Ship Mode :</label>
                                <select class="form-control" id="shipmode_no" name="shipmode_no" required>
                                    <option></option>
                                    @foreach($shipmodes as $shipmode)
                                    <option value="{{ $shipmode->shipmode_no }}">{{ $shipmode->shipmode_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Size :</label>
                                <select class="form-control" id="size_no" name="size_no" disabled>
                                    <option></option>
                                    @foreach($ordersizes as $ordersize)
                                    <option value="{{ $ordersize->size_no }}">{{ $ordersize->size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label id="ship_ready">Ship Qty :</label>
                                <input class="form-control" type="number" id="ship_qty" name="ship_qty" min="1" max="1" required>
                            </div>
                            <br>
                            <div>
                                <label id="carton_ready">Carton Qty :</label>
                                <input class="form-control" type="number" id="carton_qty" name="carton_qty" min="1" max="1" required>
                            </div>
                            <br>
                            <div>
                                <label>Ship Date :</label>
                                <input class="date form-control" type="date" id="ship_date" name="ship_date" required>
                            </div>
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark">
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
    $("#order_trans").select2({
          allowClear: true,
          placeholder: 'Choose Master PO',
    });
    $(document).on("change", "#order_trans", function(e){
        e.preventDefault();
        var order_trans = $(this).val();
        if (order_trans) {
            $.ajax({
                url: '/shipment/fetchorderlist/'+order_trans,
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
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose PO Buyer',
    });
    $(document).on("change", "#order_list", function(e){
        e.preventDefault();
        var order_list = $(this).val();
        var order_list = document.getElementById('order_list').value;
        if (order_list) {
            $.ajax({
                url: '/rafproduction/fetchcartonleft/'+order_list,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                                $('#carton_ready').text('Carton Qty : ' + (value.sum_carton));
                                $('#carton_qty').attr("max", (value.sum_carton));
                        });
                    } else {
                        $('#carton_ready').text('Ship Qty : ' + 0);
                        $('#carton_qty').attr("max", 0);
                    }
                }
            });
            $.ajax({
                url: '/shipment/fetchordersize/'+order_list,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#size_no').empty();
                    $('#size_no').append('<option></option>');
                    $.each(data, function(key, value) {
                        $('#size_no').append('<option value="'+ value.size_no +'">'+ value.size +'</option>');
                    });
                    $('#size_no').removeAttr('disabled');
                }
            });
        } else{
        }
    });
    $(document).on("change", "#size_no", function(e){
        e.preventDefault();
        var size_no = $(this).val();
        var order_list = document.getElementById('order_list').value;
        if (order_list) {
            $.ajax({
                url: '/shipment/fetchreadyship/'+order_list+'/'+size_no,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                                $('#ship_ready').text('Ship Qty : ' + (value.ready_ship));
                                $('#ship_qty').attr("max", (value.ready_ship));
                        });
                    } else {
                        $('#ship_ready').text('Ship Qty : ' + 0);
                        $('#ship_qty').attr("max", 0);
                    }
                }
            });
        } else{
        }
    });
    $("#market_no").select2({
          allowClear: true,
          placeholder: 'Choose Market',
    });
    $("#shipmode_no").select2({
          allowClear: true,
          placeholder: 'Choose Ship Mode',
    });
    $("#size_no").select2({
          allowClear: true,
          placeholder: 'Choose Size',
    });
    $("#submit").click(function() {
        $(this).hide();
    });
</script>
</html>