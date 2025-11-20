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
                                        </div>
                                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button> 
                                        <button id="import" class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import Excel</button>&nbsp;
                                        <button id="hiddenAll" class="btn btn-warning" data-toggle="modal" data-target="#hiddenModal">Ẩn/Hiện Danh Mục</button>
                                        &nbsp;
                                        <button id="raSoat" class="btn btn-secondary">Rà soát chênh lệch giá, công KTV</button>
                                        <!-- &nbsp;
                                        <button id="danhMucMoRong" class="btn btn-info">Danh mục phụ kiện (mở rộng)</button> -->
                                        <br/><br/>
                                        <p><strong id="loiImport" class="text-danger"></strong></p>                                                                                                                      

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
                            switch(row.buoichamcong) {
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
                            switch(row.loaichamcong) {
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
                    { "data": "hinhanh" },
                    { "data": "ghichu" },
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
          
            // Add data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/dichvu/hangmuc/guest/add/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        if (response.code == 200) {                            
                            $("#addForm")[0].reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#addModal").modal('hide');
                        } else {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                        })
                    }
                });
            });

            $("#xemReport").click(function(){
                let from = $("input[name=chonNgayOne]").val();
                let to = $("input[name=chonNgayTwo]").val();               
                let urlpathcurrent = "{{ url('management/nhansu/chamcongonline/getlist') }}";
                table.ajax.url( urlpathcurrent + '?from=' + from + "&to=" + to).load();
            });

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

        //upload
        $(document).one('click','#btnImport',function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#importForm').submit(function(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/dichvu/hangmuc/ajax/importfile/')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btnImport").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => {
                        this.reset();
                        if (response.code == 200) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            $("#btnImport").attr('disabled', false).html("IMPORT");                      
                            $("#importModal").modal('hide');
                            if (response.loi && Array.isArray(response.loi)) {
                                let loiHtml = "<b>Import đã bỏ qua các mã này do xảy ra lỗi:</b><br>";
                                response.loi.forEach(function(item, idx) {
                                    loiHtml += (idx+1) + ". " + item + "<br>";
                                });
                                $("#loiImport").html(loiHtml);
                            } else if (typeof response.loi === 'object') {
                                let loiHtml = "<b>Import đã bỏ qua các mã này do xảy ra lỗi:</b><br>";
                                Object.values(response.loi).forEach(function(item, idx) {
                                    loiHtml += (idx+1) + ". " + item + "<br>";
                                });
                                $("#loiImport").html(loiHtml);
                            } else {
                                $("#loiImport").text("Import đã bỏ qua các mã này do xảy ra lỗi: " + response.loi);
                            }
                            table.ajax.reload();
                            // setTimeout(() => {
                            //     open("{{route('dichvu.hangmuc')}}","_self");
                            // }, 3000);
                        } else {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });                            
                        }                        
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Có lỗi: ' + response.responseJSON.errors.fileBase
                        })
                        $("#btnImport").attr('disabled', false).html("IMPORT");  
                        console.log(response);
                    }
                });
            });                
        });

        // hidden
        $(document).one('click','#btnHidden',function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#hiddenForm').submit(function(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/dichvu/hangmuc/ajax/hiddendanhmuc/')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btnHidden").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => {
                        this.reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message + " - Đã cập nhật: " + response.soluong
                        });
                        $("#btnHidden").attr('disabled', false).html("THỰC HIỆN");                      
                        $("#hiddenModal").modal('hide');
                        setTimeout(() => {
                            open("{{route('dichvu.hangmuc')}}","_self");
                        }, 3000);
                    },
                    error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: 'Có lỗi'
                        })
                        $("#btnHidden").attr('disabled', false).html("Thực hiện");  
                        console.log(response);
                    }
                });
            });                
        });
        
    </script>
@endsection
