<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">
@include('layout.sidebar')
                        
@php
$ip="192.168.1.213";
$key="0";
@endphp

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            @include('layout.navbar')
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Download Data Finger</h1>
                </div>

                <form action="{{ route('fingerprint.tarik-data') }}">
                    <div>
                        <label>IP :</label>
                        <input class="form-control" type="text" id="ip" name="ip" value="{{$ip}}" readonly>
                    </div>
                    <br>
                    <div>
                        <label>Comm Key :</label>
                        <input class="form-control" type="text" id="ip" name="ip" value="{{$key}}" readonly>
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block" value="Download">Download</button>
                    </div>
                </form>
                <br><br>
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Finger</h6>
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
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Verifikasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $Connect = fsockopen($ip, "80", $errno, $errstr, 1);
                                    if($Connect){
                                        $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
                                        $newLine="\r\n";
                                        fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
                                        fputs($Connect, "Content-Type: text/xml".$newLine);
                                        fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
                                        fputs($Connect, $soap_request.$newLine);
                                        $buffer="";
                                        while($Response=fgets($Connect, 1024)){
                                            $buffer=$buffer.$Response;
                                        }
                                    }else echo "Koneksi Gagal";
                                    
                                    include("parse.php");
                                    $buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
                                    $buffer=explode("\r\n",$buffer);
                                    for($a=0;$a<count($buffer);$a++){
                                        $data=Parse_Data($buffer[$a],"<Row>","</Row>");
                                        $PIN=Parse_Data($data,"<PIN>","</PIN>");
                                        $DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
                                        $Verified=Parse_Data($data,"<Verified>","</Verified>");
                                        $Status=Parse_Data($data,"<Status>","</Status>");
                                    @endphp
                                    <tr>
                                        <td>{{ $PIN }}</td>
                                        <td>{{ $DateTime }}</td>
                                        <td>{{ $Verified }}</td>
                                        <td>{{ $Status }}</td>
                                    </tr>
                                    @php
                                        }
                                    @endphp
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


@include('layout.footer')
</body>
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script>
    $('#dataTable').DataTable({
    "order": [[ 2, "asc" ]] 
});
</script>
<script>
    $('.btn-show-detail').on('click', function () {
        $('#pdf-src').attr('src', '/slip-gaji/' + $(this).data('show-link'));
        $("#detail-title").text($(this).data('show-title'));
    });
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus karyawan ' + $(this).data('delete-name') + '?');
    });
</script>
</html>