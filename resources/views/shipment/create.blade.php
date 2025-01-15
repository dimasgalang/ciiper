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
                                <label>PO Buyer :</label>
                                <select class="form-control" id="order_list" name="order_list">
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                    <option value="{{ $orderlist->order_list }}">{{ $orderlist->pobuyer_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Market : </label>
                                <select class="form-control" id="market_no" name="market_no">
                                    <option></option>
                                    @foreach($markets as $market)
                                    <option value="{{ $market->market_no }}">{{ $market->market_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Ship Mode :</label>
                                <select class="form-control" id="ship_no" name="ship_no">
                                    <option></option>
                                    @foreach($shipmodes as $shipmode)
                                    <option value="{{ $shipmode->ship_no }}">{{ $shipmode->ship_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Ship Qty :</label>
                                <input class="form-control" type="text" id="ship_qty" name="ship_qty" required>
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
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose PO Buyer',
    });
    $("#market_no").select2({
          allowClear: true,
          placeholder: 'Choose Market',
    });
    $("#ship_no").select2({
          allowClear: true,
          placeholder: 'Choose Ship Mode',
    });
</script>
</html>