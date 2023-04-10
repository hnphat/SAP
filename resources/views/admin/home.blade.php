@extends('admin.index')
@section('title')
    Quản lý hồ sơ
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <style>
      #phaohoa {
        position: absolute;
        top: 10%;
        left: 20%;
      }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">           
            <img src="{{$data['hinhNen']}}" alt="background" style="width: 100%; height:auto;">
        </div>
        <!-- /.content -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content {{$data['mauThongBao']}}">
            <div class="modal-header">
              <h4 class="modal-title">THÔNG BÁO</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <img id="phaohoa" src="{{asset('images/temp/phaohoa.gif')}}" alt="GIF">
              <p>{{$data['thongBao']}}</p>
            </div>
            <div class="modal-footer justify-content-right">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
    <script src="plugins/toastr/toastr.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function(){     
            @if($data['loaiThongBao'])     
                $(document).Toasts('create', {
                    class: "{{$data['mauThongBao']}}",
                    body: "{{$data['thongBao']}}",
                    title: 'THÔNG BÁO',
                    subtitle: 'Quan trọng',
                    icon: 'fas fa-envelope fa-lg',
                })         
            @else
                $("#modal-lg").modal('show');
            @endif  
        });        
    </script>
@endsection
