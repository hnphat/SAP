@extends('admin.index')
@section('title')
    Xe hợp đồng
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
                        <h1 class="m-0"><strong>Xe hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kho xe</li>
                            <li class="breadcrumb-item active">Xe hợp đồng</li>
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
                                    <strong>Xe hợp đồng</strong>
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
                                        <th>TPKD</th>
                                        <th>Trạng thái</th>
                                        <th>Sale bán</th>
                                        <th>Khách hàng</th>
                                        <th>Ngày giao xe</th>
                                        <th>Tác vụ</th>
                                        <th>IN</th>
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
                                <div class="card-body row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Ngày giao xe</label>
                                            <input min="<?php echo Date('Y-m-d');?>" name="ngayGiaoXe" placeholder="Ngày giao xe" type="date" class="form-control">
                                        </div>    
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-danger">Trạng thái</label>
                                            <select name="xuatXe" class="form-control">
                                                <option value="0">Chưa xuất</option>
                                                <option value="1">Xuất xe</option>
                                            </select>
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

        // show data
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
                ajax: "{{ url('management/kho/getkhohd/list') }}",
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
                    { "data": null,
                        render: function(data, type, row) {
                            if (row.tpkd == false)
                                return "<strong class='text-danger'>Chưa duyệt</strong>";
                            else
                                return "<strong class='text-success'>Đã duyệt</strong>";
                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            if (row.xuatXe == false)
                                return "<strong class='text-danger'>Chưa xuất</strong>";
                            else
                                return "<strong class='text-success'>Đã xuất</strong>";
                        } 
                    },
                    { "data": "saleban" },
                    { "data": "khach" },
                    { "data": "ngayGiaoXe" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.xuatXe == true)
                                return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;";
                            else
                                return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#editModal' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;&nbsp;";     
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.xuatXe == true)
                                return "<button id='inBB' data-id='"+row.idhopdong+"' class='btn btn-primary btn-sm'>InBB</button>";                        
                            else
                                return "<button id='inQT' data-id='"+row.idhopdong+"' class='btn btn-info btn-sm'>InQT</button>";                        
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
                    url: "{{url('management/kho/getkho/edit/show/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        $("input[name=eid]").val(response.data.id);
                        $("select[name=xuatXe]").val(response.data.xuatXe);
                        $("input[name=ngayGiaoXe]").val(response.data.ngayGiaoXe);
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
                    url: "{{url('management/kho/getkho/updatehd/')}}",
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

            $(document).on('click','#inBB', function(){
                open("{{url('management/ketoan/hopdong/bienban/')}}/" + $(this).data('id'),"_blank");
            });
            $(document).on('click','#inQT', function(){
                open("{{url('management/ketoan/hopdong/quyettoan/')}}/" + $(this).data('id'),"_blank");
            });
        });
    </script>
@endsection
