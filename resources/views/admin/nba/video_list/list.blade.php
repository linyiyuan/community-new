@extends('admin.common.common')

@section('title')
视频集合列表
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf"  style="float:left">视频集合列表</div>
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
                                                <a href="{{ url('nba/nba_video_list/create')}}" type="button" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加视频集合</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-sm-12">
                                    <table width="100%" class="am-table-centered am-table am-table-compact am-table-striped tpl-table-black am-table-hover">
                                        <thead>
                                            <tr>
                                                <th>顺序</th>
                                                <th>集合名</th>
                                                <th>视频数量</th>
                                                <th>状态</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	@foreach($videoList as $k)
	                                            <tr class="gradeX">
	                                                <td>{{ $k->sort }}</td>
	                                                <td class="am-text-middle">{{ $k->list_name }}</td>
	                                                <td class="am-text-middle">{{ $k->count }}</td>
	                                                <td class="am-text-middle">
														@if($k->is_show === 0)
                                                       		 <a href="{{ url('nba/nba_video_list/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">隐藏</a>
	                                                    @elseif($k->is_show == 1)
	                                                         <a href="{{ url('nba/nba_video_list/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">显示</a>
	                                                    @endif
	                                                </td>
	                                                <td class="am-text-middle">
	                                                    <div class="tpl-table-black-operation">
	                                                        <a href="{{ url('nba/nba_video_list/'.$k->id.'/edit') }}">
	                                                            <i class="am-icon-pencil"></i> 编辑
	                                                        </a>
	                                                        <a href="{{ url('nba/nba_video_list_content/?id='.$k->id) }}">
	                                                            <i class="am-icon-pencil"></i> 编辑内容
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
                                    	{{ $videoList->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@stop