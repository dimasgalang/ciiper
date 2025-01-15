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
                    <h1 class="h3 mb-0 text-gray-800">Update Style</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Update Style</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('style.update') }}" enctype="multipart/form-data">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $styles->id }}" readonly>
                            </div>
                            <div>
                                <label>Brand No :</label>
                                <input class="form-control" type="text" id="brand_no" name="brand_no" value="{{ $styles->brand_no }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Style No :</label>
                                <input class="form-control" type="text" id="style_no" name="style_no" value="{{ $styles->style_no }}" required readonly>
                            </div>
                            <br>
                            <div>
                                <label>Style Name :</label>
                                <input class="form-control" type="text" id="style_name" name="style_name" value="{{ $styles->style_name }}">
                            </div>
                            <br>
                            <div>
                                <label>Style Desc :</label>
                                <input class="form-control" type="text" id="style_desc" name="style_desc" value="{{ $styles->style_desc }}">
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
</html>