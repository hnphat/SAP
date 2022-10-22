
@extends('admin.index')
@section('title')
   Cấu hình
@endsection
@section('script_head')
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
                        <h1 class="m-0"><strong>Cấu hình</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Cấu hình</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">          
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">
                                Cấu hình
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                        <form id="formData" method="post">
                            @csrf
                            <div class="row container">
                                <div class="col-md-4"> 
                                    <div class="form-group">
                                        <label>Hình nền</label>
                                        <input placeholder="VD: https://abc.com/anh.jpg" name="hinhNen" class="form-control" type="text" />
                                    </div>
                                    <div class="form-group">
                                        <label>Email báo duyệt phép TBP</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailPhep" class="form-control" type="text"/>
                                    </div> 
                                    <div class="form-group">
                                        <label>Email yêu cầu duyệt nhiên liệu</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailNhienLieu" class="form-control" type="text"/>
                                    </div>  
                                    <div class="form-group">
                                        <label>Email yêu cầu duyệt công cụ dụng cụ</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailCCDC" class="form-control" type="text"/>
                                    </div>   
                                    <div class="form-group">
                                        <label>Email yêu cầu duyệt cấp hoa</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailCapHoa" class="form-control" type="text"/>
                                    </div>    
                                    <div class="form-group">
                                        <label>Email yêu cầu duyệt sử dụng xe</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailDuyetXe" class="form-control" type="text"/>
                                    </div>  
                                    <div class="form-group">
                                        <label>Email yêu cầu duyệt trả xe</label>
                                        <input placeholder="VD: admin@gmail.com" name="emailTraXe" class="form-control" type="text"/>
                                    </div>                                                
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Loại thông báo</label>
                                        <select name="loaiThongBao" id="loaiThongBao" class="form-control">
                                            <option value="1">Treo gốc</option>
                                            <option value="0">Cửa sổ bật lên</option>                                            
                                        </select>
                                    </div>   
                                    <div class="form-group">
                                        <label>Nội dung thông báo</label>
                                        <textarea name="thongBao" class="form-control" name="thongBao" cols="30" rows="5">
                                        </textarea>
                                    </div>   
                                    <div class="form-group">
                                        <label>Màu thông báo</label>
                                        <select name="mauThongBao" id="mauThongBao" class="form-control">
                                            <option value="bg-mute">Không màu</option>
                                            <option value="bg-success">Xanh lá cây</option>
                                            <option value="bg-danger">Đỏ</option>
                                            <option value="bg-info">Xanh nước biển</option>
                                            <option value="bg-primary">Xanh đậm</option>
                                            <option value="bg-warning">Cam</option>
                                            <option value="bg-secondary">Bạc</option>                                            
                                        </select>
                                    </div>   
                                    <div class="form-group">
                                        <label>Cho phép nhân viên cập nhật thông tin cá nhân</label>
                                        <select name="capNhatThongTin" class="form-control">
                                            <option value="1">Không</option>
                                            <option value="0">Có</option>
                                        </select>
                                    </div>   
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dữ liệu tối đa trả về <br/>(Xin phép, Nhật ký)</label>
                                        <input type="number" name="maxRecord" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <button id="saveConfig" class="btn btn-info">LƯU CẤU HÌNH</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
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

        $(document).ready(function(){
            function onLoadData(){
                $.ajax({
                    url: "{{url('management/cauhinh/ajax/get/')}}",
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })                  
                        $("input[name=hinhNen]").val(response.data.hinhNen);  
                        $("textarea[name=thongBao]").val(response.data.thongBao);
                        $("input[name=emailPhep]").val(response.data.emailPhep);
                        $("input[name=emailNhienLieu]").val(response.data.emailNhienLieu);
                        $("input[name=emailCCDC]").val(response.data.emailCCDC);
                        $("input[name=emailCapHoa]").val(response.data.emailCapHoa);
                        $("input[name=emailDuyetXe]").val(response.data.emailDuyetXe);
                        $("input[name=emailTraXe]").val(response.data.emailTraXe);
                        $("select[name=capNhatThongTin]").val(response.data.capNhatThongTin);
                        $("select[name=mauThongBao]").val(response.data.mauThongBao);
                        $("select[name=loaiThongBao]").val(response.data.loaiThongBao);
                        $("input[name=maxRecord]").val(response.data.maxRecord);

                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể tải cấu hình!"
                        })
                    }
                });
            }
            onLoadData();

            $("#saveConfig").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cauhinh/ajax/saveconfig/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#formData").serialize(),
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })   
                        onLoadData();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể lưu cấu hình!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
