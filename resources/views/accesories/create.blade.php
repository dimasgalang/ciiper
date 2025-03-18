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
                    <h1 class="h3 mb-0 text-gray-800">Create Accesories</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Create Accesories</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('accesories.store') }}" enctype="multipart/form-data">
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
                                <label>Accesories No :</label>
                                @if($setupincements->last_number ?? '')
                                <input class="form-control" type="text" id="accesories_no" name="accesories_no" value="{{ 'ACC' . str_pad(intval(substr($setupincements->last_number,3,9)) + 1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @else
                                <input class="form-control" type="text" id="accesories_no" name="accesories_no" value="{{ 'ACC' . str_pad(1,9,'0',STR_PAD_LEFT) }}" required readonly>
                                @endif
                            </div>
                            <br>
                            <div>
                                <label>Category No :</label>
                                <select class="form-control" id="category_no" name="category_no">
                                    <option></option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_no }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div>
                                <label>Accesories Name:</label>
                                <input class="form-control" type="text" id="accesories_name" name="accesories_name" required>
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
    $("#category_no").select2({
          allowClear: true,
          placeholder: 'Choose Category',
    });
    $("#submit").click(function() {
        $(this).hide();
    });
</script>
</html>