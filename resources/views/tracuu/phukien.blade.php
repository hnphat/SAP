@extends('admin.index')
@section('title')
    Tra cứu phụ kiện
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Tra cứu phụ kiện</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Tra cứu phụ kiện</li>
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
                                    <strong>Tra cứu phụ kiện</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <form id="tracuu" autocomplete="off">
                                    {{csrf_field()}}
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Dòng xe</label>
                                            <select name="dongXe" id="dongXe" class="form-control">
                                                @foreach($typecar as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Chọn phụ kiện cần tra cứu</label>
                                            <select name="chonHangHoa" id="chonHangHoa" class="form-control">
                                            
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Mã phụ kiện</label>
                                            <input name="code" type="text" class="form-control" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <input name="namePkPay" type="text" class="form-control" readonly="readonly">
                                        </div>
                                        <div class="form-group">
                                            <label>Giá</label>
                                            <strong class="text-primary" id="showGiaCost"></strong><br/>
                                            <strong class="text-secondary" id="showGia"></strong>
                                        </div>
                                        <div class="form-group">
                                            <label>Giá tặng</label>
                                            <strong class="text-primary" id="showGiaTangCost"></strong><br/>
                                            <strong class="text-secondary" id="showGiaTang"></strong>
                                        </div>
                                    </div>
                                </form>                              
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
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
            function autoloadPkPay(idtypecar) {
                $.ajax({
                    url: 'management/hd/hd/denghi/loadpkpayfromtypecar',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "idtypecar": idtypecar
                    },
                    success: function(response){
                        $("#chonHangHoa").empty();
                        txt = "";
                        result =  response.data;
                        for(let i = 0; i < result.length; i++) {
                            txt += `<option value="${result[i].ma}">${result[i].noiDung}</option>`;
                        }
                        $("#chonHangHoa").html(txt);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi không thể tải"
                        })
                    }
                });
            }

            function autoloadCostFromPK(mahang) {
                $.ajax({
                    url: 'management/hd/hd/denghi/chonhanghoa',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{csrf_token()}}",
                        "mahang": mahang
                    },
                    success: function(response){
                        if (response.code == 200) {
                            $("input[name=namePkPay]").val(response.data.noiDung);
                            $("#showGiaCost").text(formatNumber(parseInt(response.data.donGia)));
                            $("input[name=code]").val(response.data.ma);
                            $('#showGia').text("(" + DOCSO.doc(parseInt(response.data.donGia)) + ")");
                            $("#showGiaTangCost").text(formatNumber(parseInt(response.data.giaTang)));
                            $('#showGiaTang').text("(" + DOCSO.doc(parseInt(response.data.giaTang)) + ")");
                        } else {
                            $("input[name=code]").val("Không tìm thấy");
                            $("input[name=namePkPay]").val("Không tìm thấy");
                            $("#showGiaCost").text(0);
                            $("#showGiaTangCost").text(0);
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi!"
                        })
                        $("input[name=code]").val("Không tìm thấy");
                        $("input[name=namePkPay]").val("Không tìm thấy");
                        $("#showGiaCost").text(0);
                    }
                });
            }
            
            $("select[name=dongXe]").change(function(){                
                idtypecar = $("select[name=dongXe]").val();
                autoloadPkPay(idtypecar);
                setTimeout(() => {
                    autoloadCostFromPK($("select[name=chonHangHoa]").val());
                }, 500);
            });

            $("select[name=chonHangHoa]").change(function(){                
                autoloadCostFromPK($("select[name=chonHangHoa]").val());
            });

            autoloadPkPay($("select[name=dongXe]").val());
            setTimeout(() => {
                autoloadCostFromPK($("select[name=chonHangHoa]").val());
            }, 1000);
       });
    </script>
@endsection
