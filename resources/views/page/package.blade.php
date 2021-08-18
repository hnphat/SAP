@extends('admin.index')
@section('title')
    Bảo hiểm - phụ kiện
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Bảo hiểm - phụ kiện</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Bảo hiểm - Phụ kiện</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="row container">
{{--                <div class="col-sm-4">--}}
{{--                    <form id="addForm" autocomplete="off">--}}
{{--                        {{csrf_field()}}--}}
{{--                        <div class="card-body">--}}
{{--                            <input type="hidden" name="idObject">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Nội dung</label>--}}
{{--                                <input name="noiDung" type="text" class="form-control" placeholder="Nhập nội dung" autofocus="autofocus">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Giá</label>--}}
{{--                                <input name="gia" type="number" class="form-control" placeholder="Nhập giá (nếu có)">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Hoa hồng</label>--}}
{{--                                <input name="hoaHong" type="number" class="form-control" placeholder="Nhập hoa hồng (nếu có)" >--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Loại</label>--}}
{{--                                <select name="loai" class="form-control">--}}
{{--                                    <option value="free">Phụ kiện - Quà tặng (free)</option>--}}
{{--                                    <option value="pay">Phụ kiện bán (pay)</option>--}}
{{--                                    <option value="cost">Các loại phí (cost)</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <button id="btnAdd" class="btn btn-primary">Thêm mới</button>--}}
{{--                                <button id="btnUpdate" style="display: none;" disabled="disabled" class="btn btn-success">Cập nhật</button>--}}
{{--                                <button id="btnCancel" style="display: none;" disabled="disabled" class="btn btn-secondary">Hủy</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
                <div class="col-sm-12">
                    <table id="dataTable" class="display" style="width:100%">
                        <thead>
                        <tr class="bg-cyan">
                            <th>TT</th>
                            <th>Nội dung</th>
                            <th>Giá</th>
                            <th>Hoa hồng</th>
                            <th>Loại</th>
                            <th>Sale</th>
                            <th>Ngày tạo</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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

        // show data
        $(document).ready(function() {

            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/package/get/list') }}",
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
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "cost", render: $.fn.dataTable.render.number(',','.',0,'')},
                    { "data": "profit", render: $.fn.dataTable.render.number(',','.',0,'') },
                    { "data": "type"},
                    { "data": "surname"},
                    { "data": "created_at"}
                    // {
                    //     "data": null,
                    //     render: function(data, type, row) {
                    //         return "<button id='btnEdit' data-id='"+row.id+"' data-toggle='modal' data-target='#edit' class='btn btn-success btn-sm'><span class='far fa-edit'></span></button>&nbsp;";
                    //     }
                    // },
                    // {
                    //     "data": null,
                    //     render: function(data, type, row) {
                    //         return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>&nbsp;";
                    //     }
                    // }
                ]
            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();

            // Add data
            $("#btnAdd").click(function(e){
               e.preventDefault();
               $.ajax({
                  url: "{{url('management/package/add/')}}",
                  type: "post",
                  dataType: 'json',
                  data: $("#addForm").serialize(),
                  success: function(response) {
                      $("#addForm")[0].reset();
                      Toast.fire({
                          icon: 'success',
                          title: " Đã thêm " + response.noidung
                      })
                      table.ajax.reload();
                      $('input[name=noiDung]').focus();
                  },
                  error: function() {
                      Toast.fire({
                          icon: 'warning',
                          title: "Lỗi nhập liệu; Lỗi xử lý dữ liệu"
                      })
                  }
               });
            });

            //Delete data
            $(document).on('click','#delete', function(){
               if(confirm('Bạn có chắc muốn xóa?')) {
                   $.ajax({
                      url: "{{url('management/package/delete/')}}",
                      type: "post",
                      dataType: "json",
                      data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                      },
                      success: function(response) {
                          Toast.fire({
                              icon: 'success',
                              title: "Đã xóa"
                          })
                          table.ajax.reload();
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

            // edit data
            $(document).on('click','#btnEdit', function(){
                $("#btnAdd").hide();
                $("#btnAdd").prop("disabled", true);
                $("#btnUpdate").show();
                $("#btnUpdate").prop("disabled", false);
                $("#btnCancel").show();
                $("#btnCancel").prop("disabled", false);
                $.ajax({
                   url: "{{url('management/package/edit/show/')}}",
                   type: "post",
                   dataType: "json",
                   data: {
                       "_token": "{{csrf_token()}}",
                       "id": $(this).data('id')
                   },
                   success: function(response) {
                       console.log(response);
                       $("input[name=idObject]").val(response.data.id);
                       $("input[name=noiDung]").val(response.data.name);
                       $("input[name=gia]").val(response.data.cost);
                       $("input[name=hoaHong]").val(response.data.profit);
                       $("select[name=loai]").val(response.data.type);
                   },
                   error: function(){
                       Toast.fire({
                           icon: 'warning',
                           title: "Error 500!"
                       })
                   }
                });
            });

            $("#btnCancel").click(function(){
                $("#btnAdd").show();
                $("#btnAdd").prop("disabled", false);
                $("#btnUpdate").hide();
                $("#btnUpdate").prop("disabled", true);
                $(this).hide();
                $(this).prop("disabled", true);
                $("#addForm")[0].reset();
                $("input[name=noiDung]").focus();
            });

            $("#btnUpdate").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/package/update/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã cập nhật!"
                        })
                        $("#btnAdd").show();
                        $("#btnAdd").prop("disabled", false);
                        $("#btnUpdate").hide();
                        $("#btnUpdate").prop("disabled", true);
                        $("#btnCancel").hide();
                        $("#btnCancel").prop("disabled", true);
                        table.ajax.reload();
                        $('input[name=noiDung]').focus();
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
