@extends('admin.common.common')

@section('title')
@if($carousel->id)
    修改轮播图
@else
    添加轮播图
@endif
@stop

@section('content')
 <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
						{{ isset($carousel->id)?'修改轮播图':'添加轮播图' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($carousel->id)?url('nba/nba_carousel/'.$carousel->id):url('nba/nba_carousel')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
						@if(isset($carousel->id))
							{{ method_field('PUT') }}
                        @endif
                    	<div class="am-form-group">
                            <label for="user-phone" class="am-u-sm-3 am-form-label">类型<span class="tpl-form-line-small-title">Type</span></label>
                            <div class="am-u-sm-9">
                                <select data-am-selected="{maxHeight: 150,searchBox: 1}" style="display: none;" name="type" class="type" required>
								  <option value=""></option>
								  @foreach($type as $k => $v)
								  	<option value="{{ $k }}" {{$carousel->type == $k?'selected':''}}>{{ $v }}</option>
								  @endforeach
								</select>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-name" class="am-u-sm-3 am-form-label">顺序 <span class="tpl-form-line-small-title">Title</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" class="tpl-form-input" id="user-name" placeholder="自然数，数字大的排前" style="width:150px" name="sort" value="{{ isset($carousel->sort)?$carousel->sort:' '}}" >
                                <small>请输入顺序(数字越小优先度越高)</small>
                            </div>
                        </div>
                        <div class="am-form-group">
                                <label for="user-intro" class="am-u-sm-3 am-form-label">是否显示<span class="tpl-form-line-small-title">Is_show</span></label>
                                    <div class="am-u-sm-9">
                                      <label class="am-checkbox-inline">
                                        <input type="radio" name="is_show" {{ $carousel->is_show==1?'checked':''}} value="1"> 显示
                                      </label>
                                      <label class="am-checkbox-inline">
                                        <input type="radio" name="is_show" {{ $carousel->is_show===0?'checked':''}} value="0" > 隐藏
                                      </label>
                                    </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">封面图 <span class="tpl-form-line-small-title">Images</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
									    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
									  <input class="doc-form-file" type="file" multiple name="pic">
                                </div>
                                <div class="file-list">
                                    @if($carousel->img)<img src="{{ $carousel->img }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group url" style="display:none">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">链接 <span class="tpl-form-line-small-title">Url</span></label>
                            <div class="am-u-sm-9">
                                <input type="text"  placeholder="请输入链接" name="url" value="{{ $carousel->url?$carousel->url:' '}}">
                                <div>
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group third" style="display:none">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label"><span class="third_text"></span><span class="tpl-form-line-small-title">Third_id</span></label>
                            <div class="am-u-sm-9">
                                	<input type="text"  name="third_id" value="{{ $carousel->third_id?$carousel->third_id:' '}}">
                                <div>
                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">标题 <span class="tpl-form-line-small-title">Title</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="title" placeholder="请输入标题" value="{{ $carousel->title?$carousel->title:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_carousel')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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
		$('.type').on('change',function(){
			var that = $(this);
			var val = that.val();//拿到对应type类型
			if (val == 0 || val == 2) {
				$('.third').attr('style','display:none');
				$('.url').attr('style','');	
			}else if(val == 1 || val == 3 || val == 4){
				$('.url').attr('style','display:none')
				$('.third').attr('style','');
				if (val == 1) {$('.third_text').html('填写视频id')}	
				if (val == 3) {$('.third_text').html('填写漫画id')}	
				if (val == 4) {$('.third_text').html('填写图文id')}	
			}
		})
		$(function(){
			var type = $('.type').val();
			if (type == 0 || type == 2) {
				$('.third').attr('style','display:none');
				$('.url').attr('style','');	
			}else if(type == 1 || type == 3 || type == 4){
				$('.url').attr('style','display:none')
				$('.third').attr('style','');
				if (type == 1) {$('.third_text').html('填写视频id')}	
				if (type == 3) {$('.third_text').html('填写漫画id')}	
				if (type == 4) {$('.third_text').html('填写图文id')}	
			}
		})

	</script>	
@stop