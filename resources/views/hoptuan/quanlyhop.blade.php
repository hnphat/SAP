@extends('admin.index')
@section('title')
    Quản lý cuộc họp
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
                        <h1 class="m-0"><strong>Quản lý cuộc họp</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Quản lý họp</li>
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
                                    <strong>Quản lý cuộc họp</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <button class="btn btn-success" data-toggle="modal" data-target="#addModal"> Thêm mới </button><br/><br/>
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Ngày</th>
                                        <th>Tên cuộc họp</th>
                                        <th>Chi tiết</th>
                                        <th>Thành viên</th>
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
                            <h3 class="card-title">THÊM CUỘC HỌP</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addForm" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên cuộc họp:</label>                                                        
                                    <input placeholder="Tên cuộc họp" type="text" name="tenCuocHop" class="form-control"/>
                                </div>     
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnAdd" class="btn btn-primary">GỬI</button>
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

    <!-- Medal Sửa -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">SỬA NỘI DUNG CUỘC HỌP</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <input type="hidden" name="idCuocHop">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên cuộc họp:</label>                                                        
                                    <input placeholder="Tên cuộc họp" type="text" name="etenCuocHop" class="form-control"/>
                                </div>     
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnEdit" class="btn btn-info">CẬP NHẬT</button>
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


     <!-- Medal Thêm thành viên -->
     <div class="modal fade" id="addModalMem">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">THÀNH VIÊN CUỘC HỌP</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addFormMem" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">                                
                                <input type="hidden" name="idHop">
                                <div class="form-group">
                                    <h4>Nội dung: <strong id="noiDungHop"></strong></h4>
                                </div>
                                <hr>
                                <label>Thành viên họp:</label>     
                                <h5 id="memShow">
                                </h5>
                                <div class="form-group">
                                    <label>Bổ sung:</label>    
                                    <select name="thanhVien" id="thanhVien" class="form-control">
                                        @foreach($user as $row)
                                            <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                        @endforeach
                                    </select>                                                   
                                </div>     
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnAddMem" class="btn btn-primary">BỔ SUNG</button>
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
                ajax: "{{ url('management/cuochop/quanlyhop/getlist') }}",
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
                    { "data": null,
                        render: function(data, type, row) {
                            return row.ngay + "/" + row.thang + "/" + row.nam;
                        } 
                    },
                    { "data": "tenCuocHop" },
                    { "data": null,
                        render: function(data, type, row) {
                            return `<a target="_blank" class="btn btn-secondary btn-sm" href="{{url('management/cuochop/quanlyhop/morong/')}}/${row.id}">Mở rộng</a>`;
                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            return "<button id='themMember' data-id='"+row.id+"' class='btn btn-success btn-sm' data-toggle='modal' data-target='#addModalMem'>Thành viên</button>";
                        } 
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "&nbsp;<button id='del' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Xoá</button>" +
                            "&nbsp;<button id='edit' data-id='"+row.id+"' class='btn btn-info btn-sm' data-toggle='modal' data-target='#editModal'>Sửa</button>";                        
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

            function loadMem(ids) {
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/loadmember/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": ids
                    },
                    success: function(response) {
                        // Toast.fire({
                        //     icon: response.type,
                        //     title: response.message
                        // })
                        $("input[name=idHop]").val(response.idHop);
                        $("#noiDungHop").text((response.data[0]) ? response.data[0].tenCuocHop: "");
                        $("#memShow").empty();
                        let txt = ``;
                        if (response.code == 200) {
                            let arr = response.data;
                            arr.forEach(function(x){
                                txt += `<span>${x.surname} <button id="deleteMem" data-idhop="${x.id_hop}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                            });
                            $("#memShow").append(txt);
                        }
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            }

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

            // delete data
            $(document).on('click','#del', function(){
                if(confirm("Bạn có chắc muốn xoá?")) {
                    $.ajax({
                        url: "{{url('management/cuochop/quanlyhop/delete/')}}",
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
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                }
            });

            // delete member
            $(document).on('click','#deleteMem', function(e){
                e.preventDefault();
                if(confirm("Bạn có chắc muốn xoá?")) {
                    $.ajax({
                        url: "{{url('management/cuochop/quanlyhop/deletemem/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "idhop": $(this).data('idhop'),
                            "iduser": $(this).data('iduser')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            setTimeout(() => {
                                loadMem($("input[name=idHop]").val());
                            }, 1000); 
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                }
            });

            // load thanh vien
            $(document).on('click','#themMember', function(){
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/loadmember/')}}",
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
                        $("input[name=idHop]").val(response.idHop);
                        $("#noiDungHop").text((response.data[0]) ? response.data[0].tenCuocHop: "");
                        $("#memShow").empty();
                        let txt = ``;
                        if (response.code == 200) {
                            let arr = response.data;
                            arr.forEach(function(x){
                                txt += `<span>${x.surname} <button id="deleteMem" data-idhop="${x.id_hop}" data-iduser="${x.id_user}" class="btn btn-danger btn-sm">x</button></span><br/>`;
                            });
                            $("#memShow").append(txt);
                        }

                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            // load edit cuộc họp
            $(document).on('click','#edit', function(){
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/loadedit/')}}",
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
                        $("input[name=idCuocHop]").val(response.data.id);
                        $("input[name=etenCuocHop]").val(response.data.tenCuocHop);
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/post/')}}",
                    type: "post",
                    dataType: "json",
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
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#btnEdit").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/postedit/')}}",
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

            $("#btnAddMem").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/cuochop/quanlyhop/postmem/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addFormMem").serialize(),
                    success: function(response) {
                        $("#addFormMem")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        setTimeout(() => {
                            loadMem($("input[name=idHop]").val());
                        }, 1000);                       
                        table.ajax.reload();
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
    </script>
@endsection
