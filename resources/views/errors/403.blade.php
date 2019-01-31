@extends('admin.common.common')

@section('title')
403页面
@stop

@section('content')
 <div class="row-content am-cf">
        <div class="widget am-cf">
            <div class="widget-body">
                <div class="tpl-page-state">
                    <div class="tpl-page-state-title am-text-center tpl-error-title">404</div>
                    <div class="tpl-error-title-info">Page Not Found</div>
                    <div class="tpl-page-state-content tpl-error-content">

                        <p>对不起,没有找到您所需要的页面,可能是URL不确定,您的权限不够,或者页面已被移除。</p>
                        <a class="am-btn am-btn-secondary am-radius tpl-error-btn" href="/admin/index">Back Home</a></div>

                </div>
            </div>
        </div>
     </div>
@stop