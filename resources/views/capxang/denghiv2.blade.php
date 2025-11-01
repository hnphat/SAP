
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
                                                <form id="addForm" method="post" autocomplete="off">
                                                    <!-- {{csrf_field()}} -->
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-sm-5">
                                                                <label>Cấp cho xe:</label>                                                        
                                                                <select name="capChoXe" id="capChoXe" class="form-control">
                                                                    <option value="0" disabled selected>Chọn</option>
                                                                    <option value="1">Xe mới (lưu kho/showroom)</option>
                                                                    <option value="2">Xe lái thử</option>
                                                                    <option value="3">Xe cứu hộ</option>
                                                                    <option value="4">Xe dịch vụ (dùng sửa chữa)</option>
                                                                    <option value="5">Xe bảo dưỡng lưu động</option>
                                                                    <option value="6">Xe công tác (mua hàng, ngân hàng, vận chuyển, khảo sát)</option>
                                                                    <option value="7">Xe đi thị trường</option>   
                                                                    <option value="8">Xe sự kiện</option>   
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
                                                                <input type="text" name="loaiXe" class="form-control" required="required"/>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Biển số/số khung: </label>                                                        
                                                                <input type="text" name="bienSo" class="form-control" required="required"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Thông tin khách hàng (nếu có): </label>                                                        
                                                                <input type="text" name="khachHang" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Hình ảnh taplo xe (taplo phải hiển thị số km hiện tại, số km xăng hiện tại (kim chỉ xăng)):</label>                                                        
                                                                <input type="file" name="taplo" class="form-control">                                                        
                                                            </div>
                                                        </div> -->
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Loại nhiên liệu:</label>                                                        
                                                                <select name="loaiNhienLieu" class="form-control">
                                                                    <option value="X">Xăng</option>
                                                                    <option value="D">Dầu</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Số lít đề nghị <strong class="text-danger">(*)</strong>:</label>                                                        
                                                                <input placeholder="Số lít" type="number" min="0" name="soLit" required="required" class="form-control"/>
                                                            </div>                                                          
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Lý do cấp <strong class="text-danger">(*)</strong>:</label>                                                        
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
                                                        <input id="btnAdd" type="submit" value="GỬI" class="btn btn-primary">
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
                                    <th>Xe</th>
                                    <th>Biển số/Số khung</th>
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
            function setHidden() {
                $("input[name=loaiXe]").prop('readonly', true);
                $("input[name=bienSo]").prop('readonly', true);
                // $("input[name=khachHang]").prop('readonly', true);
            }

            function unSetHidden(){
                $("input[name=loaiXe]").prop('readonly', false);
                $("input[name=bienSo]").prop('readonly', false);
                // $("input[name=khachHang]").prop('readonly', false);
            }

            setHidden();


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
                    { "data": "xe"},
                    { "data": "bienso"},
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

            $('#btnAdd').off('click').on('click', function(e){
                e.preventDefault();                
                if (!$("input[name=loaiXe]").val() || !$("input[name=bienSo]").val()) {
                    Toast.fire({ 
                        icon: 'error', 
                        title: 'Vui lòng nhập tên xe, số khung/biển số đầy đủ!' 
                    });
                    return;
                }

                let giaTri = parseInt($("#capChoXe").val());
                if (giaTri == 4 && !$("input[name=khachHang]").val()) {
                    Toast.fire({ 
                        icon: 'error', 
                        title: 'Vui lòng nhập thông tin khách hàng cho xe dịch vụ!' 
                    });
                    return;
                }

                if ($("input[name=soLit]").val() <= 0 || !$("input[name=ghiChu]").val()) {
                    Toast.fire({ 
                        icon: 'error', 
                        title: 'Vui lòng nhập số lít hoặc lý do cấp đầy đủ!' 
                    });
                    return;
                }

                $('#addForm').trigger('submit');
            });

           $('#addForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(form);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{route('capxang.post')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btnAdd").prop('disabled', true).text("Đang xử lý....");
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            form.reset();
                            Toast.fire({ icon: 'info', title: response.message });
                            table.ajax.reload();
                            $("#addModal").modal('hide');
                        } else {
                            Toast.fire({ icon: 'warning', title: response.message || 'Lỗi' });
                        }
                        $("#btnAdd").prop('disabled', false).text("GỬI");
                    },
                    error: function(response){
                        Toast.fire({ icon: 'info', title: 'Vui lòng nhập đầy đủ thông tin như yêu cầu' });
                        // $("#addModal").modal('hide');
                        $("#btnAdd").prop('disabled', false).text("GỬI");
                        console.log(response);
                    }
                });
            });
                
            $(document).on('change','#chonCapChoXe', function() {
                var giaTriRaw = $("#chonCapChoXe").val() || "";
                var giaTri = giaTriRaw.split(';').map(function(s){ return s.trim(); }).filter(function(s){ return s.length > 0; });
                var loaiXe = giaTri.length > 0 ? giaTri[0] : "";
                var bienSo = giaTri.length > 1 ? giaTri[1] : "";
                $("input[name=loaiXe]").val(loaiXe);
                $("input[name=bienSo]").val(bienSo);
            });

            $(document).on('change','#capChoXe', function() {
                let giaTri = parseInt($("#capChoXe").val());          
                switch (giaTri) {
                    case 1: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxeluukho')}}",
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
                                let data = response.data || [];

                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });

                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    if (ele.soKhung.length >= 12)
                                        txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithu')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    case 3: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithu')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    case 4: {
                        unSetHidden();
                        $("input[name=loaiXe]").val("");
                        $("input[name=bienSo]").val("");
                        $("input[name=khachHang]").val("");
                        $("#chonCapChoXe").empty();                        
                    } break;
                    case 5: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithu')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    case 6: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithumore')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    case 7: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithu')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    case 8: {
                        setHidden();
                        $("#tieuDeChonCapChoXe").text("Chọn xe:");
                        $.ajax({
                            url: "{{route('capxang.getxelaithu')}}",
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
                                let data = response.data || [];
                                // Sắp xếp theo tenXe tăng dần (hỗ trợ tiếng Việt)
                                data.sort(function(a, b){
                                    return ( (a.tenXe||'').toString().localeCompare((b.tenXe||'').toString(), 'vi', { sensitivity: 'base' }) );
                                });
                                let txt = `<option value="0" disabled selected>Chọn</option>`;
                                for (let i = 0; i < data.length; i++) {
                                    const ele = data[i];
                                    txt += `<option value="${ele.tenXe}; ${ele.soKhung}">${ele.tenXe}; ${ele.soKhung}</option>`;
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
                    default: break;
                }            
            });

            //Delete data
            $(document).on('click','#del', function(e) {
                    e.preventDefault();
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
                                if (response.code == 200) {
                                    Toast.fire({
                                        icon: 'info',
                                        title: response.message
                                    })
                                    table.ajax.reload();
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: response.message
                                    })
                                }
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
            
            // -- event realtime
            let es = new EventSource("{{route('action.reg')}}");
            es.onmessage = function(e) {
                console.log(e.data);
                let fullData = JSON.parse(e.data);
                if (fullData.flag == true) {
                open('{{route('capxang.denghi')}}','_self');
                }
            }
        });           
    </script>
@endsection
