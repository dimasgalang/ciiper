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
                    <h1 class="h3 mb-0 text-gray-800">Update Shipment</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Shipment</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('shipment.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $shipments->id }}" readonly>
                            </div>
                            <div>
                                <label>Ship No :</label>
                                <input class="form-control" type="text" id="ship_no" name="ship_no" value="{{ $shipments->ship_no }}" readonly>
                            </div>
                            <br>
                            <div>
                                <label>Order Master / Master PO :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_trans }}" selected>{{ $orderlist->order_trans }} - {{ $orderlist->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List / PO Buyer :</label>
                                <select class="form-control" id="order_list" name="order_list" readonly>
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_list }}" {{ $shipments->order_list == $orderlist->order_list  ? 'selected' : ''}}>{{ $orderlist->order_list }} - {{ $orderlist->pobuyer_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Market :</label>
                                <select class="form-control" id="market_no" name="market_no">
                                    <option></option>
                                    @foreach($markets as $market)
                                        <option value="{{ $market->market_no }}" {{ $shipments->market_no == $market->market_no  ? 'selected' : ''}}>{{ $market->market_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Ship Mode :</label>
                                <select class="form-control" id="shipmode_no" name="shipmode_no">
                                    <option></option>
                                    @foreach($shipmodes as $shipmode)
                                        <option value="{{ $shipmode->shipmode_no }}" {{ $shipments->shipmode_no == $shipmode->shipmode_no  ? 'selected' : ''}}>{{ $shipmode->shipmode_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Ship Date :</label>
                                <input class="form-control" type="date" id="ship_date" name="ship_date" value="{{ $shipments->ship_date }}" required>
                            </div>
                            <br>
                            <div>
                            <input class="form-control" type="hidden" id="ship_qty_temp" value="{{ $shipments->ship_qty }}">
                                <label id="ship_left">Ship Qty :</label>
                                <input class="form-control" type="number" id="ship_qty" name="ship_qty" value="{{ $shipments->ship_qty }}" required>
                            </div>
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark" value="{{ $shipments->remark }}">
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
    $(document).ready(function(e) {
        var ship_qty_temp = $('#ship_qty_temp').val();
        var order_list = document.getElementById('order_list').value;
        if (order_list) {
            $.ajax({
                url: '/shipment/fetchreadyship/'+order_list,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            var allowship = parseInt(ship_qty_temp) + parseInt(value.ship_left);
                            $('#ship_left').text('Ship Qty : ' + allowship);
                            $('#ship_qty').attr("max",allowship);
                        });
                    } else {
                        $('#ship_left').text('Ship Qty : ' + 0);
                        $('#ship_qty').attr("max",0);
                    }
                }
            });
        } else{
            var allowship = parseInt(ship_qty) + parseInt(value.ship_left);
            $('#ship_left').text('Ship Qty : ' + allowship);
            $('#ship_qty').attr("max",allowship);
        }
    });
    $("#order_trans").select2({
          allowClear: true,
          placeholder: 'Choose Master PO',
    });
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose PO Buyer',
    });
    $("#market_no").select2({
          allowClear: true,
          placeholder: 'Choose Market',
    });
    $("#shipmode_no").select2({
          allowClear: true,
          placeholder: 'Choose Ship Mode',
    });
</script>
</html>