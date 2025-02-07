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
                    <h1 class="h3 mb-0 text-gray-800">Production Planning List</h1>
                    <div>
                    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#importModal"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Import Production Planning</a>
                    <a href="{{ route('productionplanning.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Production Planning</a>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Production Planning Data</h6>
                    </div>
                    <div class="card-body">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Master PO</th>
                                        <th>PO Buyer</th>
                                        <th>Sample</th>
                                        <th>MI</th>
                                        <th>Acc Fab Cart</th>
                                        <th>Fab</th>
                                        <th>Acc</th>
                                        <th>Bordir Approve</th>
                                        <th>Pattern</th>
                                        <th>Sample Test</th>
                                        <th>Marker</th>
                                        <th>Pilot Run</th>
                                        <th>PPM</th>
                                        <th>Start Cut.</th>
                                        <th>Finish Cut.</th>
                                        <th>Start Sew.</th>
                                        <th>Finish Sew.</th>
                                        <th>Finish Pack.</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productionplannings as $productionplanning)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $productionplanning->po_master }}</td>
                                        <td>{{ $productionplanning->pobuyer_no }}</td>
                                        @if($productionplanning->has_sample == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if($productionplanning->has_mi == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if($productionplanning->has_cart == 'Yes')
                                             <td class="text-center">
                                                <a class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        <td>{{ $productionplanning->fab_date }}</td>
                                        <td>{{ $productionplanning->acc_date }}</td>
                                        <td>{{ $productionplanning->bordir_approve }}</td>
                                        <td>{{ $productionplanning->pattern_date }}</td>
                                        <td>{{ $productionplanning->sampletest_date }}</td>
                                        <td>{{ $productionplanning->marker_date }}</td>
                                        <td>{{ $productionplanning->pilotrun_date }}</td>
                                        <td>{{ $productionplanning->ppm_date }}</td>
                                        <td>{{ $productionplanning->startcut_date }}</td>
                                        <td>{{ $productionplanning->finishcut_date }}</td>
                                        <td>{{ $productionplanning->startsew_date }}</td>
                                        <td>{{ $productionplanning->finishsew_date }}</td>
                                        <td>{{ $productionplanning->finishpack_date }}</td>
                                        <td>{{ $productionplanning->remark }}</td>
                                        <td class="text-center">
                                            <a href="/productionplanning/find/{{ $productionplanning->id }}" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn btn-danger btn-circle btn-sm btn-delete-record" data-delete-link="delete/{{ $productionplanning->id }}" data-delete-name="{{ $productionplanning->pobuyer_no }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="delete-title" class="modal-title" id="exampleModalLabel">Delete Record</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body"><p id="modal-text-record"></p></div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                        <a id="btn-confirm" href=""><button class="btn btn-primary" type="button">Confirm</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Import Production Planning</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <form action="{{ route('productionplanning.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>PILIH FILE</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>


@include('layout.footer')
</body>
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script>
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus Production Planning ' + $(this).data('delete-name') + '?');
    });
</script>
</html>