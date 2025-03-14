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
                    <h1 class="h3 mb-0 text-gray-800">Update RAF Production</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update RAF Production</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('rafproduction.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $rafproductions->id }}" readonly>
                            </div>
                            <div>
                                <label>Order Master / Master PO :</label>
                                <select class="form-control" id="order_trans" name="order_trans" readonly>
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_trans }}" {{ $rafproductions->order_trans == $orderlist->order_trans  ? 'selected' : ''}}>{{ $orderlist->order_trans }} - {{ $orderlist->po_master }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Order List / PO Buyer :</label>
                                <select class="form-control" id="order_list" name="order_list" readonly>
                                    <option></option>
                                    @foreach($orderlists as $orderlist)
                                        <option value="{{ $orderlist->order_list }}" {{ $rafproductions->order_list == $orderlist->order_list  ? 'selected' : ''}}>{{ $orderlist->order_list }} - {{ $orderlist->pobuyer_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>RAF No :</label>
                                <input class="form-control" type="text" id="raf_no" name="raf_no" value="{{ $rafproductions->raf_no }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>RAF Date :</label>
                                <input class="form-control" type="date" id="raf_date" name="raf_date" value="{{ $rafproductions->raf_date }}" required>
                            </div>
                            <br>
                            <div>
                                <input class="form-control" type="hidden" id="raf_qty_temp" value="{{ $rafproductions->raf_qty }}">
                                <label id="raf_left">RAF Qty :</label>
                                <input class="form-control" type="number" id="raf_qty" name="raf_qty" value="{{ $rafproductions->raf_qty }}" required>
                            </div>
                            <br>
                            <div>
                                <input class="form-control" type="hidden" id="raf_dept_temp" name="raf_dept_temp" value="{{ $rafproductions->raf_dept }}">
                                <label>RAF Dept :</label>
                                <select class="form-control" id="raf_dept" name="raf_dept" readonly>
                                    <option></option>
                                    @foreach($productiondepts as $productiondept)
                                        <option value="{{ $productiondept->dept_no }}" {{ $rafproductions->raf_dept == $productiondept->dept_no  ? 'selected' : ''}}>{{ $productiondept->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Size :</label>
                                <select class="form-control" id="order_size_no" name="order_size_no">
                                    <option></option>
                                    @foreach($ordersizes as $ordersize)
                                        <option value="{{ $ordersize->size_no }}" {{ $rafproductions->size_no == $ordersize->size_no  ? 'selected' : ''}}>{{ $ordersize->size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Remark :</label>
                                <input class="form-control" type="text" id="remark" name="remark" value="{{ $rafproductions->remark }}">
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
        var raf_dept = $('#raf_dept').val();
        var raf_dept_temp = $('#raf_dept_temp').val();
        var raf_qty_temp = $('#raf_qty_temp').val();
        var order_list = document.getElementById('order_list').value;
        if (raf_dept) {
            $.ajax({
                url: '/rafproduction/fetchrafleft/'+order_list+'/'+raf_dept,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            var allowraf = parseInt(raf_qty_temp) + parseInt(value.raf_left);
                            $('#raf_left').text('RAF Qty : ' + allowraf);
                            $('#raf_qty').attr("max",allowraf);
                        });
                    } else {
                        $('#raf_left').text('RAF Qty : ' + 0);
                        $('#raf_qty').attr("max",0);
                    }
                }
            });
        } else{
            var allowraf = parseInt(raf_qty) + parseInt(value.raf_left);
            $('#raf_left').text('RAF Qty : ' + allowraf);
            $('#raf_qty').attr("max",allowraf);
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
    $("#raf_dept").select2({
          allowClear: true,
          placeholder: 'Choose RAF Dept',
    });
    $("#order_size_no").select2({
          allowClear: true,
          placeholder: 'Choose Size',
    });
    $(document).on("change", "#raf_dept", function(e){
        e.preventDefault();
        var raf_dept_temp = $('#raf_dept_temp').val();
        var raf_dept = $(this).val();
        var raf_qty_temp = $('#raf_qty_temp').val();
        var raf_qty = $('#raf_qty').val();
        var order_list = document.getElementById('order_list').value;
        if (raf_dept) {
            $.ajax({
                url: '/rafproduction/fetchrafleft/'+order_list+'/'+raf_dept,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            if (raf_dept == raf_dept_temp) {
                                var allowraf = parseInt(raf_qty_temp) + parseInt(value.raf_left);
                                $('#raf_left').text('RAF Qty : ' + allowraf);
                                $('#raf_qty').attr("max",allowraf);
                            } else {
                                $('#raf_left').text('RAF Qty : ' + value.raf_left);
                                $('#raf_qty').attr("max",value.raf_left);
                            }
                        });
                    } else {
                        $('#raf_left').text('RAF Qty : ' + 0);
                        $('#raf_qty').attr("max",0);
                    }
                }
            });
        } else{
            var allowraf = parseInt(raf_qty) + parseInt(value.raf_left);
            $('#raf_left').text('RAF Qty : ' + allowraf);
            $('#raf_qty').attr("max",allowraf);
        }
    });
</script>
</html>