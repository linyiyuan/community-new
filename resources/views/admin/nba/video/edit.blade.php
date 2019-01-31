@extends('admin.common.common')

@section('title')
	@if($video->id)
        修改视频
    @else
        添加视频
    @endif
@stop


@section('content')
<div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($video->id)?'修改视频':'添加视频' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($video->id)?url('nba/nba_video/'.$video->id):url('nba/nba_video')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($video->id))
                            {{ method_field('PUT') }}
                        @endif
                        <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
                        <div class="am-form-group">
                            <label for="user-intro" class="am-u-sm-3 am-form-label">发布日期<span class="tpl-form-line-small-title">Time</span></label>
                            <div class="am-u-sm-9">
                              <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}">
                                      <input type="text" class="am-form-field" placeholder="日历组件" readonly name="time" value="{{ $video->time?date('Y-m-d',$video->time):date('Y-m-d',time())}}">
                                      <span class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                      </span>
                              </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">标题<span class="tpl-form-line-small-title">Title</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="title" placeholder="填写标题" value="{{ $video->title?$video->title:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">视频标签<span class="tpl-form-line-small-title">Video_Tag</span></label>
                                        <div class="am-u-sm-9">
                                            <select data-am-selected="{maxHeight: 150,searchBox: 1}" style="display: none;" name="tag_id" required>
                                                 <option value="0">-无标签</option>
                                              @foreach($videoTag as $k => $v)
                                                  <option value="{{ $k }}" {{$video->tag_id == $k?'selected':''}}>-{{ $v }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                       </div>
                        <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">视频类型<span class="tpl-form-line-small-title">Video_Type</span></label>
                                        <div class="am-u-sm-9">
                                            <select data-am-selected style="display: none;" name="type" required>
                                                <option value="1" {{ $video->type == 1?'selected':''}}>MP4视频</option>
                                                <option value="2" {{ $video->type == 2?'selected':''}}>腾讯视频</option>
                                            </select>
                                        </div>
                       </div>
                       <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">顺序<span class="tpl-form-line-small-title">Sort</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="sort" placeholder="默认0，数字大优先，同级新发布排前" value="{{ $video->id?$video->sort:' '}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">腾讯id/视频地址<span class="tpl-form-line-small-title">Data</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="腾讯视频id或MP4地址" name="data">{{ $video->data?$video->data:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">视频封面 <span class="tpl-form-line-small-title">Cover</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                      <input class="doc-form-file" type="file" multiple name="cover">
                                </div>
                                <div class="file-list">
                                    @if($video->cover)<img src="{{ $video->cover }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_video')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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


