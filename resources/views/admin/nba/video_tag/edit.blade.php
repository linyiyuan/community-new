@extends('admin.common.common')

@section('title')
	@if($videoTag->id)
		修改视频标签
	@else
		添加视频标签
	@endif
@stop

@section('content')
  <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($videoTag->id)?'修改视频标签':'添加视频标签' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($videoTag->id)?url('nba/nba_video_tag/'.$videoTag->id):url('nba/nba_video_tag')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($videoTag->id))
                            {{ method_field('PUT') }}
                        @endif
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">标签名<span class="tpl-form-line-small-title">Name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="name" placeholder="填写标签名（1~6）字" value="{{ $videoTag->name?base64_decode($videoTag->name):''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">顺序<span class="tpl-form-line-small-title">Sort</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="sort" placeholder="填写顺序，数字越大越排前" value="{{ $videoTag->sort?$videoTag->sort:''}}" stype="width:150px">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">说明<span class="tpl-form-line-small-title">Desc</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="请输入说明" name="desc">{{ $videoTag->desc?$videoTag->desc:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_video_tag')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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