<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i
        class="la la-close"></i></button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

    <!-- BEGIN: Aside Menu -->
    <?php
    $parentItems = <<<parentitem
<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a
                    href="javascript:;" class="m-menu__link m-menu__toggle"><i
                        class="m-menu__link-icon flaticon-layers"></i><span
                        class="m-menu__link-text">~plabel~</span><i
                        class="m-menu__ver-arrow la la-angle-right"></i></a>
~inner~
</li>
parentitem;
    $innerItems = <<<inner
 <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span
                                class="m-menu__link"><span class="m-menu__link-text">~plabel~</span></span></li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="~iroute~" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                    class="m-menu__link-text">~ilabel~</span></a></li>
                    </ul>
                </div>
inner;
    //Generate menus
    $menuItems = $tempParentItems = $tempInnerItems = '';
    foreach ($menulists as $value) {
        if ($value['pid'] === 0) {
            $tempParentItems = $tempParentItems === '' ? $parentItems : $tempParentItems . $parentItems;
            $tempParentItems = str_replace('~plabel~', $value['label'], $tempParentItems);
            if (array_key_exists('child', $value)) {
                foreach ($value['child'] as $_value) {
                    $tempInnerItems = $tempInnerItems === '' ? $innerItems : $tempInnerItems . $innerItems;
                    $tempInnerItems = str_replace(array('~plabel~', '~ilabel~'),
                        array($value['label'], $_value['label']), $tempInnerItems);
                    $tempInnerItems = $_value['route'] === '#' ? str_replace('~iroute~', '#', $tempInnerItems) : str_replace('~iroute~', $_value['route'], $tempInnerItems);
                }
            }
            $tempMenuItems = str_replace('~inner~', $tempInnerItems, $tempParentItems);
            $menuItems = $menuItems === '' ? $tempMenuItems : $menuItems . $tempMenuItems;
            $tempMenuItems = $tempParentItems = $tempInnerItems = '';
        }
    }
    ?>
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
         m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            {!! $menuItems !!}
        </ul>
    </div>

    <!-- END: Aside Menu -->
</div>

<!-- END: Left Aside -->
