@extends('common.default')
@section('title')
    <title>主页面</title>
    @stop
@section('body')
    <?php
    $portlet = <<<portlet
<div class="m-portlet">
        <div class="m-portlet__body">
            <div class="m-pricing-table-1">
                <div class="m-pricing-table-1__items row">
                    ~block~
                </div>
            </div>
        </div>
    </div>
portlet;
    $block = <<<block
<div class="m-pricing-table-1__item col-lg-3">
                        <h2 class="m-pricing-table-1__subtitle">~1-label~</h2>
                        <span class="m-pricing-table-1__description">
												~i-label~
</span>
                    </div>
block;
    //     xdebug_break();
    $i = 0;
    $html = '';
    foreach ($menulists as $key => $value) {
        if ($value['pid'] === 0) {
            $tempBlock = $block;
            $tempBlock = str_replace('~1-label~', $value['label'], $tempBlock);
            if ($i % 4 === 0 || $i === 0) {
                $html .= $portlet;
            }
            if (array_key_exists('child', $value)) {
                $j = 1;
                foreach ($value['child'] as $_key => $_value) {
                    $size = count($value['child']);
                    if ($j >= $size) {
                        $tempBlock = str_replace('~i-label~', $_value['label'] . '<br>', $tempBlock);
                    } else {
                        $tempBlock = str_replace('~i-label~', $_value['label'] . '<br>~i-label~', $tempBlock);
                    }
                    $j++;
                }
                if ($i >= count($menulists) - 1) {
                    $html = str_replace('~block~', $tempBlock, $html);
                } else {
                    if ($i >=3) {
                        $html = str_replace('~block~', $tempBlock, $html);
                    } else {
                        $html = str_replace('~block~', $tempBlock . '~block~', $html);
                    }
                }
            }
        }
        $i++;
    }
    ?>
    <!--begin::Portlet-->
    {!! $html !!}
    <!--end::Portlet-->
@stop
