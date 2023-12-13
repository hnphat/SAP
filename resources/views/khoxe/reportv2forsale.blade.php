@extends('admin.index')
@section('title')
    Tồn kho V2
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
                        <h1 class="m-0"><strong>Tồn kho v2</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kho xe</li>
                            <li class="breadcrumb-item active">Báo cáo kho v2</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="container-fluid">
            <h5>Tổng tồn kho: <strong class="text-pink">{{$tongStore}}</strong>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Xe chưa có hợp đồng: <strong class="text-secondary">{{$tongStore - $tongHD}}</strong>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Xe đã có hợp đồng: <strong class="text-success">{{$tongHD}}</strong></h5>
            <p>
                @foreach($modelGroup as $row)
                    {{$row->name}}: <strong class="text-pink">{{$row->tongStore}}</strong> @if($row->tongHD !== 0) <strong class="text-success">({{$row->tongHD}} HĐ)</strong> @endif<br/>
                @endforeach
            </p>
            <table id="dataTable" class="display" style="width:100%">
                <thead>
                <tr class="bg-gradient-lightblue">
                    <th>TT</th>
                    <th>Model</th>
                    <th>Đỏ</th>    
                    <th>Xanh</th>
                    <th>Trắng</th>
                    <th>Vàng</th>                                    
                    <th>Ghi</th>
                    <th>Nâu</th>
                    <th>Bạc</th>
                    <th>Xám</th>
                    <th>Xám kim loại</th>
                    <th>Đen</th>
                    <th>Vàng cát</th>
                    <th>Xanh lục bảo</th>
                    <th>Xanh bóng đêm</th>
                </tr>
                </thead>
            </table>
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
        $(document).ready(function() { 
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/hd/kho/tonkho/getreportv2/')  }}",
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
                    { "data": 'name' },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Đỏ") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Đỏ") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }
                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Xanh") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Xanh") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Trắng") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Trắng") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Vàng") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Vàng") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },                                  
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Ghi") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Ghi") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Nâu") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Nâu") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Bạc") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Bạc") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Xám") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Xám") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Xám_kim_loại") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Xám_kim_loại") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Đen") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Đen") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Vàng_cát") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Vàng_cát") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Xanh_lục_bảo") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Xanh_lục_bảo") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            let numStore = 0;
                            let numHD = 0;
                            if (row.store) {                                
                                let store = row.store;
                                for (let i = 0; i < store.length; i++) {
                                    const ele = store[i];
                                    if (ele.mauSac == "Xanh_bóng_đêm") {
                                        numStore++;
                                    }
                                }
                                
                            }
                            if (row.hopdong) {                                
                                let hopdong = row.hopdong;
                                for (let i = 0; i < hopdong.length; i++) {
                                    const ele = hopdong[i];
                                    if (ele.mauSac == "Xanh_bóng_đêm") {
                                        numHD++;
                                    }
                                }
                                
                            }
                            if (numStore == 0 && numHD == 0)
                                return "";
                            else {
                                return `<strong class="text-pink">${numStore + numHD}</strong>&nbsp;${numHD == 0 ? "" : `<strong class="text-success">(${numHD}HĐ)</strong>`}`;
                            }                        } 
                    }
                ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();
        });
    </script>
@endsection
