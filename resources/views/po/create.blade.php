<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
@include('sweetalert::alert')
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
                    <h1 class="h3 mb-0 text-gray-800">Create Master PO</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Master PO</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('po.store') }}" enctype="multipart/form-data">
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
                                <label>PO No : </label>
                                @if(((substr($pos?->po_no,0,4)) == (date('y') . date('n') . 'E')) || ($setupincements->last_number ?? ''))
                                <input class="form-control" type="text" id="po_no" name="po_no" value="{{ 'PO' . str_pad(intval(substr($setupincements->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="po_no" name="po_no" value="{{ 'PO' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Master PO : </label>
                                @if(((substr($pos?->po_master,0,4)) == (date('y') . date('n') . 'E')) || ($setupincements->last_number ?? ''))
                                <input class="form-control" type="text" id="po_master" name="po_master" value="{{ date('y') . date('n') . 'E' . str_pad(intval(substr($pos?->po_master,-4)) + 1,4,'0',STR_PAD_LEFT) }}" readonly>
                                @else
                                <input class="form-control" type="text" id="po_master" name="po_master" value="{{ date('y') . date('n') . 'E' . str_pad(1,4,'0',STR_PAD_LEFT) }}" readonly>
                                @endif
                                
                                @if(((substr($pos?->po_master,0,4)) == (date('y') . date('n'))) || ($setupincements->last_number ?? ''))
                                <input class="form-control" type="hidden" id="po_number" name="po_number" value="{{ str_pad(intval(substr($pos?->po_master,-4)) + 1,4,'0',STR_PAD_LEFT) }}" readonly>
                                @else
                                <input class="form-control" type="hidden" id="po_number" name="po_number" value="{{ str_pad(1,4,'0',STR_PAD_LEFT) }}">
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Master PO Type :</label>
                                <select class="form-control" id="po_type" name="po_type">
                                    <option></option>
                                    @foreach($potypes as $potype)
                                    <option value="{{ $potype }}">{{ $potype }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Master PO Desc :</label>
                                <input class="form-control" type="text" id="po_desc" name="po_desc">
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
    $("#po_type").select2({
          allowClear: true,
          placeholder: 'Choose Master PO Type',
    });
    $(document).on("change", "#po_type", function(e){
        e.preventDefault();
        var po_type = $(this).val();
        var po_number = document.getElementById("po_number").value;
        if (po_type == 'E') {
            document.getElementById("po_master").value = "{{ date('y') . date('n') . 'E'  }}" + po_number
        } else if (po_type == 'C') {
            document.getElementById("po_master").value = "{{ date('y') . date('n') . 'C'  }}" + po_number
        } else if (po_type == 'K') {
            document.getElementById("po_master").value = "{{ date('y') . date('n') . 'K'  }}" + po_number
        }
    });
</script>
</html>