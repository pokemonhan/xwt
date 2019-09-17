<?php
$state = [
    'id' => '',
    'text' => '',
    'nodeId' => '',
    'children' => [],
    'state' => ['opened' => true]
];
$arrTree = [];
foreach ($menulists as $key => $value) {
    if ($value['pid'] === 0) {
        $temp = $state;
        $temp['id'] = $key;
        $temp['text'] = $value['label'];
//        $arrTree[]['text'] = ;
        if (array_key_exists('child', $value)) {
            foreach ($value['child'] as $_key => $_value) {
                $tempChild['text'] = $_value['label'];
                $tempChild['id'] = $_key;
                $temp['children'][] = $tempChild;
            }
        }
        $arrTree[] = $temp;
    }
}
$treejson = json_encode($arrTree, JSON_UNESCAPED_UNICODE);
?>

<!--begin::Portlet-->
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    菜单详情与拖拽排序
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div id="m_tree_1" class="tree-demo">
        </div>
        <br>
        <button type="submit" class="btn btn-primary btn-block" id="detail-submit" disabled="disabled">提交</button>
    </div>
    <form id="detail-menu-form" method="POST" action="{{ route('web-blade.menu.changeParent') }}">
        @csrf
        <input type="hidden" name="dragResult" value="" id="dragResult">
    </form>
</div>

<!--end::Portlet-->
@section('script-temp-start')
    <script>
            @stop
            @section('demo1-tree')
        var demo1 = function () {
                $("#m_tree_1").jstree({
                    "core": {
                        "themes": {
                            "responsive": false
                        },
                        // so that create works
                        "check_callback": true,
                        'data': {!! $treejson !!},
                    },
                    "types": {
                        "#": {
                            "max_depth": 2,
                        },
                        "default": {
                            "icon": "fa fa-folder m--font-success"
                        },
                        "file": {
                            "icon": "fa fa-file  m--font-success"
                        }
                    },
                    "state": {"key": "demo2"},
                    "plugins": ["dnd", "state", "types"]
                });

                $("#m_tree_1")
                    .on('move_node.jstree', function (e, data) {
                        console.info(data);
                        var dragResult = $("#dragResult").val();
                        console.log(dragResult);
                        if (dragResult != "") {
                            datas = jQuery.parseJSON(dragResult);
                        } else {
                            datas = {};
                        }
                        currentId = data.node.id;
                        oldParent = data.old_parent;
                        currentParent = data.parent;
                        currentText = data.node.text;
                        datas[currentId] = {};
                        datas[currentId]['currentId'] = currentId;
                        datas[currentId]['oldParent'] = oldParent;
                        datas[currentId]['currentParent'] = currentParent;
                        datas[currentId]['currentText'] = currentText;
                        $("#dragResult").val(JSON.stringify(datas));
                        $('#detail-submit').removeAttr('disabled')
                        console.log(datas);
                    })
            }
        @stop
        @section('demo1-tree-return')
        demo1();
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('demo1-tree-additional-scripts')
    <script>
        $('#detail-submit').click(function (event) {
            var action = $('#detail-menu-form').attr('action');
            event.preventDefault();
            $.ajax({
                url: action,
                type: 'POST',
                data: $('#detail-menu-form').serialize(),
            })
                .done(function () {
                    console.log("success");
                    location.reload();
                })
                .fail(function () {
                    console.log("error");
                });
        });
    </script>
@stop
@section('script-temp-start')
@overwrite
@section('script-temp-end')
@overwrite
