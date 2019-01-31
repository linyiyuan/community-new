<!-- 菜单 -->
<ul class="sidebar-nav">
    <li class="sidebar-nav-link">
        <a href="{{ url('/index') }}" class="{{strpos(Request::getPathInfo(),'/index') !== false?'active':'' }}">
            <i class="am-icon-home sidebar-nav-link-logo"></i> 首页
        </a>
    </li>
    @if($menu)
       @foreach($menu as $k => $v)
            <li class="sidebar-nav-link">
                <a href="javascript:;" class="sidebar-nav-sub-title {{strpos(Request::getPathInfo(),$v['url']) !== false?'active':'' }}">
                    <i class="{{ $v['icon']}} sidebar-nav-link-logo"></i> {{ $v['type']}}
                    <span class="am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
                </a>
                <ul class="sidebar-nav sidebar-nav-sub" style="{{strpos(Request::getPathInfo(),$v['url']) !== false?'display: block;' :'' }}">
                    @foreach($v['data'] as $key => $val)
                    <li class="sidebar-nav-link">
                        <a href="{{ url($val['url']) }}"  class="{{strpos(Request::getPathInfo(),$val['url']) !== false?'sub-active' :'' }} list">
                            <span class="am-icon-angle-right sidebar-nav-link-logo"></span> {{ $val['type'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    @endif
</ul>
<script>

</script>