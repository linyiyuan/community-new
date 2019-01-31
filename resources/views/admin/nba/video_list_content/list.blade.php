@extends('admin.common.common')

@section('title')
	集合内容
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf"  style="float:left">视频列表</div>
                                <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_video_tag') }}" type="button" class="am-btn am-btn-default am-btn-warning"> 标签</a>
                                            </div>
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_video_list') }}" type="button" class="am-btn am-btn-default am-btn-danger"> 集合</a>
                                            </div>
                                            <div class="am-btn-group am-btn-group-xs" style="float:right">
                                                <a href="{{ url('nba/nba_video') }}" type="button" class="am-btn am-btn-default am-btn-secondary"> 列表</a>
                                            </div>
                                </div>
                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('nba/nba_video_list_content/create?id='.$id)}}" type="button" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加集合视频</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                
                                </div>

                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-table-centered am-table am-table-compact am-table-striped tpl-table-black ">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>优先级</th>
                                                <th>标题</th>
                                                <th>标签</th>
                                                <th>集合</th>
                                                <th>发布日期</th>
                                                <th>视频封面</th>
                                                <th>视频id/视频地址</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($videoListContent as $k)
                                                <tr class="gradeX">
                                                    <td class="am-text-middle">{{ $k->id }}</td>
                                                    <td class="am-text-middle">{{ $k->sort }}</td>
                                                    <td class="am-text-middle"><div class="am-text-truncate" style="width: 100px; padding: 10px;">{{ $k->title}}</<div></td>
                                                     <td class="am-text-middle">
                                                        @if($k->tag_id == 0)
                                                            无标签
                                                        @else
                                                         {{ $videoTag[$k->tag_id]}}
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">集合</td>
                                                    <td class="am-text-middle">{{ date('Y-m-d',$k->time) }}</td>
                                                    <td class="am-text-middle"><img src="{{ $k->cover }}" class="tpl-table-line-img" alt=""></td>
                                                    <td class="am-text-middle"><div class="am-text-truncate" style="width: 100px; padding: 10px;">{{ $k->data}}</<div></td>
                                                    <td class="am-text-middle">
                                                        @if($k->is_show == 0)
                                                            <a href="{{ url('nba/nba_video/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">隐藏</a>
                                                        @elseif($k->is_show == 1)
                                                            <a href="{{ url('nba/nba_video/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">显示</a>
                                                        @endif
                                                    </td>
                                                    <td class="am-text-middle">
                                                        <div class="tpl-table-black-operation">
                                                            <a href="{{ url('nba/nba_video/'.$k->id.'/edit') }}">
                                                                <i class="am-icon-pencil"></i> 编辑
                                                            </a>
                                                            <a href="javascript:;" class="tpl-table-black-operation-del del" data-id="{{ $k->id }}">
                                                                <i class="am-icon-trash"></i> 移除集合
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
        if (confirm('确定将该视频移除该集合?')) {
            $.AMUI.progress.start();
            var that = $(this);
            var video_id = that.attr('data-id');
            var video_list_id = {{ $id }}
            $.ajax({
                url:"{{ url('nba/nba_video_list_content') }}"+'/'+video_id,
                data:{video_list_id:video_list_id},
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