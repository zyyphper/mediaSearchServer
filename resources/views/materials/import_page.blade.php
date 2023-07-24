<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .jumbotron {
        display: block;
        height: 100%;
        text-align: center;
        background: #FFFFFF;
        padding: 20px 0;
    }
    #data-check-block{
        padding: 0 10px;
    }
    table, th {
        background-color: #FFFFFF;
        text-align: center;
    }

    #data-check-block, #line-tpl, #empty-import-btn {
        display: none;
    }

    #import-check {
        font-weight: bold;
        text-align: center;
    }

    #import-check .alert {
        height: 3rem;
        line-height: 3rem;
        padding: 0;
    }

    #refresh-btn, #true-import-btn, #empty-import-btn {
        height: 3rem;
        line-height: 3rem;
        padding: 0 1rem;
        float: right;
        margin-right: 0.3rem;
    }
</style>

<section class="content">
    <div class="row">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">上传</h3>

                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{url('admin/media_resource')}}"
                           class="btn btn-sm btn-default" title="媒资列表"><i class="fa fa-list"></i><span
                                class="hidden-xs">&nbsp;媒资列表</span></a>
                    </div>
                </div>
            </div>
            <div class="jumbotron">
                <div id="input-select-block">
                    <button class="btn btn-primary btn-lg" role="button" id="upload-btn">
                        <i class="fa fa-upload "></i>选择文件
                    </button>
                    <div class="alert alert-secondary" role="alert" style="color: red;">
                        单次导入文件条数为{{$import_line_num}}条
                    </div>
                </div>

                <div id="data-check-block">
                    <div class="row">
                        <div id="import-check" class="col-sm-6">
                            <div class="alert alert-success col-sm-6">正确：<span id="success-count">0</span>条</div>
                            <div class="alert alert-danger col-sm-6">格式错误：<span id="error-count">1</span>条</div>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-warning btn-lg btn-sm" id="true-import-btn">
                                <i class="fa fa-imdb"></i>导入
                            </button>
                            <button class="btn btn-danger btn-lg btn-sm" id="empty-import-btn">
                                <i class="fa fa-reddit"></i>不存在可导入的数据
                            </button>
                            <button class="btn btn-primary btn-lg btn-sm" onclick="location.reload();" id="refresh-btn">
                                <i class="fa fa-recycle"></i>清空
                            </button>
                        </div>
                    </div>
                    <div style="overflow-x:scroll;">
                        <table class="table table-bordered" style="word-break: keep-all;">
                            <thead>
                            <tr id="header-block">
                            </tr>
                            </thead>
                            <tbody id="check-data-body">
                            <tr id="line-tpl">
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- loading 遮罩 - start --}}
                <div class="modal fade" id="loadingModal" backdrop="static" keyboard="false">
                    <div
                        style="width: 200px;height:20px; z-index: 20000; position: absolute; text-align: center; left: 50%; top: 50%;margin-left:-100px;margin-top:-10px">
                        <div class="progress progress-striped active" style="margin-bottom: 0;">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                        <h5>数据加载中...</h5>
                    </div>
                </div>
                {{-- loading 遮罩 - end --}}
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {

        let file = $('<input type="file" accept=".xls,.xlsx"/>');

        $('#upload-btn').click(function () {
            file.click();
        });

        let importData = [];
        // 默认成功/失败条数为0
        let errorLineNum = 0;
        let successLineNum = 0;

        let showFieldArr = [
            'encode_code',
            'original_name',
            'project.id',
            'project.name',
            'project.encode_code',
            'copyright.id',
            'copyright.name',
            'copyright.encode_code',
            'album.id',
            'album.name',
            'album.encode_code',
            'duration_text',
            'file_size',
            'resolution_ratio',
            'file_url',
            'remark',
            'introduction',
        ];

        file.change(function (e) {
            // 选择好文件后，获取选择的内容
            let selectFile = file[0].files[0];
            // 选择好文件后，获取选择的内容
            let formData = new FormData();
            formData.append("file", selectFile);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{$check_url}}",
                data: formData,
                type: "post",
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    $('#loadingModal').modal({backdrop: 'static', keyboard: false});
                },
                success: function (res) {
                    file.val("");
                    $('#loadingModal').modal('hide');
                    if (0 === res.code) {
                        $('#input-select-block').hide();
                        $('#data-check-block').show();
                        let data = res.data;
                        // 标题信息
                        let headerData = data.header_data;
                        $.each(showFieldArr, function (sk, sv) {
                            let headerTd = $('<td></td>').html(headerData[sv]);
                            $('#header-block').append(headerTd);
                        });
                        let excelData = data.excel_data;
                        $.each(excelData, function (ek, ev) {
                            let lineTpl = $('#line-tpl').clone().removeAttr('id');
                            let errorTips = ev['error_tips'];
                            if (!$.isEmptyObject(errorTips)) {
                                lineTpl.css('background-color', '#dd4b3929');
                            }
                            for (let si = 0; si < showFieldArr.length; si++) {
                                let excelShowField = showFieldArr[si];
                                let excelTd = $('<td></td>').html(appendText(ev[excelShowField], excelShowField, errorTips));
                                lineTpl.append(excelTd);
                            }
                            $('#check-data-body').append(lineTpl);
                            if ($.isEmptyObject(errorTips)) {
                                successLineNum++;
                                importData.push(ev);
                            } else {
                                errorLineNum++;
                            }
                        });
                        $('#success-count').text(successLineNum);
                        $('#error-count').text(errorLineNum);
                        if (successLineNum === 0) {
                            $('#empty-import-btn').show();
                            $('#true-import-btn').hide();
                        }
                    } else {
                        $.admin.toastr.error(res.message, '', {
                            positionClass: "toast-top-right"
                        });
                    }
                },
                error: function () {
                    $('#loadingModal').modal('hide');
                }
            });
        });

        // 添加错误提示文本
        function appendText(lineText, errorKey, errorTipsArr) {
            if (lineText == null) {
                lineText = '';
            }
            if (errorTipsArr.hasOwnProperty(errorKey)) {
                if (lineText !== "") {
                    lineText += '<br/>';
                }
                return lineText + '<div style="color:red">' + errorTipsArr[errorKey] + '<\/div>';
            }
            return lineText;
        }

        // 真实数据导入按钮点击
        $('#true-import-btn').click(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{$submit_url}}",
                data: {import_data: JSON.stringify(importData)},
                type: "post",
                dataType: 'json',
                beforeSend: function () {
                    $('#loadingModal').modal({backdrop: 'static', keyboard: false});
                },
                success: function (res) {
                    $('#loadingModal').modal('hide');
                    if (0 === res.code) {
                        $.admin.toastr.success(res.message, '', {
                            positionClass: "toast-top-right"
                        });
                    } else {
                        $.admin.toastr.error(res.message, '', {
                            positionClass: "toast-top-right"
                        });
                    }
                },
                error: function () {
                    $('#loadingModal').modal('hide');
                }
            });
        });
    })
</script>
