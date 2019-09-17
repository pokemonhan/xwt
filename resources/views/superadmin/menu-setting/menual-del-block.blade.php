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
                        菜单移除
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="m_tree_1_del" class="tree-demo">
            </div>
            <br>
            <button type="button" class="btn btn-primary btn-block" id="delete-submit" disabled="disabled">提交</button>
        </div>
        <form id="delete-menu-form" method="POST" action="{{ route('web-blade.menu.delete') }}">
            @csrf
            <input type="hidden" value="" name="toDelete" id="toDelete">
        </form>
    </div>

    <!--end::Portlet-->
@section('script-temp-start')
    <script>
            @stop

            @section('demodel-tree')
        var demo1del = function () {
                $('#m_tree_1_del').jstree({
                    'plugins': ["wholerow", "checkbox", "types"],
                    'core': {
                        "themes": {
                            "responsive": false
                        },
                        'data': {!! $treejson !!},
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder m--font-warning"
                        },
                        "file": {
                            "icon": "fa fa-file  m--font-warning"
                        }
                    },
                });

                $('#m_tree_1_del').on("changed.jstree", function (e, data) {
                    console.log(data); // newly selected
                    var ids = data.selected;
                    if (ids !== undefined && ids.length !== 0) {
                        $('#delete-submit').removeAttr('disabled')
                    } else {
                        $('#delete-submit').attr("disabled", "disabled");
                    }
                    $("#toDelete").val(JSON.stringify(ids));
                }).jstree({
                    /*"core": {
                        "animation": 0,
                        "check_callback": true,
                        'force_text': true,
                        "themes": {"stripes": true}
                    },*/
                    "plugins": ["search", "state", "types", "wholerow", "checkbox"]
                });
            }
        @stop
        @section('demodel-tree-return')
        demo1del();
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('demodel-tree-additional-scripts')
    <script>
        $('#delete-submit').click(function (event) {
            var action = $('#delete-menu-form').attr('action');
            event.preventDefault();
            $.ajax({
                url: action,
                type: 'POST',
                data: $('#delete-menu-form').serialize(),
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
