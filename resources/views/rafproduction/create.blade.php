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
                    <h1 class="h3 mb-0 text-gray-800">Create RAF Production</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create RAF Production</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('rafproduction.store') }}" enctype="multipart/form-data">
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
                                    <option></option>
                                    @foreach($ordermasters as $ordermaster)
                                    <option value="{{ $ordermaster->order_trans }}">{{ $ordermaster->order_trans }} - {{ $ordermaster->po_no }}</option>
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
                                <label>RAF No :</label>
                                <input class="form-control" type="text" id="raf_no" name="raf_no" value="{{ 'RAF' . str_pad($rafs->id + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>RAF Date :</label>
                                <input class="date form-control" type="date" id="raf_date" name="raf_date" required>
                            </div>
                            <br>
                            <div>
                                <label>RAF Qty :</label>
                                <input class="form-control" type="text" id="raf_qty" name="raf_qty">
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
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> -->
<script type="text/javascript">
    $("#order_list").select2({
          allowClear: true,
          placeholder: 'Choose Order List',
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
                url: '/rafproduction/fetchorderlist/'+order_trans,
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
</script>
<script type="text/javascript">
    $('.date').datepicker({
    format: 'YYYY-MM-DD',
    locale: 'en'
  });
</script>
</html>