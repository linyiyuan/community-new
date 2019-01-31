@extends('admin.common.common')


@section('title')
老黄历管理
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf">老黄历列表页</div>
                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_calendar/create')}}" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span>添加老黄历</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ url('nba/nba_calendar')}}" method="get">
                                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                        <div class="am-form-group tpl-table-list-select">
                                        </div>
                                    </div>
                                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                        <div class="am-form-group tpl-table-list-select">
                                            <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                                 <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}" style="float:left">
                                                                 <input type="text" class="am-form-field" placeholder="点击选择日期" data-am-datepicker="{theme: 'success'}" readonly/ name="time">
                                                                  <span class="am-input-group-btn">
                                                                    <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                                                  </span>
                                                 </div>
                                             
                                             </div>
                                        </div>
                                    </div>
                                  
                                </form>
                                <div class="am-u-sm-12">
                                    <table width="100%" class=" am-table am-table-compact am-table-striped tpl-table-black am-table-centered am-table-hover">
                                        <thead>
                                            <tr>
                                            	<th>Id</th>	
                                                <th>名人图片</th>
                                                <th>球星图片</th>
                                                <th>发布日期(默认零时发布)</th>
                                                <th>创建时间</th>
                                                <th>修改时间</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @foreach($calendar as $k)
	                                            <tr class="gradeX">
	                                            	<td class="am-text-middle">{{ $k->id }}</td>
	                                                <td>
	                                                    <img src=" {{ $k->avatar}}" class="tpl-table-line-img" alt="" height="70" width="100">
	                                                </td>
                                                     <td>
                                                        <img src=" {{ $k->img }}" class="tpl-table-line-img" alt="" height="70" width="100">
                                                    </td>
	                                                <td class="am-text-middle">{{ date('Y-m-d',$k->time)}}</td>
	                                                <td class="am-text-middle">{{ $k->created_at }}</td>
	                                                <td class="am-text-middle">{{ $k->updated_at }}</td>
	                                                <td class="am-text-middle">
	                                                    <div class="tpl-table-black-operation">
	                                                        <a href="{{ url('nba/nba_calendar/'.$k->id.'/edit' )}}">
	                                                            <i class="am-icon-pencil"></i> 编辑
	                                                        </a>
	                                                        <a href="javascript:;" class="tpl-table-black-operation-del del" data-id="{{ $k->id}}">
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
                                     	{{ $calendar->appends($search)->links() }}
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
        if (confirm('确定删除该老黄历?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var id = that.attr('data-id');
            $.ajax({
                url:"{{ url('nba/nba_calendar') }}"+'/'+id,
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
                }
            })
        }
    })
	</script>
@stop