<!--begin::Portlet-->
<div class="m-portlet m-portlet--tab">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                <h3 class="m-portlet__head-text">
                    编辑菜单
                </h3>
            </div>
        </div>
    </div>
    <!--begin::Form-->
    <form class="m-form m-form--fit m-form--label-align-right" id="edit-menu-form" method="POST" action="{{ route('web-blade.menu.edit') }}">
        @csrf
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <label for="menuid">请选菜单编辑</label>
                <select class="form-control m-select2" name="menuid" id="menuid">
                    @foreach($editMenu as $editMenuV)
                        <option value="{{$editMenuV->id}}"
                                parent-id="{{$editMenuV->pid}}">{{$editMenuV->label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group m-form__group">
                <label for="emenulabel">菜单名</label>
                <input type="text" name="emenulabel" class="form-control m-input m-input--square" id="emenulabel" autocomplete="off"
                       placeholder="输入菜单名">
            </div>
            <div class="form-group m-form__group">
                <label class="m-checkbox m-checkbox--state-brand">
                    <input type="checkbox" id="eisParent" name="eisParent" checked="checked"> 勾住为父级菜单，是否设置为父菜单
                    <span></span>
                </label>
            </div>
            <div class="form-group m-form__group edit-children-menu-related">
                <label for="eroute">请选择路由名</label>
                <select class="form-control m-select2" name="eroute" id="eroute">
                    @foreach($rname as $rkey => $rvalue)
                        <option value="{{$rkey}}"
                                menu-id="@if (array_key_exists($rkey,$routeMatchingName)){{$routeMatchingName[$rkey]['id']}}@else#@endif">
                            name: ( {{$rkey}} ) | url:( {{$rvalue}} )
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group m-form__group edit-children-menu-related">
                <label for="eparentMenu">请选择父级</label>
                <select class="form-control m-select2" name="eparentid" id="eparentid">
                    @foreach($firstlevelmenus as $flvalue)
                        <option value="{{$flvalue->id}}">{{$flvalue->label}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions">
                <button type="submit" id="edit-submit" class="btn btn-primary">提交</button>
            </div>
        </div>
    </form>

    <!--end::Form-->
</div>
<!--end::Portlet-->
@section('script-temp-start')
    <script>
        @stop
        @section('edit-block-select2')
        $('#eparentid, #eparentid_validate').select2({
            placeholder: "请选择父级"
        });

        $('#eroute, #eroute_validate').select2({
            placeholder: "请选择路由"
        });

        $('#menuid, #menuid_validate').select2({
            placeholder: "请选择编辑的菜单"
        });
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('script-temp-start')
@overwrite
@section('script-temp-end')
@overwrite
@section('edit-block-additional-scripts')
    <script>
        $('.edit-children-menu-related').ready(function() {
            if ($('#eisParent')[0].checked) {
                        $('.edit-children-menu-related').hide();
                    }
        });
        $("#eisParent").change(function () {
            if (this.checked) {
                $('.edit-children-menu-related').hide();
            } else {
                $('.edit-children-menu-related').show();
            }
        });
        $('#menuid').on('select2:select', function (e) {
            // var data = e.params.data;
            // console.log(e);
            var data = e.params.data;
            datas = {};
            datas['id'] = data.id;
            datas['text'] = data.text;
            datas['pid'] = data['element']['attributes']['parent-id']['nodeValue'];
            console.log(datas['pid']);
            if (datas['pid'] === '0') {
                if (!$('eisParent').is(':checked')) {
                    $('#eisParent').attr('checked', true);
                    $('#eisParent')[0].checked=true;
                }
                $('.edit-children-menu-related').hide();
            } else {
                $('#eisParent').removeAttr('checked');
                $('#eisParent')[0].checked=false;
                $('.edit-children-menu-related').show();
            }
            try {
                var robj = $("#eroute").select2().find("[menu-id='" + datas['id'] + "']");
                nodevalue = robj[0]['attributes']['menu-id']['nodeValue'];
                optionvalue = robj[0]['value'];
                console.log(optionvalue);
                $('#eroute').val(optionvalue);
                $('#eroute').trigger('change');
            } catch (e) {
                console.log(e);
            }
            $('#eparentid').val(datas['pid']); // Select the option with a value of '1'
            $('#eparentid').trigger('change'); // Notify any JS components that the value changed
        });

        $('#edit-submit').click(function (event) {
            var action = $('#edit-menu-form').attr('action');
            event.preventDefault();
            $.ajax({
                url: action,
                type: 'POST',
                data: $('#edit-menu-form').serialize(),
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
