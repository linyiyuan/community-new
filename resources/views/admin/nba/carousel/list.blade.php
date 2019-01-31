@extends('admin.common.common')

@section('title')	
	轮播图列表页
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf">轮播图列表页</div>
                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_carousel/create')}}" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span>添加轮播图</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ url('nba/nba_carousel')}}" method="get">
                                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                                        <div class="am-form-group tpl-table-list-select">
                                            <select data-am-selected="{searchBox: 1}" name="type">
                                                 <option value="-2">点击选择类型</option>
                                              @foreach($type as $k => $v)
    							                 <option  value="{{ $k }}" {{intval(Request::get('type'))===$k?'selected':''}}>{{ $v }}</option>
                                              @endforeach
    							            </select>
                                        </div>
                                    </div>
                                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                            <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                                    <input type="text" class="am-form-field " name="title" placeholder="请输入要查询的标题" value="{{ Request::get('title')? Request::get('title'):''}}">
                                                    <span class="am-input-group-btn">
                                                    <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                                      </span>
                                            </div>
                                         
                                    </div>
                                </form>
                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-scrollable-horizontal am-table am-table-compact am-table-striped tpl-table-black am-table-centered am-table-hover">
                                        <thead>
                                            <tr>
                                            	<th>顺序</th>	
                                                <th>封面图</th>
                                                <th>标题</th>
                                                <th>类型</th>
                                                <th>创建时间</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($carousel as $k)
                                            <tr class="gradeX">
                                            	<td class="am-text-middle">{{ $k->sort }}</td>
                                                <td class="am-text-middle"><img src="{{ $k->img }}" class="tpl-table-line-img" alt="" height="70" width="100"></td>
                                                <td class="am-text-middle">{{ $k->title }}</td>
                                                <td class="am-text-middle"> <span class="am-badge am-radius am-badge-secondary">{{ $type[$k->type]}}</span></td>
                                                <td class="am-text-middle">{{ $k->created_at}}</td>
                                                <td class="am-text-middle">
                                                    @if($k->is_show == 0)
                                                        <a href="{{ url('nba/nba_carousel/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">隐藏</a>
                                                    @elseif($k->is_show == 1)
                                                        <a href="{{ url('nba/nba_carousel/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">显示</a>
                                                    @endif
                                                </td>
                                                <td class="am-text-middle">
                                                    <div class="tpl-table-black-operation">
                                                        <a href="{{ url('nba/nba_carousel/'.$k->id.'/edit') }}">
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
                                    
                                       {{ $carousel->appends($search)->links() }}
                                  
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
        if (confirm('确定删除该轮播图?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var id = that.attr('data-id');
            $.ajax({
                url:"{{ url('nba/nba_carousel') }}"+'/'+id,
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