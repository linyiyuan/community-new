@extends('admin.common.common')

@section('title')
    @if($content->id)
        修改图文内容
    @else
        添加图文内容
    @endif
@stop

@section('style')
<style>
     #ueditor_0{ background: #121622 !important; } 
 </style>
@stop
@section('content')
 <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($content->id)?'修改图文内容':'添加图文内容' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($content->id)?url('nba/nba_content/'.$content->id):url('nba/nba_content')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($content->id))
                            {{ method_field('PUT') }}
                        @endif
                        <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">标题<span class="tpl-form-line-small-title">Person_name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="title" placeholder="请填写标题" value="{{ $content->title?$content->title:''}}">
                                <div>
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">图文标签<span class="tpl-form-line-small-title">Content_Tag</span></label>
                                        <div class="am-u-sm-9">
                                            <select data-am-selected="{maxHeight: 100,searchBox: 1}" style="display: none;" name="tag_id" required>
                                              @foreach($contentTag as $k => $v)
                                                  <option value="{{ $k }}" {{$content->tag_id==$k?'selected':''}}>-{{ $v }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                        </div>
                        <div class="am-form-group">
                                <label for="user-intro" class="am-u-sm-3 am-form-label">优先级<span class="tpl-form-line-small-title">Sort</span></label>
                                <div class="am-u-sm-9">
                                   <input type="number" name="sort" placeholder="请填写优先级 数字越大越优先" value="{{ $content->id?$content->sort:''}}">
                                </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">图文封面图 <span class="tpl-form-line-small-title">Conver</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                      <input class="doc-form-file" type="file" multiple name="cover">
                                </div>
                                <div class="file-list">
                                    @if($content->cover)<img src="{{ $content->cover }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-intro" class="am-u-sm-3 am-form-label">发布日期<span class="tpl-form-line-small-title">Time</span></label>
                            <div class="am-u-sm-9">
                              <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}">
                                      <input type="text" class="am-form-field" readonly name="time" value="{{ $content->time?date('Y-m-d',$content->time):date('Y-m-d',time())}}">
                                      <span class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                      </span>
                              </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">正文<span class="tpl-form-line-small-title">Person_word</span></label><br>
                                        <div class="am-u-sm-9" >
                                            <textarea style="backgroud:black" class="" id="editor" placeholder="请输入名言" name="content"></textarea>
                                        </div>
                        </div>                                           
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">	
                                <a href="{{ url('nba/nba_content')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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
<script type="text/javascript" src="{{ asset('ueditor/ueditor.config.js') }}" /></script>
<script type="text/javascript" src="{{ asset('ueditor/_examples/editor_api.js') }}" /></script> 
<script>
var editor = UE.getEditor('editor',{    
            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个    
            //focus时自动清空初始化时的内容    
            autoClearinitialContent:true,    
            //关闭字数统计    
            wordCount:true,    
            //关闭elementPath    
            elementPathEnabled:false,    
            //默认的编辑区域高度    
            initialFrameHeight:500,  
            //更多其他参数，请参考ueditor.config.js中的配置项    
            
        }); 

editor.ready(function() {
	    //设置编辑器的内容
	    editor.setContent('{!! $content->content !!}');
	    //获取html内容，返回: <p>hello</p>
	  
});


</script>
@stop