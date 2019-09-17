@extends('common.default')
@section('title')
    <title>菜单配置</title>
@stop
@section('body')
    <div class="row">
        <div class="col-lg-6">
            @include('superadmin.menu-setting.manual-add-block')
            @include('superadmin.menu-setting.manual-edit-block')
        </div>
        {{-- 详情模块--}}
        <div class="col-lg-3">
            @include('superadmin.menu-setting.menual-detail-block')
        </div>
        {{-- 详情模块--}}
        <div class="col-lg-3">
            @include('superadmin.menu-setting.menual-del-block')
        </div>
    </div>
@stop
@section('endscript')
    @parent
    {{--    <script src="{{ asset('assets/demo/custom/components/base/treeview.js') }}" type="text/javascript"></script>--}}
    <script src="{{ asset('assets/demo/custom/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
    <script>
        var Treeview = function () {
            @yield('demodel-tree')
            @yield('demo1-tree')
                return {
                //main function to initiate the module
                init: function () {
                    @yield('demo1-tree-return')
                    @yield('demodel-tree-return')
                }
            };
        }();
        jQuery(document).ready(function () {
            Treeview.init();
        });
        //== Class definition
        var Select2 = function () {
            //== Private functions
            var demos = function () {
                @yield('add-block-select2')
                @yield('edit-block-select2')
            }
            //== Public functions
            return {
                init: function () {
                    demos();
                }
            };
        }();
    </script>
    @yield('add-block-additional-scripts')
    @yield('edit-block-additional-scripts')
    @yield('demo1-tree-additional-scripts')
    @yield('demodel-tree-additional-scripts')
    @yield('script-temp-start')
    @yield('script-temp-end')
@stop
