@extends('admin.common.common')

@section('title')
	添加{{ $videoList->list_name }}集合视频
@stop

@section('content')
  <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        添加{{ $videoList->list_name}}集合视频
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{ url('nba/nba_video_list_content') }}" enctype="multipart/form-data">
                         {{ csrf_field()}}
                        <input type="hidden" name="video_list_id" value="{{ $videoList->id }}">
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">当前集合名称<span class="tpl-form-line-small-title">List_name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text"  placeholder="填写集合名（1~6）字" value="{{ $videoList->list_name}}" disabled>
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">视频id<span class="tpl-form-line-small-title">Video_id</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="video_id" placeholder="填写视频id" value="" stype="width:150px">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_video_list_content?id='.$videoList->id) }}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
                                <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success ">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop