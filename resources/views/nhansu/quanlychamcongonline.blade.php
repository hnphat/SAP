@extends('admin.index')
@section('title')
    Quản lý Chấm công Online
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
                        <h1 class="m-0"><strong>Quản lý Chấm công Online</strong></h1>
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
                                            Quản lý Chấm công Online
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
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <button id="xoaAnh" type="button" class="btn btn-warning btn-xs">GIẢI PHÓNG ẢNH ĐỆM</button>
                                                </div>
                                            </div>
                                        </div>                                                                                                                
                                        <h5>Tổng ảnh đệm: <span id="tongAnhDem" class="text-danger"></span></h5>
                                        <table id="dataTable" class="display" style="width:100%">
                                            <thead>
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Mã NV</th>
                                                <th>Họ tên</th>    
                                                <th>Ngày chấm công</th>                                                
                                                <th>Buổi chấm công</th>
                                                <th>Loại chấm công</th>
                                                <th>Thời gian</th>
                                                <th>Hình ảnh</th>
                                                <th>Ghi chú</th>
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
                ajax: "{{ url('management/nhansu/chamcongonline/getlist') }}" + '?from=' + from + "&to=" + to,
                "order": [
                    [ 1, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "manv" },
                    { "data": "hoten" },
                    { "data": "ngaychamcong" },
                    {
                        "data": null,
                        render: function(data, type, row) {  
                            let buoichamcong = parseInt(row.buoichamcong);                          
                            switch(buoichamcong) {
                                case 1:
                                    return "<strong>Sáng</strong>";
                                case 2:
                                    return "<strong>Chiều</strong>";
                                case 3:
                                    return "<strong>Tối</strong>";
                                default:
                                    return "Khác";
                            }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) { 
                            let loaichamcong = parseInt(row.loaichamcong);                             
                            switch(loaichamcong) {
                                case 1:
                                    return "<strong class='text-success'>Vào</strong>";
                                case 2:
                                    return "<strong class='text-pink'>Ra</strong>";
                                default:
                                    return "<strong>Khác</strong>";
                            }
                        }
                    },
                    { "data": "thoigianchamcong" },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                          return "<img src='{{asset('upload/chamcongonline/')}}/"+row.hinhanh+"' alt='Ảnh đã xóa' style='width: 120px; max-width:120px;'/>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                          
                            if (row.isXoa == 1) {
                                return "<strong class='text-danger'>Đã xóa ảnh đệm</strong>";
                            } else {
                                return "<strong class='text-secondary'>Chưa xóa ảnh đệm</strong>";
                            }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                            return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
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
                let urlpathcurrent = "{{ url('management/nhansu/chamcongonline/getlist') }}";
                table.ajax.url( urlpathcurrent + '?from=' + from + "&to=" + to).load();
            });

            function autoLoadCounter() {
                // Gọi AJAX load lịch sử chấm công hôm nay
                $.ajax({
                    url: "{{url('management/nhansu/chamcongonline/loadsoluonganhdem/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        if (response.code === 200) {
                            $("#tongAnhDem").text(response.counterAnhDem);
                        } else {
                            Toast.fire({ icon: 'error', title: "Lỗi tải!" });
                        }
                    },
                    error: function() {
                        Toast.fire({ icon: 'error', title: "Không thể tải!" });
                    }
                });
            }
            autoLoadCounter();

             //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chamcongonline/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            autoLoadCounter();
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });
            // Xóa ảnh đệm
            $(document).one('click','#xoaAnh',function(e){
                if (confirm('Bạn có chắc muốn giải phóng ảnh đệm không?')) {
                    let from = $("input[name=chonNgayOne]").val();
                    let to = $("input[name=chonNgayTwo]").val();    
                    $.ajax({
                        url: "{{url('management/nhansu/chamcongonline/giaiphonganhdem/')}}" + '?from=' + from + "&to=" + to,
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}"
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            autoLoadCounter();
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }                     
            });
        });
        
    </script>
@endsection
