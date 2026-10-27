@extends('admin.index')
@section('title')
    Tổng quan Chấm công Online
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal {
            overflow-y: auto !important;
        }
        #modalKetQuaKiemTra .modal-body {
            max-height: 65vh;
            overflow-y: auto;
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
                        <h1 class="m-0"><strong>Tổng quan Chấm công Online</strong></h1>
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
                                            Tổng quan Chấm công Online
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
                                                    <label>Chọn ngày</label>
                                                    <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <a href="{{route('quanly.chamcong.online.chitiet')}}" target="_blank" class="btn btn-success">CHI TIẾT CHẤM CÔNG</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <button id="luuChamCong" class="btn btn-primary">LƯU CHẤM CÔNG</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label><br/>
                                                    <button id="kiemTraChamCong" class="btn btn-warning">KIỂM TRA CHẤM CÔNG</button>
                                                </div>
                                            </div>
                                        </div>     
                                        <hr>
                                        <div id="alertChuaPhanCa" class="alert alert-warning font-weight-bold" style="display: none;">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <span id="alertChuaPhanCaMsg"></span>
                                            <a href="{{ route('ghepca.panel') }}" target="_blank" class="btn btn-xs btn-danger ml-2 font-weight-bold">
                                                <i class="fas fa-user-clock mr-1"></i>Ghép ca ngay
                                            </a>
                                        </div>
                                        <table id="dataTable" class="display" style="width:100%">
                                            <thead>
                                                <tr class="bg-cyan">
                                                    <th>TT</th>
                                                    <th>Mã NV</th>
                                                    <th>Họ tên</th>    
                                                    <th>Ngày chấm công</th>  
                                                    <th>Vào Sáng</th>                                              
                                                    <th>Ra Sáng</th>
                                                    <th>Vào Chiều</th>
                                                    <th>Ra Chiều</th>
                                                    <th>Vào Tối</th>
                                                    <th>Ra Tối</th>
                                                    <th>Công sáng</th>
                                                    <th>Công chiều</th>
                                                    <th>Trể sáng</th>
                                                    <th>Trể chiều</th>
                                                    <th>Trạng thái</th>
                                                    <th>Ghi chú</th>                                    
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

    <!-- Modal Chọn Thời Gian Kiểm Tra -->
    <div class="modal fade" id="modalChonGioKiemTra" tabindex="-1" role="dialog" aria-labelledby="modalChonGioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold" id="modalChonGioLabel">
                        <i class="fas fa-clock mr-2"></i>Chọn thời gian kiểm tra vào sáng
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label for="gioKiemTra"><strong>Mốc thời gian (hh:mm):</strong></label>
                        <input type="time" class="form-control form-control-lg text-center font-weight-bold" id="gioKiemTra" value="07:55" required>
                        <small class="form-text text-muted mt-2">
                            Hệ thống sẽ lọc ra các nhân viên có thời gian <strong>Vào Sáng</strong> sau mốc giờ này (ví dụ chọn 07:55 thì 07:56 tính là vào sau, đúng 07:55 không tính).
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" id="btnXacNhanKiemTra" class="btn btn-primary font-weight-bold px-4">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hiển Thị Kết Quả Kiểm Tra -->
    <div class="modal fade" id="modalKetQuaKiemTra" tabindex="-1" role="dialog" aria-labelledby="modalKetQuaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="modalKetQuaLabel">
                        <i class="fas fa-list-alt mr-2"></i>Danh sách nhân viên vào sáng sau mốc giờ
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <div id="thongBaoKetQua" class="mb-3"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center mb-0" id="tableKetQuaKiemTra">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px;">STT</th>
                                    <th style="width: 120px;">Mã NV</th>
                                    <th>Họ và tên</th>
                                    <th style="width: 150px;">Ngày chấm công</th>
                                    <th style="width: 150px;">Vào Sáng</th>
                                </tr>
                            </thead>
                            <tbody id="bodyKetQuaKiemTra">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnXuatExcelKiemTra" class="btn btn-success font-weight-bold">
                        <i class="fas fa-file-excel mr-1"></i>Xuất Excel
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,           // tăng lên 5000 ms = 5s
            timerProgressBar: true // (tùy chọn) hiển thị thanh tiến trình
        });

        // show data
        $(document).ready(function() {
            let from = $("input[name=chonNgayOne]").val();
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/nhansu/chamcongonline/getlisttongquan') }}" + '?from=' + from,
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
                            if (row.vaoSangTre && row.vaoSangTre < 240) {
                               return "<span>"+row.vaoSang+"</span><br/><span class='badge badge-warning'>[Sáng trễ: "+ row.vaoSangTre + "p]</span>";
                            } else {
                               return row.vaoSang;
                            }
                        }
                    },
                    { "data": "raSang" },
                    {
                        "data": null,
                        render: function(data, type, row) {  
                            if (row.vaoChieuTre && row.vaoChieuTre < 240) {
                               return "<span>"+row.vaoChieu+"</span><br/><span class='badge badge-warning'>[Chiều trễ: "+ row.vaoChieuTre + "p]</span>";
                            } else {
                               return row.vaoChieu;
                            }
                        }
                    },
                    { "data": "raChieu" },
                    { "data": "vaoToi" },
                    { "data": "raToi" },
                    { "data": "caSang" },
                    { "data": "caChieu" },
                    { "data": "treSang" },
                    { "data": "treChieu" },
                    {
                        "data": null,
                        render: function(data, type, row) {  
                            if (!row.codulieu) {
                               return "<span class='badge badge-danger'>Không chấm công</span>";
                            } else if (row.duyet) {
                               return "<span class='badge badge-success'>Đã kiểm duyệt</span>";
                            } else {
                               return "<span class='badge badge-warning'>Chưa kiểm duyệt</span>";
                            }
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                            
                            let badges = "";
                            if (row.chuaPhanCa) {
                                badges += "<span class='badge badge-danger'>Chưa phân ca</span><br/>";
                            } else if (row.tenCa && row.tenCa !== 'Mặc định') {
                                badges += "<span class='badge badge-light border text-primary font-weight-bold'>Ca: " + row.tenCa + "</span><br/>";
                            }
                            if (row.chamcong2lan) {
                                badges += "<span class='badge badge-info'>Chấm 02 lần</span> ";
                            } else if (row.nhanvienvesinh) {
                                badges += "<span class='badge badge-primary'>NVVS</span> ";
                            }
                            return badges;
                        }
                    }
                ]
            });

            var soNVChuaPhanCaGlobal = 0;
            var isMacDinhGlobal = 1;

            table.on('xhr', function() {
                let json = table.ajax.json();
                if (json) {
                    isMacDinhGlobal = json.isMacDinh !== undefined ? json.isMacDinh : 1;
                    soNVChuaPhanCaGlobal = json.soNhanVienChuaPhanCa !== undefined ? json.soNhanVienChuaPhanCa : 0;
                    if (isMacDinhGlobal === 0 && soNVChuaPhanCaGlobal > 0) {
                        $("#alertChuaPhanCaMsg").html("Chế độ tính công theo ca làm đang <strong>BẬT</strong>: Hiện có <strong class='text-danger'>" + soNVChuaPhanCaGlobal + "</strong> nhân viên chưa được phân ca! Hệ thống tạm dùng mốc mặc định.");
                        $("#alertChuaPhanCa").slideDown();
                    } else {
                        $("#alertChuaPhanCa").slideUp();
                    }
                }
            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();
          
            $("#xemReport").click(function(){
                let from = $("input[name=chonNgayOne]").val();
                let urlpathcurrent = "{{ url('management/nhansu/chamcongonline/getlisttongquan') }}";
                table.ajax.url( urlpathcurrent + '?from=' + from).load();
            });    
            
            // Lưu chấm công
            $(document).on('click','#luuChamCong',function(e){
                let from = $("input[name=chonNgayOne]").val(); 
                let confirmMsg = 'Xác nhận lưu dữ liệu chấm công vào CSDL?\nLưu ý: Vui lòng kiểm tra thật kỹ trước khi thực hiện thao tác này!';
                if (isMacDinhGlobal === 0 && soNVChuaPhanCaGlobal > 0) {
                    confirmMsg = 'CẢNH BÁO: Đang có ' + soNVChuaPhanCaGlobal + ' nhân viên chưa được phân ca làm việc!\n' + confirmMsg;
                }
                if (confirm(confirmMsg)) {  
                    $.ajax({
                        url: "{{url('management/nhansu/chamcongonline/luuchamcong/')}}"+ '?from=' + from,
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}"
                        },
                        success: function(response) {
                            if (response.code == 200) {
                                Toast.fire({
                                    icon: response.type,
                                    title: response.message
                                })
                                $("#xemReport").click();
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
                                title: "Không thể lưu lúc này!"
                            })
                        }
                    });
                }                     
            });

            // Mở modal chọn thời gian kiểm tra khi nhấn nút KIỂM TRA CHẤM CÔNG
            $("#kiemTraChamCong").click(function(){
                $('#modalChonGioKiemTra').modal('show');
            });

            // Hàm chuyển đổi chuỗi giờ hh:mm hoặc hh:mm:ss thành tổng số phút
            function timeStringToMinutes(timeStr) {
                if (!timeStr) return null;
                let parts = timeStr.trim().split(':');
                if (parts.length < 2) return null;
                let h = parseInt(parts[0], 10);
                let m = parseInt(parts[1], 10);
                if (isNaN(h) || isNaN(m)) return null;
                return h * 60 + m;
            }

            // Xử lý khi nhấn nút OK xác nhận kiểm tra
            $("#btnXacNhanKiemTra").click(function(){
                let mTime = $("#gioKiemTra").val();
                if (!mTime) {
                    alert("Vui lòng chọn mốc thời gian kiểm tra!");
                    return;
                }

                let targetMinutes = timeStringToMinutes(mTime);
                if (targetMinutes === null) {
                    alert("Thời gian không hợp lệ!");
                    return;
                }

                // Lấy toàn bộ dữ liệu hiện tại từ bảng dataTable
                let allData = table.rows().data().toArray();
                let danhSachSauGio = [];

                allData.forEach(function(row) {
                    // Kiểm tra trường Vào Sáng
                    if (row && row.vaoSang) {
                        let empMinutes = timeStringToMinutes(row.vaoSang);
                        // Chỉ tính nếu vào SAU thời điểm mốc (lớn hơn; bằng nhau không tính)
                        if (empMinutes !== null && empMinutes > targetMinutes) {
                            danhSachSauGio.push(row);
                        }
                    }
                });

                // Cập nhật tiêu đề modal kết quả
                $("#modalKetQuaLabel").html('<i class="fas fa-list-alt mr-2"></i>Danh sách nhân viên vào sáng sau <strong>' + mTime + '</strong>');

                let tbody = $("#bodyKetQuaKiemTra");
                tbody.empty();

                if (danhSachSauGio.length > 0) {
                    $("#thongBaoKetQua").html(
                        '<div class="alert alert-warning font-weight-bold mb-0">' +
                            '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                            'Tìm thấy <strong>' + danhSachSauGio.length + '</strong> nhân viên vào sáng sau ' + mTime +
                        '</div>'
                    );

                    danhSachSauGio.forEach(function(item, index) {
                        let tr = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td><strong>' + (item.manv ? item.manv : '') + '</strong></td>' +
                            '<td class="text-left">' + (item.hoten ? item.hoten : '') + '</td>' +
                            '<td>' + (item.ngaychamcong ? item.ngaychamcong : '') + '</td>' +
                            '<td><strong class="text-danger font-weight-bold" style="font-size: 1.15rem; background-color: #ffe6e6; padding: 2px 8px; border-radius: 4px; display: inline-block;">' + (item.vaoSang ? item.vaoSang : '') + '</strong></td>' +
                        '</tr>';
                        tbody.append(tr);
                    });
                    $("#tableKetQuaKiemTra").show();
                    $("#btnXuatExcelKiemTra").show();
                } else {
                    $("#thongBaoKetQua").html(
                        '<div class="alert alert-success font-weight-bold mb-0">' +
                            '<i class="fas fa-check-circle mr-2"></i>' +
                            'Không có nhân viên nào vào sáng sau ' + mTime +
                        '</div>'
                    );
                    $("#tableKetQuaKiemTra").hide();
                    $("#btnXuatExcelKiemTra").hide();
                }

                // Lưu dữ liệu để phục vụ xuất Excel
                currentDanhSachSauGio = danhSachSauGio;
                currentMocGioKiemTra = mTime;

                // Đợi modal chọn giờ đóng hoàn tất rồi mới mở modal kết quả để tránh xung đột làm mất class modal-open của Bootstrap
                $('#modalChonGioKiemTra').one('hidden.bs.modal', function () {
                    $('#modalKetQuaKiemTra').modal('show');
                });
                $('#modalChonGioKiemTra').modal('hide');
            });

            // Biến lưu dữ liệu kết quả kiểm tra phục vụ xuất Excel
            let currentDanhSachSauGio = [];
            let currentMocGioKiemTra = '';

            // Xử lý xuất Excel danh sách nhân viên vào sau mốc giờ
            $("#btnXuatExcelKiemTra").click(function(){
                if (!currentDanhSachSauGio || currentDanhSachSauGio.length === 0) {
                    alert("Không có dữ liệu nhân viên để xuất Excel!");
                    return;
                }

                let dateFilter = $("input[name=chonNgayOne]").val() || "";
                let wb = XLSX.utils.book_new();

                let ws_data = [
                    ["DANH SÁCH NHÂN VIÊN VÀO SÁNG SAU " + currentMocGioKiemTra],
                    ["Ngày chấm công: " + dateFilter],
                    ["Tổng số nhân viên: " + currentDanhSachSauGio.length],
                    [],
                    ["STT", "Mã NV", "Họ và tên", "Ngày chấm công", "Vào Sáng"]
                ];

                currentDanhSachSauGio.forEach(function(item, index) {
                    ws_data.push([
                        index + 1,
                        item.manv ? item.manv : '',
                        item.hoten ? item.hoten : '',
                        item.ngaychamcong ? item.ngaychamcong : '',
                        item.vaoSang ? item.vaoSang : ''
                    ]);
                });

                let ws = XLSX.utils.aoa_to_sheet(ws_data);

                // Căn chỉnh độ rộng các cột (đơn vị: ký tự)
                ws['!cols'] = [
                    { wch: 8 },  // STT
                    { wch: 15 }, // Mã NV
                    { wch: 30 }, // Họ và tên
                    { wch: 18 }, // Ngày chấm công
                    { wch: 15 }  // Vào Sáng
                ];

                XLSX.utils.book_append_sheet(wb, ws, "Vao_Sang_Tre");

                let safeTime = currentMocGioKiemTra.replace(':', 'h');
                let fileName = "Danh_sach_nhan_vien_vao_sang_sau_" + safeTime + (dateFilter ? "_" + dateFilter : "") + ".xlsx";

                XLSX.writeFile(wb, fileName);
            });

            // Đảm bảo body luôn có class modal-open và cho phép scroll khi modal kết quả hiển thị
            $('#modalKetQuaKiemTra').on('shown.bs.modal', function () {
                $('body').addClass('modal-open');
            });
        });
        
    </script>
@endsection
