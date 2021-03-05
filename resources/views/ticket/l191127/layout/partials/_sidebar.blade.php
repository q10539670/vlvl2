<ul class="sidebar-menu" data-widget="tree">
    <li class="header">菜单</li>
    @foreach($menus as $menu)
        <li class="{{ (isset($menu['submenu'])&&count($menu['submenu']))? 'treeview':'' }} {{in_array($menu['label'], $parrentLabelNameArr)?'menu-open':''}} {{$menu['route_name'] ==$currentRouteName?'active':''}}">
            <a href="{{ empty($menu['route_name'])?'javascript:void(0)':route($menu['route_name'])}}">
                <i class="fa {{ empty($menu['icon'])?'fa-link':$menu['icon']}}"></i>
                <span>{{$menu['label']}}</span>
                @if(isset($menu['submenu']) && count($menu['submenu']))
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                @endif
            </a>
            @if(isset($menu['submenu'])&&count($menu['submenu']))
                <ul class="treeview-menu"
                    style="display:{{in_array($menu['label'], $parrentLabelNameArr)?'block':'none'}}">
                    @foreach($menu['submenu'] as $menu)
                        <li class="{{ (isset($menu['submenu'])&&count($menu['submenu']))? 'treeview':'' }}  {{$menu['route_name'] ==$currentRouteName?'active':''}}">
                            <a href="{{ empty($menu['route_name'])?'javascript:void(0)':route($menu['route_name'])}}">
                                <i class="fa fa-link"></i>
                                <span>{{$menu['label']}}</span>
                                @if(isset($menu['submenu'])&&count($menu['submenu']))
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>