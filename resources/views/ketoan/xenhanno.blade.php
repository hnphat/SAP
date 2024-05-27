@extends('admin.index')
@section('title')
    Xe nhận nợ
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Xe nhận nợ - hoa hồng xe</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kế toán</li>
                            <li class="breadcrumb-item active">Xe nhận nợ/HH xe</li>
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
                                    <strong>Xe nhận nợ - hoa hồng xe</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Tên xe</th>
                                        <th>Màu</th>
                                        <th>VIN</th>
                                        <th>Số máy</th>
                                        <th>Nhận nợ</th>
                                        <th>Ngày nhận nợ</th>
                                        <th>Ngày rút HS</th>
                                        <th>Số ngày</th>
                                        <th>Lãi phải trả</th>
                                        <th>Giá trị vay (%)</th>
                                        <th>Lãi suất vay (%)</th>
                                        <th>Xăng lưu kho</th>
                                        <th>Trạng thái xe</th>
                                        <th>Sale</th>
                                        <th>Khách hàng</th>
                                        <th>Hoa hồng xe</th>
                                        <th>Giá vốn bảo hiểm</th>
                                        <th>% Hoa hồng công ĐK</th>
                                        <th>Giá vốn phụ kiện</th>
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
        <!-- /.content -->
    </div>
    <!--  MEDAL -->
    <div>
        <!-- Medal Edit -->
        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cập nhật</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="editForm" autocomplete="off">
                                {{csrf_field()}}
                                <input type="hidden" name="eid"/>
                                <input type="hidden" name="eidhopdong"/>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h5>
                                                Xe: <span id="sxe"></span>; Màu: <span id="smau"></span><br/>
                                                Số khung: <span id="ssokhung"></span><br/>
                                                Số máy: <span id="ssomay"></span><br/>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>Nhận nợ:</label>
                                                <select name="ghiChu" class="form-control">
                                                    <option value="1">Có</option>
                                                    <option value="0">Không</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Ngày nhận nợ</label>
                                                <input name="ngayNhanNo" placeholder="Ngày nhận nợ" type="date" class="form-control">
                                            </div>    
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Ngày rút hồ sơ</label>
                                                <input name="ngayRutHoSo" placeholder="Ngày rút hồ sơ" type="date" class="form-control">
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>% vay trên giá vốn xe (Mặc định 85%):</label>
                                                <input min="1" max="100" value="85" name="giaTriVay" placeholder="VD: 85" type="number" class="form-control">
                                            </div>    
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Lãi suất vay (%):</label>
                                                <input min="1" max="100" name="laiSuatVay" placeholder="VD: 12.5" type="number" class="form-control">
                                            </div>                                              
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Xăng lưu kho (Mặc định 150.000)</label>
                                                <input name="xangLuuKho" placeholder="Xăng lưu kho" value="150000" type="number" class="form-control">
                                            </div>    
                                            <div class="form-group">
                                                <label>Hoa hồng sale (Nếu có):</label>
                                                <input name="hoaHongSale" id="hoaHongSale" placeholder="Hoa hồng sale" type="number" class="form-control">
                                            </div> 
                                        </div> 
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>(%) Hoa hồng công đk:</label>
                                                <input name="hhcongdk" id="hhcongdk" placeholder="VD: 45" type="number" min="0" step="1" max="100" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>Giá vốn bảo hiểm:</label>
                                                <input name="giavonbh" id="giavonbh" placeholder="Giá vốn bảo hiểm" type="number" class="form-control">
                                            </div>   
                                        </div>                                                               
                                    </div>  
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Giá vốn phụ kiện</label>
                                                <input name="giavonpk" placeholder="Giá vốn phụ kiện" value="0" type="number" class="form-control">
                                            </div>    
                                        </div>                                                                
                                    </div>                                     
                                </div>                             
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnUpdate" class="btn btn-primary" form="editForm">Lưu</button>
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
        
        // $('#hoaHongSale').keyup(function(){
        //         var cos = $('#hoaHongSale').val();
        //         $('#showHoaHongSale').val("(" + DOCSO.doc(cos) + ")");
        // });

        // show data

        function CountTheDays(date_1, date_2) {
            let difference = date_1.getTime() - date_2.getTime();
            let TotalDays = Math.ceil(difference / (1000 * 3600 * 24));
            return TotalDays;
        }

        $(document).ready(function() {            
            $('#cost').keyup(function(){
                var cos = $('#cost').val();
                $('#showCost').text(formatNumber(cos) + " (" + DOCSO.doc(cos) + ")");
            });

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/ketoan/xenhanno/getkho') }}",
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
                    { "data": "ten" },
                    { "data": "color" },
                    { "data": "vin" },
                    { "data": "frame" },
                    {
                        "data": null,
                        render: function(data, type, row) {    
                            if (row.ghiChu) {
                                return (row.ghiChu == 1) ? "<span class='text-danger text-bold'>Có</span>" : "Không";
                            } else {
                                return "<span class='text-secondary'>Null</span>";
                            }                            
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {    
                            if (row.ngayNhanNo) {
                                let arr = row.ngayNhanNo.toString().split("-");    
                                return arr[2] + "-" + arr[1] + "-" + arr[0]; 
                            } else {
                                return " ";
                            }                            
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {    
                            if (row.ngayRutHoSo) {
                                let arr = row.ngayRutHoSo.toString().split("-");   
                                return arr[2] + "-" + arr[1] + "-" + arr[0]; 
                            } else {
                                return " ";
                            }                            
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {     
                            if (row.ngayNhanNo) {
                                let date_1 = new Date(row.ngayNhanNo);
                                let date_2 = new Date();
                                if (row.ngayRutHoSo)
                                    date_2 = new Date(row.ngayRutHoSo);
                                return "<strong class='text-secondary'>" + (Math.abs(CountTheDays(date_1, date_2)) + 1) + " ngày </strong>";
                            }  
                            return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {  
                            if (row.giaTriVay !== 0 && row.laiSuatVay !== 0) {
                                if (row.ngayNhanNo) {
                                    let date_1 = new Date(row.ngayNhanNo);
                                    let date_2 = new Date();
                                    if (row.ngayRutHoSo)
                                        date_2 = new Date(row.ngayRutHoSo);
                                    let countNgayNhanNo = Math.abs(CountTheDays(date_1, date_2)) + 1;
                                    return "<strong class='text-danger'>" + formatNumber(Math.round((row.giaVon * (row.giaTriVay/100) * (row.laiSuatVay/100)) / 365) * countNgayNhanNo) + "</strong>";
                                }
                            } 
                            return " ";                            
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {           
                            if (row.giaTriVay)                 
                                return "<strong class='text-primary'>" + row.giaTriVay + "%" + "</strong>"; 
                            else return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {    
                            if (row.laiSuatVay)                        
                                return "<strong class='text-primary'>" + row.laiSuatVay + "%" + "</strong>";  
                            else return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {       
                            if (row.xangLuuKho)     
                                return "<strong>" + formatNumber(row.xangLuuKho) + "</strong>";   
                            else return " ";  
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {   
                            if (row.type == "STORE")
                                return "<strong class='text-primary'>Xe lưu kho</strong>";   
                            else if (row.type == "HD" && row.xuatXe == true) {
                                return "<strong class='text-success'>Đã xuất xe</strong>";   
                            } 
                            else if (row.type == "HD" && row.xuatXe == false) {
                                return "<strong class='text-info'>Hợp đồng</strong>";   
                            }
                            else return "<strong class='text-warning'>Khác</strong>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                           
                            if (row.saleban) 
                                return "" + row.saleban;
                            else
                                return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                           
                            if (row.khach) 
                                return "" + row.khach;
                            else
                                return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {  
                            if (row.hoaHongSale)                          
                                return "<strong class='text-pink'>" + formatNumber(row.hoaHongSale) + "</strong>"; 
                            else   
                                return " ";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {       
                            if (row.giavonbh)     
                                return "<strong class='text-pink'>" + formatNumber(row.giavonbh) + "</strong>";   
                            else return " ";  
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {       
                            if (row.hhcongdk)     
                                return "<strong class='text-pink'>" + formatNumber(row.hhcongdk) + "</strong>";   
                            else return " ";  
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {       
                            if (row.giavonpk)     
                                return "<strong class='text-pink'>" + formatNumber(row.giavonpk) + "</strong>";   
                            else return " ";  
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {                           
                            return "<button id='btnEdit' data-ten='"+row.ten+"' data-id='"+row.id+"' data-idhd='"+row.idhopdong+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;";     
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

            // edit data
            $(document).on('click','#btnEdit', function(){
                $.ajax({
                    url: "{{url('management/ketoan/xenhanno/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id'),
                        "idhd": $(this).data('idhd'),
                        "tenxe": $(this).data('ten'),
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            $("input[name=eid]").val(response.data.id);
                            $("input[name=ngayNhanNo]").val(response.data.ngayNhanNo);
                            $("input[name=ngayRutHoSo]").val(response.data.ngayRutHoSo);
                            $("input[name=xangLuuKho]").val(response.data.xangLuuKho);
                            $("input[name=giaTriVay]").val(response.data.giaTriVay);
                            $("input[name=laiSuatVay]").val(response.data.laiSuatVay);
                            $("input[name=hoaHongSale]").val(response.hoaHongSale); 
                            $("input[name=giavonbh]").val(response.data.giavonbh); 
                            $("input[name=giavonpk]").val(response.data.giavonpk); 
                            $("input[name=hhcongdk]").val(response.data.hhcongdk); 
                            if (response.data.ghiChu)
                                $("select[name=ghiChu]").val(response.data.ghiChu);
                            else
                                $("select[name=ghiChu]").val(0);
                            $("#sxe").text(response.tenXe);
                            $("#smau").text(response.data.color);
                            $("#ssokhung").text(response.data.vin);
                            $("#ssomay").text(response.data.frame);
                            if (response.idHopDong)
                                $("input[name=eidhopdong]").val(response.idHopDong);
                        }

                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
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
                    url: "{{url('management/ketoan/xenhanno/update/')}}",
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

            // $(document).on('click','#inBB', function(){
            //     open("{{url('management/ketoan/hopdong/bienban/')}}/" + $(this).data('id'),"_blank");
            // });
            // $(document).on('click','#inQT', function(){
            //     open("{{url('management/ketoan/hopdong/quyettoan/')}}/" + $(this).data('id'),"_blank");
            // });
        });
    </script>
@endsection
