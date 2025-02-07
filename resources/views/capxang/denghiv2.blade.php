
@extends('admin.index')
@section('title')
    Đề nghị cấp xăng
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
                        <h1 class="m-0"><strong>Đề nghị nhiên liệu v2</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Đề nghị nhiên liệu</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            @if(session('succ'))
                <div class="alert alert-success">
                    {{session('succ')}}
                </div>
            @endif
            @if(session('err'))
                <div class="alert alert-warning">
                    {{session('err')}}
                </div>
            @endif
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đề nghị nhiên liệu</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                        <!-- Medal Add -->
                        <div class="modal fade" id="addModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- general form elements -->
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">ĐỀ NGHỊ CẤP NHIÊN LIỆU</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="addForm" method="post" enctype="multipart/form-data" autocomplete="off">
                                                    <!-- {{csrf_field()}} -->
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-sm-5">
                                                                <label>Cấp cho xe:</label>                                                        
                                                                <select name="capChoXe" id="capChoXe" class="form-control">
                                                                    <option value="0" disabled selected>Chọn</option>
                                                                    <option value="1">Xe theo hợp đồng</option>
                                                                    <option value="2">Xe lưu kho</option>
                                                                    <option value="3">Xe lái thử/cứu hộ</option>
                                                                    <option value="4">Xe khác</option>
                                                                </select>                                                            
                                                            </div>                                                            
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label for="chonCapChoXe" id="tieuDeChonCapChoXe">Cấp cho xe:</label>                                                        
                                                                <select name="chonCapChoXe" id="chonCapChoXe" class="form-control">
                                                                    
                                                                </select>                                                            
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Thông tin xe:</label>                                                        
                                                                <input readonly type="text" name="loaiXe" class="form-control" required="required"/>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Biển số/số khung: </label>                                                        
                                                                <input readonly type="text" name="bienSo" class="form-control" required="required"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Thông tin khách hàng (nếu có): </label>                                                        
                                                                <input readonly type="text" name="khachHang" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Hình ảnh taplo xe (taplo phải hiển thị số km hiện tại, số km xăng hiện tại (kim chỉ xăng)):</label>                                                        
                                                                <input type="file" name="taplo" class="form-control">                                                        
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Loại nhiên liệu:</label>                                                        
                                                                <select name="loaiNhienLieu" class="form-control">
                                                                    <option value="X">Xăng</option>
                                                                    <option value="D">Dầu</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Số lít đề nghị (lưu ý theo quy định công ty):</label>                                                        
                                                                <input placeholder="Số lít" type="number" min="0" name="soLit" required="required" class="form-control"/>
                                                            </div>                                                          
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Lý do cấp:</label>                                                        
                                                                <input placeholder="Phải ghi chi tiết lý do cần cấp nhiên liệu" type="text" name="ghiChu" class="form-control">
                                                            </div>
                                                            <div class="form-group col-sm-12">
                                                                <select name="leadCheck" class="form-control">
                                                                    <option value="">Chọn người duyệt</option>
                                                                    @foreach($lead as $row)
                                                                        @if($row->hasRole('lead'))
                                                                            <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <div class="card-footer">
                                                        <button id="btnAdd" type="button" class="btn btn-primary">GỬI</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày</th>
                                    <th>Đề nghị</th>
                                    <th>Xe - Biển số</th>
                                    <th>Nhiên liệu</th>
                                    <th>Số lít</th>
                                    <th>Khách hàng</th>
                                    <th>Hạng mục</th>
                                    <th>Lý do cấp</th>
                                    <th>Trưởng bộ phận</th>
                                    <th>Hành chính</th>
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
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/capxang/loaddenghinhienlieu/') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "ngay" },
                    { "data": "username"},
                    { "data": "xe_bienso"},
                    { "data": "nhienlieu"},
                    { "data": "solit" },
                    { "data": "khachhang"},
                    { "data": "lydo" },
                    { "data": "ghichu" },
                    { "data": "nguoiduyet"},
                    { "data": "hanhchinh"},
                    { "data": "action"}      
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            $("#btnAdd").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#addForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{route('capxang.post')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Thao tác client có vấn đề'
                            })
                            $("#addModal").modal('hide');
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });
            });
        });

        // -- event realtime
        let es = new EventSource("{{route('action.reg')}}");
        es.onmessage = function(e) {
            console.log(e.data);
            let fullData = JSON.parse(e.data);
            if (fullData.flag == true) {
               open('{{route('capxang.denghi')}}','_self');
            }
        }
        // -- event realtime

        //Delete data
        $(document).on('click','#del', function() {
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/capxang/del/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            setTimeout(function(){
                                open('{{route('capxang.denghi')}}','_self');
                            }, 1000);
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
        $(document).on('change','#capChoXe', function() {
            let giaTri = parseInt($("#capChoXe").val());          
            switch (giaTri) {
                case 1: {
                    $("#tieuDeChonCapChoXe").text("Chọn hợp đồng:");
                    $.ajax({
                        url: "{{route('capxang.getxehopdong')}}",
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
                            $("#chonCapChoXe").empty();
                            let data = response.data;
                            let txt = `<option value="0" disabled selected>Chọn</option>`;
                            for (let i = 0; i < data.length; i++) {
                                const ele = data[i];
                                txt += `<option value="${ele.id}">${ele.thongTinKhachHang}</option>`;
                            }
                            $("#chonCapChoXe").html(txt);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi!"
                            })
                        }
                    });
                } break;
                case 2: {

                } break;
                case 3: {

                } break;
                case 4: {

                } break;
                default: break;
            }            
        });

        $(document).on('change','#chonCapChoXe', function() {
            let giaTri = $("#chonCapChoXe").val();
            $.ajax({
                url: "{{route('capxang.getxehopdongchitiet')}}",
                type: "post",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "id": giaTri
                },
                success: function(response) {
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })
                    $("input[name=loaiXe]").val(response.thongtinxe);
                    $("input[name=bienSo]").val(response.sokhung);
                    $("input[name=khachHang]").val(response.thongtinkhachhang);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Lỗi!"
                    })
                }
            });
        });
    </script>
@endsection
