@extends('admin.common.common')

@section('title')
导入图片
@stop

@section('content')
<div class="row-content am-cf">
  <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
					             	导入zip在线解压
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">
                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{ url('/helix_saga/import_picture/')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        <div class="am-form-group am-form-file">
                        	<label for="user-weibo" class="am-u-sm-3 am-form-label">图片压缩包 <span class="tpl-form-line-small-title">zip</span></label>
	                        <div class="am-u-sm-9">
								               <button type="button" class="am-btn am-btn-danger am-btn-sm">
								               <i class="am-icon-cloud-upload"></i> 选择要上传的文件(文件的大小限制为20M)</button>
								               <input id="doc-form-file" class="doc-form-file" type="file" multiple name="file">
								               <div id="file-list"></div>
								          </div>
							  </div>
                <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-3 am-form-label">图片目录<span class="tpl-form-line-small-title">Path</span></label>
                        <div class="am-u-sm-9">
                            <select data-am-selected style="display: none;" name="path" required>
                                @foreach($picCatalog as $k)
                                  <option value="{{$k->path}}">{{$k->path}}</option>
                                @endforeach
                            </select>
                        </div>
                 </div>
					  </div>
            <div class="am-form-group">
                <div class="am-u-sm-9 am-u-sm-push-3">
                <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success submit">提交</button>
                </div>
            </div>
            </form>
        </div>
      </div>
  </div>
<div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        导入单张或者或多张图片
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">
                     <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{ url('/helix_saga/import_picture/upload_pic')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        <div class="am-form-group am-form-file">
                          <label for="user-weibo" class="am-u-sm-3 am-form-label">图片 <span class="tpl-form-line-small-title">zip</span></label>
                          <div class="am-u-sm-9">
                              <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                    <i class="am-icon-cloud-upload"></i> 选择要上传的文件(最大上传文件数20)</button>
                                  <input id="doc-form-file-pic" type="file" multiple name="pic[]">
                                  <input type="hidden" id="countPic" value="0" name="picCount">
                                  <div id="file-list-pic"></div>
                          </div>
                </div>
                <div class="am-form-group">
                        <label for="user-phone" class="am-u-sm-3 am-form-label">图片目录<span class="tpl-form-line-small-title">Path</span></label>
                        <div class="am-u-sm-9">
                            <select data-am-selected style="display: none;" name="path" required>
                                @foreach($picCatalog as $k)
                                  <option value="{{$k->path}}">{{$k->path}}</option>
                                @endforeach
                            </select>
                        </div>
                 </div>
            </div>
            <div class="am-form-group">
                <div class="am-u-sm-9 am-u-sm-push-3">
                <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success submit">提交</button>
                </div>
            </div>
            </form>
        </div>
      </div>
  </div>
@stop
@section('javascript')
<script>
  $(function() {
    $('#doc-form-file').on('change', function() {
      var fileNames = '';
      $.each(this.files, function() {
        fileNames += '<span class="am-badge">' + this.name + '</span> ';
      });
      $('#file-list').html(fileNames);
    });

     $('#doc-form-file-pic').on('change', function() {
      var fileNames = '';
      $.each(this.files, function() {
        fileNames += '<span class="am-badge">' + this.name + '</span> ';
      });
      $('#file-list-pic').html(fileNames);
      $('#countPic').val(this.files.length);
    });
  });

  //加载进度条
  $('.submit').click(function(){
      $.AMUI.progress.start();
  })
</script>
	
	
</script>
@stop