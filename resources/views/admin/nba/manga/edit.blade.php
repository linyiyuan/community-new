@extends('admin.common.common')

@section('title')
	@if($manga->id)
        修改漫画
    @else
        添加漫画
    @endif
@stop


@section('content')
<div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($manga->id)?'修改漫画':'添加漫画' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($manga->id)?url('nba/nba_manga/'.$manga->id):url('nba/nba_manga')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($manga->id))
                            {{ method_field('PUT') }}
                        @endif
                        <input type="hidden" name="admin_id" value="{{ Auth::id() }}">
                        <div class="am-form-group">
                            <label for="user-intro" class="am-u-sm-3 am-form-label">发布日期<span class="tpl-form-line-small-title">Time</span></label>
                            <div class="am-u-sm-9">
                              <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}">
                                      <input type="text" class="am-form-field" placeholder="日历组件" readonly name="time" value="{{ $manga->time?date('Y-m-d',$manga->time):date('Y-m-d',time())}}">
                                      <span class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                      </span>
                              </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">标题<span class="tpl-form-line-small-title">Title</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="title" placeholder="填写标题" value="{{ $manga->title?$manga->title:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                         <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">漫画标签<span class="tpl-form-line-small-title">Manga_Tag</span></label>
                                        <div class="am-u-sm-9">
                                            <select data-am-selected="{maxHeight: 150,searchBox: 1}" style="display: none;" name="tag_id" required>
                                            	 <option value="0">无标签</option>
                                              @foreach($mangaTag as $k => $v)
                                                  <option value="{{ $k }}" {{$manga->tag_id==$k?'selected':''}}>-{{ $v }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                       </div>
                       <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">优先度<span class="tpl-form-line-small-title">Sort</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="sort" placeholder="默认0，数字大优先，同级新发布排前" value="{{ isset($manga->sort)?$manga->sort:''}}" max="200">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">漫画封面 <span class="tpl-form-line-small-title">Cover</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的封面图</button>
                                      <input class="doc-form-file" type="file" multiple name="cover">
                                </div>
                                <div class="file-list">
                                    @if($manga->cover)<img src="{{ $manga->cover }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                <label for="user-intro" class="am-u-sm-3 am-form-label">漫画类型<span class="tpl-form-line-small-title">Type	</span></label>
                                    <div class="am-u-sm-9">
                                      <label class="am-checkbox-inline">
                                        <input type="radio" name="type" {{ $manga->type==1?'checked':''}} value="1"> 单列
                                      </label>
                                      <label class="am-checkbox-inline">
                                        <input type="radio" name="type" {{ $manga->type===2?'checked':''}} value="2" > 双列
                                      </label>
                                    </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">漫画内容<span class="tpl-form-line-small-title">Cover</span></label>
                            @if(isset($mangaImgs))
	                            @if($mangaImgs->isEmpty())
		                            <div class="am-u-sm-9 manga-img" count="1">
			                                <div class="am-form-group am-form-file">
			                                    <div class="tpl-form-file-img">
			                                    </div>
			                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
			                                        <i class="am-icon-cloud-upload"></i> 选择要上传的漫画图片</button>
			                                      <input class="doc-form-file" type="file" multiple name="img[]">
			                                </div>
			                                <div class="file-list">
			                                </div>
		                        	</div>
	                        	@else
									<div class="am-u-sm-9 manga-img" count="{{ count($mangaImgs )}}">
										@foreach($mangaImgs as $k)
			                                <div class="am-form-group am-form-file">
			                                    <div class="tpl-form-file-img">
			                                    </div>
			                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
			                                        <i class="am-icon-cloud-upload"></i> 选择要上传的漫画图片</button>
			                                      <input class="doc-form-file" type="file" multiple name="{{ 'img_'.$k->id}}">
			                                      <input type="hidden" name="img_id[]" value="{{ $k->id }}">
			                                </div>
			                                <div class="file-list" style="margin-bottom:30px">
			                                    @if($k->img)<img src="{{ $k->img }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
			                                </div>
		                                @endforeach
		                            </div>
	                        	@endif
                        	@else
								<div class="am-u-sm-9 manga-img" count="1">
			                                <div class="am-form-group am-form-file">
			                                    <div class="tpl-form-file-img">
			                                    </div>
			                                    <button type="button" class="am-btn am-btn-danger am-btn-sm file-list">
			                                        <i class="am-icon-cloud-upload"></i> 选择要上传的漫画图片</button>
			                                      <input class="doc-form-file" type="file" multiple name="img[]">
			                                </div>
			                                <div class="file-list">
			                                </div>
		                        </div>
                        	@endif
                        	<div class="am-u-sm-9">
	                                <div class="am-form-group am-form-file">
	                                    <div class="tpl-form-file-img">
	                                    </div>
	                                    <button type="button" class="am-btn am-btn-success am-btn-sm add-img">
	                                        <i class="am-icon-plus"></i>添加更多</button>
	                                </div>
                        	</div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_manga')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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
	$('.add-img').on('click',function(){
		var count = $('.manga-img').attr('count');

		if (count >= 9) {
			alert('超过最大上传量');
			return;
		}
		var that = $(this);
		var img = '<div class="am-form-group am-form-file"><div class="tpl-form-file-img"></div><button type="button" class="am-btn am-btn-danger am-btn-sm"><i class="am-icon-cloud-upload"></i> 选择要上传的漫画图片</button><input class="doc-form-file" type="file" multiple name="img[]"></div><div class="file-list"></div>';

		that.parent().parent().prev('.manga-img').append(img);

		count ++;
		$('.manga-img').attr('count',count);
		
	})





</script>

@stop

