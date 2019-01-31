@extends('admin.common.common')

@section('title')
管理员列表
@stop

@section('content')
		<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf">文章列表</div>


                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('admin/user/create') }}" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加新用户</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                                    <div class="am-form-group tpl-table-list-select">
                                    </div>
                                </div>
                                <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                     <form action="{{ url('admin/user')}}" method="get">
                                    <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                       
                                            <input type="text" class="am-form-field" placeholder='请输入要搜索的用户名' name="name">
                                            <span class="am-input-group-btn">
                                                <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                       
                                            </span>
                                    </div>
                                     </form>
                                </div>

                                <div class="am-u-sm-12 am-scrollable-horizontal">
                                    <table width="100%" class="am-text-nowrap am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>用户名</th>
                                                <th>用户邮箱</th>
                                                <th>角色</th>
                                                <th>创建时间</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(empty($list))
                                                <tr class="gradeX">
                                                     <td colspan="8">暂无数据</td>
                                                </tr>
                                            @else
                                                @foreach($list as $key)
                                                    <tr class="gradeX">
                                                        <td>{{ $key->id}}</td>
                                                        <td>{{ $key->name}}</td>
                                                        <td>{{ $key->email}}</td>
                                                        <td>
                                                            @foreach($key->roles as $role)
                                                                    <span class="am-badge am-badge-success am-radius">
                                                                        {{ $role->display_name }}
                                                                    </span>
                                                            @endforeach
                                                        </td>
                                                        <td style="width:250px">{{ $key->created_at}}</td>
                                                        <td>
                                                            <div class="tpl-table-black-operation">
                                                                <a href="{{ url('admin/user').'/'.$key->id.'/edit' }}">
                                                                    <i class="am-icon-pencil"></i> 编辑
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <!-- more data -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="am-u-lg-12 am-cf">
                                    <div class="am-fr">
                                        {{ $list->appends(['name' => $name ])->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('javascript')
<script>
       
</script>
    
@stop