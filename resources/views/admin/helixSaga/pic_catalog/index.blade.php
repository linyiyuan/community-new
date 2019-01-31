@extends('admin.common.common')

@section('title')
图片路径管理
@stop

@section('content')
<div class="row-content am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title  am-cf"  style="float:left">图片路径列表</div>
                    </div>
                    <div class="widget-body  am-fr">

                        <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                            <div class="am-form-group">
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                      <a class="am-btn am-btn-success" href="{{ url('/helix_saga/pic_catalog/create')}}">添加图片路径</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-sm-12 am-scrollable-horizontal">
                            <table width="100%" class="am-text-nowrap am-table am-table-compact am-table-striped tpl-table-black am-table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>目录名</th>
                                        <th>目录描述</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($list as $k)
                                        <tr class="gradeX">
                                            <td>{{ $k->id }}</td>
                                            <td class="am-text-middle">{{ $k->path }}</td>
                                            <td class="am-text-middle">
                                            	{{$k->desc}}
                                            </td>
                                            <td class="am-text-middle">{{ $k->created_at }}</td>
                                            <td class="am-text-middle">{{ $k->updated_at }}</td>
                                            <td class="am-text-middle">
                                                 <div class="tpl-table-black-operation">
	                                                        <a href="{{ url('helix_saga/pic_catalog/'.$k->id.'/edit') }}">
	                                                            <i class="am-icon-pencil"></i>编辑
	                                                        </a>
                                                        </a>
	                                             </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- more data -->
                                </tbody>
                            </table>
                        </div>
                        <div class="am-u-lg-12 am-cf">
                            	{{ $list->links() }}
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