@extends('admin.index')
@section('title')
    Danh sách hạng mục
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
      input[type="checkbox"] {
            width: 30px;
            height: 30px;
      }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Danh mục phụ kiện (Mở rộng)</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Danh mục phụ kiện (Mở rộng)</li>
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
                                            Quản lý hạng mục (chỉ xem)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <table id="dataTable" class="display" style="width:100%">
                                            <thead>
                                            <tr class="bg-cyan">
                                                <th>TT</th>
                                                <th>Dòng xe</th>    
                                                <th>Loại</th>                                                
                                                <th>Mã</th>
                                                <th>Nội dung</th>
                                                <th>Đơn vị tính</th>
                                                <th>Giá vốn</th>
                                                <th>Giá bán</th>
                                                <th>Giá tặng</th>
                                                <th>Công KTV</th>   
                                                <th>Thời gian thực hiện</th>  
                                                <th>Bảo hành</th>  
                                                <th>Nhà cung cấp</th>    
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
            timer: 3000
        });

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/dichvu/hangmucmorong/get/list') }}",
                // "columnDefs": [ {
                //     "searchable": false,
                //     "orderable": false,
                //     "targets": 0
                // } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "namecar" },
                    { "data": "loai" },
                    { "data": "ma" },
                    { "data": "noiDung" },
                    { "data": "dvt" },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.giaVon);
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.donGia);
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.giaTang);
                        } 
                    },
                    { 
                        "data": null,
                        render: function(data, type, row) {
                          return formatNumber(row.congKTV);
                        } 
                    },             
                    { "data": "thoigian" },
                    { "data": "baohanh" },
                    { "data": "nhacungcap" }
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();
          
            $('#donGia').keyup(function(){
                var cos = $(this).val();
                $('#showGia').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaVon').keyup(function(){
                var cos = $(this).val();
                $('#showGiaVon').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#giaTang').keyup(function(){
                var cos = $(this).val();
                $('#showGiaTang').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#congKTV').keyup(function(){
                var cos = $(this).val();
                $('#showCongKTV').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#edonGia').keyup(function(){
                var cos = $(this).val();
                $('#eshowGia').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#egiaVon').keyup(function(){
                var cos = $(this).val();
                $('#eshowGiaVon').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#egiaTang').keyup(function(){
                var cos = $(this).val();
                $('#eshowGiaTang').text("(" + DOCSO.doc(cos) + ")");
            });

            $('#econgKTV').keyup(function(){
                var cos = $(this).val();
                $('#eshowCongKTV').text("(" + DOCSO.doc(cos) + ")");
            });

            // Add data
            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/dichvu/hangmuc/guest/add/')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#addModal").modal('hide');
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                        })
                    }
                });
            });

            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/dichvu/hangmuc/guest/delete/')}}",
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

            //Off data
            $(document).on('click','#khoa', function(){
                let idKhoa = $(this).data('id');
                if(confirm('Xác nhận ẩn/khóa?')) {
                    $.ajax({
                        url: "{{url('management/dichvu/hangmuc/khoa/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title:  response.message,
                            })
                            if (response.code == 200) {
                                console.log(response.isShow);
                                if (response.isShow == true) {
                                    $("#khoa[data-id='"+idKhoa+"']").text("off");
                                    $("#khoa[data-id='"+idKhoa+"']").removeClass("btn btn-success btn-sm").addClass("btn btn-warning btn-sm");

                                } else {
                                    $("#khoa[data-id='"+idKhoa+"']").text("on");
                                    $("#khoa[data-id='"+idKhoa+"']").removeClass("btn btn-warning btn-sm").addClass("btn btn-success btn-sm");
                                }
                            } 
                            // table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể ẩn/khóa lúc này!"
                            })
                        }
                    });
                }
            });

            // Map All
             $(document).on('click','#dupMoreBtn', function(){
                let idHangMuc = $('input[name="idHangMuc"]').val();
                let typeCarArr = [];
                $('input[name="typeCar[]"]:checked').each(function(){
                    typeCarArr.push($(this).val());
                });
                if(typeCarArr.length === 0) {
                    Toast.fire({
                        icon: 'warning',
                        title: "Vui lòng chọn ít nhất một dòng xe!"
                    });
                    return;
                }
                if(confirm('Xác nhận nhân bản danh mục này đến các dòng xe đã chọn?')) {
                    $.ajax({
                        url: "{{url('management/dichvu/hangmuc/mapall/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": idHangMuc,
                            "typeCar": typeCarArr
                        },
                        success: function(response) {
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: response.type,
                                    title:  response.message,
                                });
                                $("#dupMoreModal").modal('hide');
                                table.ajax.reload();
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: response.message
                                });
                            }
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể Map All!"
                            });
                        }
                    });
                }
            });
            // $(document).on('click','#dup', function(){
            //     let idKhoa = $(this).data('id');
            //     if(confirm('Xác nhận nhân bản danh mục này đến tất cả các dòng xe?')) {
            //         $.ajax({
            //             url: "{{url('management/dichvu/hangmuc/mapall/')}}",
            //             type: "post",
            //             dataType: "json",
            //             data: {
            //                 "_token": "{{csrf_token()}}",
            //                 "id": $(this).data('id')
            //             },
            //             success: function(response) {
            //                 Toast.fire({
            //                     icon: response.type,
            //                     title:  response.message,
            //                 })
            //                 table.ajax.reload();
            //             },
            //             error: function() {
            //                 Toast.fire({
            //                     icon: 'warning',
            //                     title: "Không thể Map All!"
            //                 })
            //             }
            //         });
            //     }
            // });

            // edit data
            $(document).on('click','#btnEdit', function(){
                $.ajax({
                    url: "{{url('management/dichvu/hangmuc/guest/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        // $("select[name=eisPK]").val(response.data.isPK);
                        $("input[name=ema]").val(response.data.ma);
                        $("input[name=enoiDung]").val(response.data.noiDung);
                        $("select[name=edvt]").val(response.data.dvt);
                        $("input[name=edonGia]").val(response.data.donGia);
                        $("input[name=egiaVon]").val(response.data.giaVon);
                        $("input[name=egiaTang]").val(response.data.giaTang);
                        $("input[name=econgKTV]").val(response.data.congKTV);
                        $("input[name=ethoigian]").val(response.data.thoigian);
                        $("input[name=ebaohanh]").val(response.data.baohanh);
                        $("input[name=enhacungcap]").val(response.data.nhacungcap);
                        $("select[name=eloai]").val(response.data.loai);
                        $("select[name=etypeCar]").val(response.data.loaiXe);
                        $('#eshowGia').text("(" + DOCSO.doc(response.data.donGia) + ")");
                        $('#eshowGiaVon').text("(" + DOCSO.doc(response.data.giaVon) + ")");
                        $('#eshowGiaTang').text("(" + DOCSO.doc(response.data.giaTang) + ")");
                        $('#eshowCongKTV').text("(" + DOCSO.doc(response.data.congKTV) + ")");
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/dichvu/hangmuc/guest/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#editForm").serialize(),
                    success: function(response) {
                        $("#editForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#editModal").modal('hide');
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
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
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        });
                        $("#btnImport").attr('disabled', false).html("IMPORT");                      
                        $("#importModal").modal('hide');
                        setTimeout(() => {
                            open("{{route('dichvu.hangmuc')}}","_self");
                        }, 3000);
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

        $(document).one('click','#dupMore',function(e){
            let idHangMuc = $(this).data('id');
            $('input[name="idHangMuc"]').val(idHangMuc);      
        });

        // $("input[name='checkAll']").click(function(){
        //     var checked = $(this).prop('checked');
        //     $("input[name='typeCar[]']").prop('checked', checked);
        // });
        $(document).on('change', 'input[name="checkAll"]', function() {
            var checked = $(this).prop('checked');
            $('input[name="typeCar[]"]').prop('checked', checked);
        });

        $("#danhMucMoRong").click(function(){
            open("{{route('dichvu.hangmucmorong')}}","_self");
        });
    </script>
@endsection
