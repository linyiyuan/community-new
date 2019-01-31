@extends('admin.common.common')

@section('title')
	漫画列表
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf"  style="float:left">漫画列表</div>
                                <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_manga_tag') }}" type="button" class="am-btn am-btn-default am-btn-warning"> 漫画标签</a>
                                            </div>
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_manga') }}" type="button" class="am-btn am-btn-default am-btn-secondary"> 漫画列表</a>
                                            </div>
                                </div>
                            </div>
                            <div class="widget-body  am-fr">
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_manga/create')}}" type="button" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加漫画</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ url('nba/nba_manga')}}" method="get">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group tpl-table-list-select">
                                        <select data-am-selected name="tag_id">
                                              <option></option>
							                  <option value="0" {{intval(Request::get('tag_id'))===0?'selected':''}}>无标签</option>
                                              @foreach($mangaTag as $k => $v)
                                                    <option value="{{ $k }}" {{Request::get('tag_id')==$k?'selected':''}}>{{ $v }}</option>
                                              @endforeach
							             
							            </select>
                                         <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                                      </span>
                                    </div>
                                </div>
                                 
                            </form>

                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-table-centered am-table am-table-compact am-table-striped tpl-table-black am-table-hover ">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>优先级</th>
                                                <th>标题</th>
                                                <th>标签</th>
                                                <th>发布日期</th>
                                                <th>漫画封面</th>
                                                <th>样式</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($manga as $k)
                                                <tr class="gradeX">
                                                    <td class="am-text-middle">{{ $k->id }}</td>
                                                    <td class="am-text-middle">{{ $k->sort }}</td>
                                                    <td class="am-text-middle"><div class="am-text-truncate" style="width: 200px; padding: 10px;">{{ $k->title}}</div></td>
                                                    <td class="am-text-middle">
                                                        @if($k->tag_id == 0)
                                                        <span class="am-badge am-radius am-badge-danger">
                                                            无
                                                        </span>
                                                        @else
                                                        <span class="am-badge am-radius am-badge-secondary">
														  {{ $mangaTag[$k->tag_id] }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">{{ date('Y-m-d',$k->time) }}</td>
                                                    <td class="am-text-middle"><img src="{{ $k->cover }}" class="tpl-table-line-img" alt="" height="70" width="100"></td>
                                                    <td class="am-text-middle">{{ $k->type }}</td>
                                                    <td class="am-text-middle">
                                                        @if($k->is_show == 0)
                                                            <a href="{{ url('nba/nba_manga/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">隐藏</a>
                                                        @elseif($k->is_show == 1)
                                                            <a href="{{ url('nba/nba_manga/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">显示</a>
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">
                                                        <div class="tpl-table-black-operation">
                                                            <a href="{{ url('nba/nba_manga/'.$k->id.'/edit') }}">
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
                                        {{ $manga->appends($search)->links() }}
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
        if (confirm('确定删除该漫画以及其漫画图片 不可恢复?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var manga_id = that.attr('data-id');
            $.ajax({
                url:"{{ url('nba/nba_manga') }}"+'/'+manga_id,
                data:{manga_id:manga_id},
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