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
                    <h1 class="h3 mb-0 text-gray-800">Update Order List</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Order List</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('orderlist.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $orderlists->id }}" required readonly>
                            </div>
                            <div>
                                <label>Order Trans :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    @foreach($ordermasters as $ordermaster)
                                        <option value="{{ $ordermaster->order_trans }}" {{ $orderlists->order_trans == $ordermaster->order_trans  ? 'selected' : ''}}>{{ $ordermaster->order_trans }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List :</label>
                                <input class="form-control" type="text" id="order_list" name="order_list" value="{{ $orderlists->order_list }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Factory :</label>
                                <select class="form-control" id="factory_no" name="factory_no">
                                    @foreach($factorys as $factory)
                                        <option value="{{ $factory->factory_no }}" {{ $orderlists->factory_no == $factory->factory_no  ? 'selected' : ''}}>{{ $factory->factory_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Lot :</label>
                                <input class="form-control" type="text" id="lot_no" name="lot_no" value="{{ $orderlists->lot_no }}" required>
                            </div>
                            <br>
                            <div>
                                <label>PO Buyer :</label>
                                <input class="form-control" type="text" id="pobuyer_no" name="pobuyer_no" value="{{ $orderlists->pobuyer_no }}" required>
                            </div>
                            <br>
                            <div>
                                <label>DC PO Qty :</label>
                                <input class="form-control" type="text" id="dcpo_qty" name="dcpo_qty" value="{{ $orderlists->dcpo_qty }}" required>
                            </div>
                            <br>
                            <div>
                                <label>Ex Factory Date :</label>
                                <input class="date form-control" type="date" id="ex_factory_date" name="ex_factory_date" value="{{ $orderlists->ex_factory_date }}" required>
                            </div>
                            <br>
                            <div>
                                <label>VSL Date :</label>
                                <input class="date form-control" type="date" id="vsl_date" name="vsl_date" value="{{ $orderlists->vsl_date }}" required>
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
          allowClear: true
    });
</script>
</html>