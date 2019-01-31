@extends('admin.common.common')

@section('title')
	首页栏目列表
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf"  style="float:left">首页栏目列表</div>
                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_column/create')}}" type="button" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加栏目</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-table-hover am-table-centered">
                                        <thead>
                                            <tr>
                                                <th>顺序</th>
                                                <th>栏目名</th>
                                                <th>类型</th>
                                                <th>所属标签/集合</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($column as $k)
	                                            <tr class="gradeX">
	                                                <td>{{ $k->sort }}</td>
	                                                <td class="am-text-middle">{{ base64_decode($k->column_name) }}</td>
	                                                <td class="am-text-middle">
														@if($k->type == 1 )
															视频集合
														@elseif($k->type == 2)
															视频标签
														@elseif($k->type == 3)
															漫画标签
														@endif
	                                                </td>
	                                                <td class="am-text-middle data" type="{{$k->type}}" data-id="{{$k->data_id}}">{{ $dataName[$k->type][$k->data_id]}}</td>
	                                                <td class="am-text-middle">
	                                                    <div class="tpl-table-black-operation">
	                                                        <a href="{{ url('nba/nba_column/'.$k->id.'/edit') }}">
	                                                            <i class="am-icon-pencil"></i> 编辑
	                                                        </a>
                                                             <a href="javascript:;" class="tpl-table-black-operation-del del" data-id="{{ $k->id }}">
                                                            <i class="am-icon-trash"></i> 删除
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
                                    	{{ $column->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('javascript')
<script>
    $('.del').on('click',function(){
        if (confirm('确定删除该栏目?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var id = that.attr('data-id');
            $.ajax({
                url:"{{ url('nba/nba_column') }}"+'/'+id,
                data:{id:id},
                method:'delete',
                dataType:'json',
                success:function(msg)
                {
                    if (msg['code'] == 200) {
                          that.parent().parent().parent().remove();
                          $.AMUI.progress.done();
                    }else if(msg['code'] == 500){
                           alert(msg['data']);
                           $.AMUI.progress.done();
                    }
                },
                error:function(){
                    alert('系统错误,请联系管理员');
                    $.AMUI.progress.done();
                }
            })
        }
    })
</script>

@stop