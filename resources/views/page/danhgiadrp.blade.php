@extends('admin.index')
@section('title')
    Đánh giá DRP
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
                        <h1 class="m-0"><strong>Đánh giá DRP</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng DRP - Đánh giá DRP</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Đánh giá DRP</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                              <h5>Ngày tạo đánh giá: <span class="text-secondary">{{\HelpFunction::revertCreatedAt($info->created_at)}}</span></h5>
                              <h5>Khách hàng: <span class="text-primary">{{$info->khachHang}}</span></h5>
                              <h5>Điện thoại: <span class="text-primary">{{$info->dienThoai}}</span></h5>
                              <h5>Địa chỉ: <span class="text-primary">{{$info->diaChi}}</span></h5>
                              <h5>Xe quan tâm: <span class="text-primary">{{$info->xeQuanTam}}</span></h5>
                              <h5>Sale: <span class="text-info">{{$info->user->userDetail->surname}}</span></h5>
                              <hr>
                              <h4>BẢNG CÂU HỎI ĐÁNH GIÁ</h4>
                              <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Câu hỏi</th>
                                        <th>Điểm tối đa</th>
                                        <th>Điểm đánh giá</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody id="showData">
                                    
                                </tbody>
                              </table>
                              @if(!$info->danhGia)
                                <button id="xacNhanDanhGia" data-id="{{$info->id}}" class="btn btn-success">XÁC NHẬN ĐÁNH GIÁ</button>
                              @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!--  MEDAL ADD-->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="danhGiaModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                    
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="danhGiaForm" autocomplete="off">
                            <input type="hidden" name="idCheck">
                            <div class="form-group">
                               <label>Điểm đánh giá</label> 
                               <input placeholder="Điểm đánh giá" type="number" id="diemCham" name="diemCham" min="0" step=".1" class="form-control" required >
                            </div>                                                  
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnDanhGia" class="btn btn-primary" form="danhGiaForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->
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

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        var DOCSO = function(){ var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" tư":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{doc:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();
       
        $(document).ready(function(){
            let arrData = {!! str_replace("\\", "", json_encode($data, JSON_UNESCAPED_UNICODE))  !!};
            function autoload(data) {
                $("#showData").empty();
                let txt = ``;
                let i = 1;
                for (let index = 0; index < data.length; index++) {                    
                    const ele = data[index];
                    txt += `<tr>
                                <td>${i++}</td>
                                <td>${ele.drp_question}</td>
                                <td class="text-pink"><strong>${ele.diemToiDa}</strong></td>
                                <td>${ele.diemCham}</td>
                                <td>
                                    <button id="danhGia" data-toggle='modal' data-target='#danhGiaModal' data-id="${ele.id}" data-cham="${ele.diemCham}" data-max="${ele.diemToiDa}" class="btn btn-info btn-sm"><span class="fas fa-edit"></span></button>
                                </td>
                            </tr>`;
                }
                $("#showData").html(txt);
            }
            setTimeout(() => {
                autoload(arrData);
            }, 1000);

            $(document).on('click','#danhGia', function(){
                let idcheck = $(this).data('id');
                let diemToiDa = $(this).data('max');
                let diemCham = $(this).data('cham');
                $("input[name=diemCham]").val(diemCham);
                $("input[name=idCheck]").val(idcheck);
                $("input[name=diemCham]").attr({
                    "max" : diemToiDa
                });
                setTimeout(() => {
                    $("input[name=diemCham]").focus();
                }, 500);
            });


            $("#btnDanhGia").click(function(){   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#danhGiaForm").one("submit", submitFormFunction);
                function submitFormFunction(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{route('drp.check.post')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnDanhGia").attr('disabled', true).html("Đang xử lý...");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("#danhGiaModal").modal('hide');
                            $("#btnDanhGia").attr('disabled', false).html("Lưu");
                            // console.log(response.data);
                            if (response.code != 500)
                                autoload(response.data);
                        },
                        error: function(response){
                            console.log(response);
                            $("#btnDanhGia").attr('disabled', false).html("Lưu");
                        }
                    });
                }
            });

            //Delete data
            $("#xacNhanDanhGia").click(function(){
                if(confirm('Xác nhận đánh giá?\nLưu ý: Kiểm tra các tiêu chí đánh giá, xác nhận sẽ khoá đánh giá không thể chỉnh sửa!')) {
                    $.ajax({
                        url: "{{ route('drp.check.done') }}",
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
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xác nhận lúc này!"
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection
