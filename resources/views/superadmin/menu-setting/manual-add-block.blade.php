<!--begin::Portlet-->
<div class="m-portlet m-portlet--tab">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                <h3 class="m-portlet__head-text">
                    添加菜单
                </h3>
            </div>
        </div>
    </div>

    <!--begin::Form-->
    <form class="m-form m-form--fit m-form--label-align-right" id="add-menu-form" method="POST" action="{{ route('web-blade.menu.add') }}">
        @csrf
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <label for="menulabel">菜单名</label>
                <input type="text" name="menulabel" class="form-control m-input m-input--square" id="menulabel" autocomplete="off"
                       placeholder="输入菜单名">
                {{--                    <span class="m-form__help">We'll never share your email with anyone else.</span>--}}
            </div>
            <div class="form-group m-form__group">
                <label class="m-checkbox m-checkbox--state-brand">
                    <input type="checkbox" id="isParent" name="isParent"> 是否父菜单
                    <span></span>
                </label>
            </div>
            <div class="form-group m-form__group add-children-menu-related">
                <label for="route">请选择路由名</label>
                <select class="form-control m-select2" name="route" id="route">
                    @foreach($rname as $rkey => $rvalue)
                        <option value="{{$rkey}}">name: ( {{$rkey}} ) | url:( {{$rvalue}} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group m-form__group add-children-menu-related">
                <label for="parentMenu">请选择父级</label>
                <select class="form-control m-select2" name="parentid" id="parentid">
                    @foreach($firstlevelmenus as $flvalue)
                        <option value="{{$flvalue->id}}">{{$flvalue->label}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions">
                <button type="submit" id="add-submit" class="btn btn-primary">提交</button>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Portlet-->
@section('script-temp-start')
    <script>
        @stop
        @section('add-block-select2')
        $('#parentid, #parentid_validate').select2({
            placeholder: "Select a state"
        });

        $('#route, #route_validate').select2({
            placeholder: "Select a state"
        });
        @stop
        @section('script-temp-end')
    </script>
@stop
@section('add-block-additional-scripts')
    <script>
        $("#isParent").change(function () {
            if (this.checked) {
                $('.add-children-menu-related').hide();
            } else {
                $('.add-children-menu-related').show();
            }
        });
        $('#add-submit').click(function (event) {
            var action = $('#add-menu-form').attr('action');
            event.preventDefault();
            $.ajax({
                url: action,
                type: 'POST',
                data: $('#add-menu-form').serialize(),
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
