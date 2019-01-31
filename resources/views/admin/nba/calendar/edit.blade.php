@extends('admin.common.common')

@section('title')
    @if($calendar->id)
        修改老黄历
    @else
        添加老黄历
    @endif
@stop

@section('content')
 <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($calendar->id)?'修改老黄历':'添加老黄历' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($calendar->id)?url('nba/nba_calendar/'.$calendar->id):url('nba/nba_calendar')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($calendar->id))
                            {{ method_field('PUT') }}
                        @endif
                        <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
                        <div class="am-form-group">
                            <label for="user-intro" class="am-u-sm-3 am-form-label">发布日期<span class="tpl-form-line-small-title">Time</span></label>
                            <div class="am-u-sm-9">
                              <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}">
                                      <input type="text" class="am-form-field" placeholder="日历组件" readonly name="time" value="{{ $calendar->time?date('Y-m-d',$calendar->time):date('Y-m-d',time())}}">
                                      <span class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                      </span>
                              </div>
                            </div>
                        </div>
                       <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">宜<span class="tpl-form-line-small-title">Good_content</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="单项不超5字，半角逗号分隔多项，最多3项" name="good_content">{{ $calendar->good_content?$calendar->good_content:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">忌<span class="tpl-form-line-small-title">Bad_content</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="单项不超5字，半角逗号分隔多项，最多3项" name="bad_content">{{ $calendar->bad_content?$calendar->bad_content:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">名人<span class="tpl-form-line-small-title">Person_name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="person_name" placeholder="填写名人名称（国籍）" value="{{ $calendar->person_name?$calendar->person_name:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">名言<span class="tpl-form-line-small-title">Person_word</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="请输入名言" name="person_word">{{ $calendar->person_word?$calendar->person_word:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">名人肖像 <span class="tpl-form-line-small-title">Avatar</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                      <input class="doc-form-file" type="file" multiple name="avatar">
                                </div>
                                <div class="file-list">
                                    @if($calendar->avatar)<img src="{{ $calendar->avatar }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">球星图片 <span class="tpl-form-line-small-title">Img</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button><span class="tpl-form-line-small-title">请上传PNG格式图片</span>
                                      <input class="doc-form-file" type="file" multiple name="img">
                                </div>
                                <div class="file-list">
                                    @if($calendar->img)<img src="{{ $calendar->img }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                <label for="user-intro" class="am-u-sm-3 am-form-label">活动提醒<span class="tpl-form-line-small-title">Tip</span></label>
                                <div class="am-u-sm-9">
                                    <textarea class="" rows="6" id="user-intro" placeholder="重点部分用“”符号包含，，半角逗号分隔多项，最多3项" name="tip">{{ $calendar->tip?$calendar->tip:'' }}</textarea>
                                </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_calendar')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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

@section('javascript')
    <script>

    </script>   
@stop