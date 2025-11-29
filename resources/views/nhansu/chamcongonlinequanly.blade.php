@extends('admin.index')
@section('title')
    Tổng quan Chấm công Online
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Tổng quan Chấm công Online</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý chấm công -> Quản lý Chấm công Online</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                    <div>
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">
                                            Tổng quan Chấm công Online
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <div class="row">
                                             <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Từ</label>
                                                    <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Đến</label>
                                                    <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <a href="{{route('quanly.chamcong.online.chitiet')}}" target="_blank" class="btn btn-success">CHI TIẾT CHẤM CÔNG</a>
                                                </div>
                                            </div>
                                            
                                        </div>                                                                                                                
                                        <table id="dataTable" class="display" style="width:100%">
                                            <thead>
                                                <tr class="bg-cyan">
                                                    <th>TT</th>
                                                    <th>Mã NV</th>
                                                    <th>Họ tên</th>    
                                                    <th>Ngày chấm công</th>  
                                                    <th>Vào Sáng</th>                                              
                                                    <th>Ra Sáng</th>
                                                    <th>Vào Chiều</th>
                                                    <th>Ra Chiều</th>
                                                    <th>Vào Tối</th>
                                                    <th>Ra Tối</th>
                                                    <th>Công sáng</th>
                                                    <th>Công chiều</th>
                                                    <th>Trể sáng</th>
                                                    <th>Trể chiều</th>
                                                    <th>Trạng thái</th>
                                                    <th>Tác vụ</th>                                    
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>                                    
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
            </div>
        </div>
        <!-- /.content -->
    </div>      
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Below is plugin for datatables -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,           // tăng lên 5000 ms = 5s
            timerProgressBar: true // (tùy chọn) hiển thị thanh tiến trình
        });

        // show data
        $(document).ready(function() {
            let from = $("input[name=chonNgayOne]").val();
            let to = $("input[name=chonNgayTwo]").val(); 
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/nhansu/chamcongonline/getlisttongquan') }}" + '?from=' + from + "&to=" + to,
                "order": [
                    [ 1, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "manv" },
                    { "data": "hoten" },
                    { "data": "ngaychamcong" },
                    { "data": "vaoSang" },
                    { "data": "raSang" },
                    { "data": "vaoChieu" },
                    { "data": "raChieu" },
                    { "data": "vaoToi" },
                    { "data": "raToi" },
                    { "data": "caSang" },
                    { "data": "caChieu" },
                    { "data": "treSang" },
                    { "data": "treChieu" },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                            return "Trang thai";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                            return "Tac vu";
                        }
                    }
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();
          
            $("#xemReport").click(function(){
                let from = $("input[name=chonNgayOne]").val();
                let to = $("input[name=chonNgayTwo]").val();               
                let urlpathcurrent = "{{ url('management/nhansu/chamcongonline/getlisttongquan') }}";
                table.ajax.url( urlpathcurrent + '?from=' + from + "&to=" + to).load();
            });            
        });
        
    </script>
@endsection
