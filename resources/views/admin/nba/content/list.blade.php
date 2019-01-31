@extends('admin.common.common')

@section('title')
	图文管理
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf"  style="float:left">图文列表</div>
                                <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_content_tag') }}" type="button" class="am-btn am-btn-default am-btn-warning"> 图文标签</a>
                                            </div>
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_content') }}" type="button" class="am-btn am-btn-default am-btn-secondary"> 图文列表</a>
                                            </div>
                                </div>
                            </div>
                            <div class="widget-body  am-fr">
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_content/create')}}" type="button" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加图文</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ url('nba/nba_content')}}" method="get">
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group tpl-table-list-select">
                                        <select data-am-selected name="tag_id">
                                              <option></option>
							                  <option value="0" {{intval(Request::get('tag_id'))===0?'selected':''}}>无标签</option>
                                              @foreach($contentTag as $k => $v)
                                                    <option value="{{ $k }}" {{Request::get('tag_id')==$k?'selected':''}}>{{ $v }}</option>
                                              @endforeach
							             
							            </select>
                                         <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                                      </span>
                                    </div>
                                </div>
                                 
                            </form>

                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-table-centered am-table am-table-compact am-table-striped tpl-table-black am-table-hover">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>优先级</th>
                                                <th>标题</th>
                                                <th>标签</th>
                                                <th>发布日期</th>
                                                <th>图文封面</th>
                                                <th>预览</th>
                                                <th>状态</th>	
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($content as $k)
                                                <tr class="gradeX">
                                                    <td class="am-text-middle">{{ $k->id }}</td>
                                                    <td class="am-text-middle">{{ $k->sort }}</td>
                                                    <td class="am-text-middle"><div class="am-text-truncate" style="width: 250px; padding: 10px;">{{ $k->title}}</div></td>
                                                    <td class="am-text-middle">
                                                        @if($k->tag_id == 0)
                                                            无
                                                        @else
														  {{ $contentTag[$k->tag_id] }}
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">{{ date('Y-m-d',$k->time) }}</td>
                                                    <td class="am-text-middle"><img src="{{ $k->cover }}" class="tpl-table-line-img" alt="" height="70" width="100"></td>
                                                    <td class="am-text-middle"><a href="http://hd2.appgame.com/hdpic/?id=4">预览</a></td>
                                                    <td class="am-text-middle">
                                                        @if($k->is_show == 0)
                                                            <a href="{{ url('nba/nba_content/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">隐藏</a>
                                                        @elseif($k->is_show == 1)
                                                            <a href="{{ url('nba/nba_content/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">显示</a>
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">
                                                        <div class="tpl-table-black-operation">
                                                            <a href="{{ url('nba/nba_content/'.$k->id.'/edit') }}">
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
                                        {{ $content->appends($search)->links() }}
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
        if (confirm('确定删除该图文?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var content_id = that.attr('data-id');
            $.ajax({
                url:"{{ url('nba/nba_content') }}"+'/'+content_id,
                data:{content_id:content_id},
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
                error:function(msg)
                {   
                    alert('系统错误');
                    $.AMUI.progress.done();
                }
            })
        }
    })

</script>

@stop