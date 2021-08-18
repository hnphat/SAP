@extends('admin.index')
@section('title')
    Phê duyệt hợp đồng
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Phê duyệt hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Phê duyệt hợp đồng</li>
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
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Hợp đồng cần duyệt</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="so-01-tab" data-toggle="pill" href="#so-01" role="tab" aria-controls="so-01" aria-selected="true">Hợp đồng chi tiết</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="so-02-tab" data-toggle="pill" href="#so-02" role="tab" aria-controls="so-02" aria-selected="true">Hợp đồng đã duyệt</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Mã hợp đồng</th>
                                    <th>Khách hàng</th>
                                    <th>Xe bán</th>
                                    <th>Giá</th>
                                    <th>Sale bán</th>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                        <th>Admin duyệt</th>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                    <th>Quản lý duyệt</th>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                                    <th>Kế toán duyệt</th>
                                    @endif
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($hd as $row)
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') && $row->admin_check == 1)
                                            @continue
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') && $row->lead_sale_check == 1)
                                            @continue
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan') && $row->complete == 1)
                                            @continue
                                        @endif
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>HAGI-0{{$row->id}}/HDMB-PA</td>
                                            <td>{{$row->surname}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{number_format($row->cost)}}</td>
                                            <td>{{$row->salemen}}</td>
                                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                            <td>
                                                @if($row->admin_check == 0)
                                                    <button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>
                                                @else
                                                    <button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>
                                                @endif
                                            </td>
                                            @endif
                                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                            <td>
                                                @if($row->lead_sale_check == 0)
                                                    <button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>
                                                @else
                                                    <button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>
                                                @endif
                                            </td>
                                            @endif
                                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                                            <td>
                                                @if($row->complete == 0)
                                                    <button class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>
                                                @else
                                                    <button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button>
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                <button id="check" data-id="{{$row->id}}" class="btn btn-success btn-sm">Check</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="so-01" role="tabpanel" aria-labelledby="so-01-tab">
                            <div>
                                <div class="row">
                                    <div class="col-sm-4">
                                            <label>CHỌN HỢP ĐỒNG</label>
                                            <select name="chonHD" id="chonHD" class="form-control">
                                                <option value="0" selected="selected">Chọn</option>
                                                @foreach($hd as $row)
                                                    <option value="{{$row->id}}">HAGI-0{{$row->id}}/HDMB-PA ({{$row->surname}})</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <hr>
                                <h5>THÔNG TIN HỢP ĐỒNG</h5>
                                <div>
                                    <p>Họ và tên: <strong id="xHoTen"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày sinh: <strong id="xNgaySinh"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Số điện thoại: <strong id="xDienThoai"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MST: <strong id="xmst"></strong></p>
                                    <p>CMND: <strong id="xcmnd"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ngày Cấp: <strong id="xNgayCap"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi cấp: <strong id="xNoiCap"></strong></p>
                                    <p>Địa chỉ: <strong id="xDiaChi"></strong></p>
                                    <p>Đại diện: <strong id="xDaiDien"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chức vụ: <strong id="xChucVu"></strong></p>
                                </div>
                                <hr>
                                <h5>THÔNG TIN XE BÁN</h5>
                                <table class="table table-bordered table-striped">
                                    <tr class="bg-orange">
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Số vin</th>
                                        <th>Số máy</th>
                                        <th>Thông tin khác</th>
                                        <th>Giá xe</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td id="x_ten"></td>
                                        <td id="x_vin"></td>
                                        <td id="x_frame"></td>
                                        <td id="x_detail"></td>
                                        <td id="x_cost"></td>
                                    </tr>
                                </table>
                                <p>Tiền cọc: <strong id="xtamUng"></strong></p>
                                <hr>
                                <h5>PHỤ KIỆN BÁN</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr class="bg-cyan">
                                        <th>TT</th>
                                        <th>Nội dung</th>
                                        <th>Giá</th>
                                        <th>Hoa hồng</th>
                                    </tr>
                                    <tbody id="showPKPAY">
                                    </tbody>
                                    </thead>
                                </table>
                                <p>Tổng cộng: <strong id="xtongPay"></strong></p>
                            </div>
                            <div>
                                <h5>PHỤ KIỆN, KHUYẾN MÃI, QUÀ TẶNG</h5>
                                <table class="table table-bordered table-striped">
                                    <tr class="bg-cyan">
                                        <th>TT</th>
                                        <th>Nội dung</th>
                                        <th>Giá</th>
                                    </tr>
                                    <tbody id="showPKFREE">
                                    </tbody>
                                </table>
                                <p>Tổng cộng: <strong id="xtongFree"></strong> (Miễn phí)</p>
                            </div>
                            <div>
                                <h5>CÁC LOẠI PHÍ</h5>
                                <table class="table table-bordered table-striped">
                                    <tr class="bg-cyan">
                                        <th>TT</th>
                                        <th>Nội dung</th>
                                        <th>Giá</th>
                                        <th>Hoa hồng</th>
                                    </tr>
                                    <tbody id="showPKCOST">
                                    </tbody>
                                </table>
                                <p>Tổng cộng: <strong id="xtongCost"></strong></p>
                            </div>
                            <h4 class="text-right">
                                TỔNG: <strong id="xtotal"></strong>
                            </h4>
                        </div>
                        <div class="tab-pane fade" id="so-02" role="tabpanel" aria-labelledby="so-02-tab">
                            <table id="dataTable2" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Mã hợp đồng</th>
                                    <th>Khách hàng</th>
                                    <th>Xe bán</th>
                                    <th>Giá</th>
                                    <th>Sale bán</th>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                        <th>Đã duyệt</th>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                        <th>Đã duyệt</th>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                                        <th>Đã duyệt</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hd as $row)
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') && $row->admin_check != 1)
                                        @continue
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') && $row->lead_sale_check != 1)
                                        @continue
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan') && $row->complete != 1)
                                        @continue
                                    @endif
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>HAGI-0{{$row->id}}/HDMB-PA</td>
                                        <td>{{$row->surname}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{number_format($row->cost)}}</td>
                                        <td>{{$row->salemen}}</td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('adminsale'))
                                            <td>
                                                <span style="color: green;" class="fas fa-check-circle"></span>
                                            </td>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('tpkd'))
                                            <td>
                                                <span style="color: green;" class="fas fa-check-circle"></span>
                                            </td>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('ketoan'))
                                            <td>
                                                <span style="color: green;" class="fas fa-check-circle"></span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page specific script -->
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

        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#dataTable2").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click','#check', function(){
           if(confirm('Xác nhận phê duyệt hợp đồng này? \nLưu ý: Phê duyệt sẽ không thể hồi lại!')){
                open('management/pheduyet/check/' + $(this).data('id'),'_self');
           }
        });

        //load quickly PK Pay
        function loadPKPay(id) {
            $.ajax({
                url: 'management/hd/get/pkpay/' + id,
                dataType: 'json',
                success: function(response){
                    // Show package pay
                    txt = "";
                    sum = 0;
                    for(i = 0; i < response.pkban.length; i++) {
                        txt += "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + response.pkban[i].name + "</td>" +
                            "<td>" + formatNumber(response.pkban[i].cost) + "</td>" +
                            "<td>" + formatNumber(response.pkban[i].profit) + "</td>" +
                            "</tr>";
                        sum += response.pkban[i].cost;
                    }
                    $("#showPKPAY").html(txt);
                    $("#xtongPay").text(formatNumber(sum));
                    loadTotal($("select[name=chonHD]").val());
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: "Lỗi không thể làm mới phụ kiện bán"
                    })
                }
            });
        }
        //load quickly PK Free
        function loadPKFree(id) {
            $.ajax({
                url: 'management/hd/get/pkfree/' + id,
                dataType: 'json',
                success: function(response){
                    // Show package pay
                    txt = "";
                    sum = 0;
                    for(let i = 0; i < response.pkfree.length; i++) {
                        txt += "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + response.pkfree[i].name + "</td>" +
                            "<td>" + formatNumber(response.pkfree[i].cost) + "</td>" +
                            "</tr>";
                        sum += response.pkfree[i].cost;
                    }
                    $("#showPKFREE").html(txt);
                    $("#xtongFree").text(formatNumber(sum));
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: "Lỗi không thể làm mới phụ kiện bán"
                    })
                }
            });
        }
        //load quickly PK Cost
        function loadPKCost(id) {
            $.ajax({
                url: 'management/hd/get/pkcost/' + id,
                dataType: 'json',
                success: function(response){
                    // Show package pay
                    txt = "";
                    sum = 0;
                    for(let i = 0; i < response.pkcost.length; i++) {
                        txt += "<tr>" +
                            "<td>" + (i+1) + "</td>" +
                            "<td>" + response.pkcost[i].name + "</td>" +
                            "<td>" + formatNumber(response.pkcost[i].cost) + "</td>" +
                            "<td>" + formatNumber(response.pkcost[i].profit) + "</td>" +
                            "</tr>";
                        sum += response.pkcost[i].cost;
                    }
                    $("#showPKCOST").html(txt);
                    $("#xtongCost").text(formatNumber(sum));
                    loadTotal($("select[name=chonHD]").val());
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: "Lỗi không thể làm mới các loại phí"
                    })
                }
            });
        }
        //Load total
        function loadTotal(id) {
            $.ajax({
                url: 'management/hd/get/total/' + id,
                dataType: 'text',
                success: function(response){
                    $("#xtotal").text(formatNumber(response));
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: "Lỗi không thể làm mới các loại phí"
                    })
                }
            });
        }

        $(document).on('change','#chonHD', function(){
            $.ajax({
                url: "management/pheduyet/detail/hd/" + $("select[name=chonHD]").val(),
                dataType: "json",
                success: function(response) {
                    if (response.code != 500) {
                        $("#xHoTen").text(response.data.surname);
                        $("#xDienThoai").text(response.data.phone);
                        $("#xmst").text(response.data.mst);
                        $("#xcmnd").text(response.data.cmnd);
                        $("#xNgayCap").text(response.data.ngayCap);
                        $("#xNoiCap").text(response.data.noiCap);
                        $("#xNgaySinh").text(response.data.ngaySinh);
                        $("#xDiaChi").text(response.data.address);
                        $("#xDaiDien").text(response.data.daiDien);
                        $("#xChucVu").text(response.data.chucVu);
                        // $("#xtamUng").text(formatNumber(response.data.tamUng));
                        $("#x_ten").text(response.data.name_car);
                        $("#x_vin").text(response.data.vin);
                        $("#x_frame").text(response.data.frame);
                        let detail = "Màu xe: " + response.data.color +
                            "; động cơ: " + response.data.machine +
                            "; Số: " + response.data.gear +
                            "; Chỗ ngồi: " + response.data.seat + " chỗ" +
                            "; Nhiên liệu: " + response.data.fuel;
                        $("#x_detail").text(detail);
                        $("#x_cost").text(formatNumber(response.data.cost));
                        loadPKPay($("select[name=chonHD]").val());
                        loadPKFree($("select[name=chonHD]").val());
                        loadPKCost($("select[name=chonHD]").val());
                        loadTotal($("select[name=chonHD]").val());
                    } else {
                        Toast.fire({
                            icon: 'info',
                            title: "Chọn hợp đồng để biết thông tin"
                        })
                        $("#xHoTen").text("");
                        $("#xDienThoai").text("");
                        $("#xmst").text("");
                        $("#xcmnd").text("");
                        $("#xNgayCap").text("");
                        $("#xNoiCap").text("");
                        $("#xNgaySinh").text("");
                        $("#xDiaChi").text("");
                        $("#xDaiDien").text("");
                        $("#xChucVu").text("");
                        $("#x_ten").text("");
                        $("#x_vin").text("");
                        $("#x_frame").text("");
                        $("#x_detail").text("");
                        $("#x_cost").text("")
                        $("#xtamUng").text("");
                        $("#showPKCOST").text("");
                        $("#showPKPAY").text("");
                        $("#showPKFREE").text("");
                        $("#xtongCost").text("");
                        $("#xtongFree").text("");
                        $("#xtongPay").text("");
                        $("#xtotal").text("");
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Lỗi tải thông tin chi tiết hợp đồng từ máy chủ"
                    })
                }
            });
        });

    </script>
@endsection
