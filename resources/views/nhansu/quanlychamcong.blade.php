@extends('admin.index')
@section('title')
    Quản lý chấm công
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
                        <h1 class="m-0"><strong>Quản lý chấm công</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý chấm công</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="container row">
                    <a href="{{route('quanly.chamcong.online')}}" class="btn btn-xs btn-primary">Quản lý Chấm công Online</a>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label>Ngày</label>
                        <select name="ngay" class="form-control">
                            @for($i = 1; $i <= 31; $i++)
                                <option value="{{$i}}" <?php if(Date('d') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Tháng</label>
                        <select name="thang" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}" <?php if(Date('m') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Năm</label>
                        <select name="nam" class="form-control">
                            @for($i = 2021; $i < 2100; $i++)
                                <option value="{{$i}}" <?php if(Date('Y') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Nhân viên</label>
                        <select name="nhanVien" class="form-control">
                            @foreach($user as $row)
                                @if($row->active == true)
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->userDetail->surname}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <input id="chon" type="submit "class="btn btn-xs btn-info" value="Chọn">
                    </div>
                </div>  
                <br/>
                <h4>Đang thao tác trên user: <span class="text-red" id="userName"></span></h4>
                <input type="hidden" name="idChiTiet">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="vaoSang" class="form-control" placeholder="Vào ca sáng vd: 07:30">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="raSang" class="form-control" placeholder="Ra ca sáng vd: 11:30">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="vaoChieu" class="form-control" placeholder="Vào ca chiều vd: 12:55">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="raChieu" class="form-control" placeholder="Ra ca chiều vd: 17:00">
                    </div>
                    <div class="col-md-2">
                        <input id="luu" type="submit" class="btn btn-xs btn-success" value="Lưu">
                        <a href="{{route('import.panel')}}" class="btn btn-xs btn-info">Import Excel</a>
                    </div>                   
                </div>
                <br/>
                <table class="table table-striped table-bordered">
                    <tr class="text-center">
                        <th>Vào Sáng</th>
                        <th>Ra Sáng</th>
                        <th>Vào Chiều</th>
                        <th>Ra Chiều</th>
                        <th>Công buổi sáng</th>
                        <th>Công buổi chiều</th>
                        <th>Trể sáng</th>
                        <th>Trể chiều</th>
                    </tr>
                    <tr class="text-center" id="chiTietCong">
                       
                    </tr>
                </table>  
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

        $(document).ready(function(){

            function reloadData() {
                $.ajax({
                    url: "{{url('management/nhansu/quanly/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){
                        $("#userName").text(response.data.name);
                        if (response.chiTiet !== null && response.chiTiet.length != 0) {
                            let txt = `<td class='text-success'>${response.chiTiet.vaoSang}</td>
                            <td class='text-success'>${response.chiTiet.raSang}</td>
                            <td class='text-success'>${response.chiTiet.vaoChieu}</td>
                            <td class='text-success'>${response.chiTiet.raChieu}</td>
                            <td class='text-info'>${response.chiTiet.gioSang} (giờ)</td>
                            <td class='text-info'>${response.chiTiet.gioChieu} (giờ)</td>
                            <td class='text-danger'>${response.chiTiet.treSang} (phút)</td>
                            <td class='text-danger'>${response.chiTiet.treChieu} (phút)</td>`;

                            $("input[name=idChiTiet]").val(response.chiTiet.id);
                            $("#chiTietCong").html(txt);
                            $("input[name=vaoSang]").val(response.chiTiet.vaoSang);
                            $("input[name=raSang]").val(response.chiTiet.raSang);
                            $("input[name=vaoChieu]").val(response.chiTiet.vaoChieu);
                            $("input[name=raChieu]").val(response.chiTiet.raChieu);
                        } else {
                            $("input[name=idChiTiet]").val(0);
                            $("#chiTietCong").html("");
                            $("input[name=vaoSang]").val("");
                            $("input[name=raSang]").val("");
                            $("input[name=vaoChieu]").val("");
                            $("input[name=raChieu]").val("");
                        }                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
            }

           $("#chon").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/quanly/ajax/getnhanvien')}}",
                    type: "get",
                    dataType: "json",
                    data: {
                        "id": $("select[name=nhanVien]").val(),
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    success: function(response){
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        $("#userName").text(response.data.name);
                        if (response.chiTiet !== null && response.chiTiet.length != 0) {
                            let txt = `<td class='text-success'>${response.chiTiet.vaoSang}</td>
                            <td class='text-success'>${response.chiTiet.raSang}</td>
                            <td class='text-success'>${response.chiTiet.vaoChieu}</td>
                            <td class='text-success'>${response.chiTiet.raChieu}</td>
                            <td class='text-info'>${response.chiTiet.gioSang} (giờ)</td>
                            <td class='text-info'>${response.chiTiet.gioChieu} (giờ)</td>
                            <td class='text-danger'>${response.chiTiet.treSang} (phút)</td>
                            <td class='text-danger'>${response.chiTiet.treChieu} (phút)</td>`;

                            $("input[name=idChiTiet]").val(response.chiTiet.id);
                            $("#chiTietCong").html(txt);
                            $("input[name=vaoSang]").val(response.chiTiet.vaoSang);
                            $("input[name=raSang]").val(response.chiTiet.raSang);
                            $("input[name=vaoChieu]").val(response.chiTiet.vaoChieu);
                            $("input[name=raChieu]").val(response.chiTiet.raChieu);
                        } else {
                            $("input[name=idChiTiet]").val(0);
                            $("#chiTietCong").html("");
                            $("input[name=vaoSang]").val("");
                            $("input[name=raSang]").val("");
                            $("input[name=vaoChieu]").val("");
                            $("input[name=raChieu]").val("");
                        }                   
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           });


           $("#luu").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/quanly/ajax/postnhanvien')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $("select[name=nhanVien]").val(),
                        "idChamCong": $("input[name=idChiTiet]").val(),
                        "ngay": $("select[name=ngay]").val(),
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val(),
                        "vaoSang": $("input[name=vaoSang]").val(),
                        "raSang": $("input[name=raSang]").val(),
                        "vaoChieu": $("input[name=vaoChieu]").val(),
                        "raChieu": $("input[name=raChieu]").val(),
                    },
                    success: function(response){
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })

                        reloadData();      
                    },
                    error: function(){
                        Toast.fire({
                            icon: "error",
                            title: "Lỗi! Không thể chọn"
                        })
                    }
                });
           });
        });
    </script>
@endsection
