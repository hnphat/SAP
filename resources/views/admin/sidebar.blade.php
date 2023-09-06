<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
        <div id="real_work" style="display: none;">
        <h5>CÔNG VIỆC</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('work.get')}}" class="left">
                    Công việc mới &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_newWork"></span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="{{route('working.list')}}" class="left">
                    Đang thực hiện &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-warning" id="r_working"></span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="{{route('work.push')}}" class="left">
                    Xác nhận công việc &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_checkWork"></span>
                </a>
            </p>
        </div>
        <div id="real_xedemo" style="display: none;">
            <h5>XE DEMO</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('laithu.duyet')}}" class="left">
                    Chờ phê duyệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_duyet"></span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="{{route('laithu.duyet.pay')}}" class="left">
                    Chờ duyệt trả &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_tra"></span>
                </a>
            </p>
        </div>
        <div id="real_xedemo_tbp" style="display: none;">
            <h5>XE DEMO TBP</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('laithu.tbp.duyet')}}" class="left">
                    Chờ phê duyệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_duyet_tbp"></span>
                </a>
            </p>
        </div>
        <div id="real_capxang" style="display: none;">
            <h5>CẤP XĂNG</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('capxang.duyet')}}" class="left">
                    Chờ duyệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_capXang"></span>
                </a>
            </p>
        </div>
        <div id="real_capxang_lead" style="display: none;">
            <h5>CẤP XĂNG TBP</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('capxang.duyet')}}" class="left">
                    Chờ duyệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_capXang_lead"></span>
                </a>
            </p>
        </div>
        <div id="real_hd" style="display: none;">
            <h5>HỢP ĐỒNG</h5>
            <p>
                <a style="text-decoration: none;" href="#" class="left">
                    Đề nghị hợp đồng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger">5</span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="#" class="left">
                    Đề nghị hủy &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger">5</span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="#" class="left">
                    Hợp đồng chờ duyệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger">5</span>
                </a>
            </p>
        </div>
        <div id="real_duyet" style="display: none;">
            <h5>PHÉP/TĂNG CA</h5>
            <p>
                <a style="text-decoration: none;" href="{{route('quanlypheduyet.panel')}}" class="left">
                    Phép &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_duyet_phep"></span>
                </a>
            </p>
            <p>
                <a style="text-decoration: none;" href="{{route('quanlypheduyettangca.panel')}}" class="left">
                    Tăng ca &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="badge badge-danger" id="r_duyet_tangca"></span>
                </a>
            </p>
        </div>
    </div>
</aside>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    $(document).ready(function() {
        let es = new EventSource("{{route('action')}}");
        es.onmessage = function(e) {
            // console.log(e.data);
            let fullData = JSON.parse(e.data);
            if (fullData.s_duyet_phep == 1 && fullData.s_duyet_tangca == 1)
                $('#real_duyet').show();
            else
                $('#real_duyet').hide();
            if (fullData.s_work == 1)
                $('#real_work').show();
            else
                $('#real_work').hide();
            if (fullData.s_xedemo == 1)
                $('#real_xedemo').show();
            else
                $('#real_xedemo').hide();

            if (fullData.s_xang == 1)
                $('#real_capxang').show();
            else
                $('#real_capxang').hide();

            if (fullData.s_xang_lead == 1)
                $('#real_capxang_lead').show();
            else
                $('#real_capxang_lead').hide();

            if (fullData.s_duyet_tbp == 1)
                $('#real_xedemo_tbp').show();
            else
                $('#real_xedemo_tbp').hide();

            if (fullData.phep != 0)
                $('#r_duyet_phep').text(fullData.phep);
            else
                $('#r_duyet_phep').text("");
            if (fullData.tangCa != 0)
                $('#r_duyet_tangca').text(fullData.tangCa);
            else
                $('#r_duyet_tangca').text("");
            if (fullData.newWork != 0)
                $('#r_newWork').text(fullData.newWork);
            else
                $('#r_newWork').text("");
            if (fullData.working != 0)
                $('#r_working').text(fullData.working);
            else
                $('#r_working').text("");
            if (fullData.checkWork != 0)
                $('#r_checkWork').text(fullData.checkWork);
            else
                $('#r_checkWork').text("");
            if (fullData.duyet != 0)
                $('#r_duyet').text(fullData.duyet);
            else
                $('#r_duyet').text("");
            if (fullData.duyettbp != 0)
                $('#r_duyet_tbp').text(fullData.duyettbp);
            else
                $('#r_duyet_tbp').text("");
            if (fullData.tra != 0)
                $('#r_tra').text(fullData.tra);
            else
                $('#r_tra').text("");
            if (fullData.duyetXang != 0)
                $('#r_capXang').text(fullData.duyetXang);
            else
                $('#r_capXang').text("");
            if (fullData.duyetXangLead != 0)
                $('#r_capXang_lead').text(fullData.duyetXangLead);
            else
                $('#r_capXang_lead').text("");
            if (fullData.total_full != 0)
                $('#r_total_full').text(fullData.total_full);
            else
                $('#r_total_full').text("");
        }
    });
</script>
