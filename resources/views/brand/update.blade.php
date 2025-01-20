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
                    <h1 class="h3 mb-0 text-gray-800">Update Brand</h1>
                </div>
                

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Brand</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('brand.update') }}">
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
                                <input class="form-control" type="hidden" id="id" name="id" value="{{ $brands->id }}" readonly>
                            </div>
                            <br>
                            <div>
                                <label>Buyer No :</label>
                                <input class="form-control" type="text" id="buyer_no" name="buyer_no" value="{{ $brands->buyer_no }}" readonly>
                            </div>
                            <br>
                            <div>
                                <label>Brand No :</label>
                                <input class="form-control" type="text" id="brand_no" name="brand_no" value="{{ $brands->brand_no }}" readonly>
                            </div>
                            <br>
                            <div>
                                <label>Brand Name :</label>
                                <input class="form-control" type="text" id="brand_name" name="brand_name" value="{{ $brands->brand_name }}">
                            </div>
                            <br>
                            <div>
                                <label>Brand Gender : </label>
                                <select class="form-control" id="brand_gender" name="brand_gender">
                                    @foreach($genders as $gender)
                                    <option value="{{ $gender }}" {{ $brands->brand_gender == $gender  ? 'selected' : ''}}>{{ $gender }}</option>
                                    @endforeach
                                </select>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
    $("#chatid").select2({
          allowClear: true
    });
</script>
</html>