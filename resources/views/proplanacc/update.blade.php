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
                    <h1 class="h3 mb-0 text-gray-800">Update Production Planning Detail</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Production Planning Detail</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('proplanacc.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $proplanacc->id }}" readonly>
                            </div>
                            <div>
                                <label>Order Master :</label>
                                <select class="form-control" id="order_trans" name="order_trans">
                                    @foreach($ordermasters as $ordermaster)
                                        <option value="{{ $ordermaster->order_trans }}" {{ $proplanacc->order_trans == $ordermaster->order_trans  ? 'selected' : ''}}>{{ $ordermaster->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List / PO Buyer :</label>
                                <select class="form-control" id="order_list" name="order_list">
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_list }}" {{ $proplanacc->order_list == $orderlist->order_list  ? 'selected' : ''}}>{{ $orderlist->pobuyer_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Category :</label>
                                <select class="form-control" id="category_no" name="category_no">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category_no }}" {{ $proplanacc->category_no == $category->category_no  ? 'selected' : ''}}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Accesories :</label>
                                <select class="form-control" id="accesories_no" name="accesories_no">
                                    @foreach($accesories as $accesory)
                                        <option value="{{ $accesory->accesories_no }}" {{ $proplanacc->accesories_no == $accesory->accesories_no  ? 'selected' : ''}}>{{ $accesory->accesories_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Item Date :</label>
                                <input class="form-control" type="date" id="item_date" name="item_date" value="{{ $proplanacc->item_date }}">
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
    $("#category_no").select2({
          allowClear: true,
          placeholder: 'Choose Category',
    });
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose Category',
    });
    $("#accesories_no").select2({
          allowClear: true,
          placeholder: 'Choose Category',
    });
</script>
</html>