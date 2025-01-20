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
                    <h1 class="h3 mb-0 text-gray-800">Update Fabrication</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Fabrication</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('fabrication.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $fabrications->id }}" readonly>
                            </div>
                            <div>
                                <label>Order Trans :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                        <option value="{{ $ordermaster->order_trans }}" {{ $fabrications->order_trans == $ordermaster->order_trans  ? 'selected' : ''}}>{{ $ordermaster->order_trans }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Fabrication No :</label>
                                <input class="form-control" type="text" id="fab_no" name="fab_no" value="{{ $fabrications->fab_no }}" readonly required>
                            </div>
                            <br>
                            <div>
                                <label>Fabric Mill :</label>
                                <select class="form-control" id="fabmill_no" name="fabmill_no">
                                    <option></option>
                                    @foreach($fabmills as $fabmill)
                                    <option value="{{ $fabmill->fabmill_no }}" {{ $fabrications->fabmill_no == $fabmill->fabmill_no  ? 'selected' : ''}}>{{ $fabmill->fabmill_no }} - {{ $fabmill->fabmill_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Fabrication :</label>
                                <textarea class="form-control" type="text" id="fabrication" name="fabrication" required>{{ $fabrications->fabrication }}</textarea>
                            </div>
                            <br>
                            <div>
                                <label>PO Fabrication :</label>
                                <textarea class="form-control" type="text" id="po_fab" name="po_fab" required>{{ $fabrications->po_fab }}</textarea>
                            </div>
                            <br>
                            <div>
                                <label>ETD :</label>
                                <textarea class="form-control" type="text" id="etd" name="etd" required>{{ $fabrications->etd }}</textarea>
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
    $("#fabmill_no").select2({
          allowClear: true,
          placeholder: 'Choose Fabric Mill',
    });
</script>
</html>