@extends('admin.common.common')

@section('title')
	@if($picCatalog->id)
		修改图片目录
	@else
		添加图片目录
	@endif
@stop

@section('content')
  <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($picCatalog->id)?'修改图片路径':'添加图片路径' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($picCatalog->id)?url('/helix_saga/pic_catalog/'.$picCatalog->id):url('/helix_saga/pic_catalog')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($picCatalog->id))
                            {{ method_field('PUT') }}
                        @endif
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">图片目录名<span class="tpl-form-line-small-title">Path</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="path" placeholder="填写图片目录名" value="{{ $picCatalog->path?$picCatalog->path:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">图片目录描述<span class="tpl-form-line-small-title">Desc</span></label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="6" id="user-intro" placeholder="请输入图片目录描述" name="desc">{{ $picCatalog->desc?$picCatalog->desc:'' }}</textarea>
                                        </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('/helix_saga/pic_catalog')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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