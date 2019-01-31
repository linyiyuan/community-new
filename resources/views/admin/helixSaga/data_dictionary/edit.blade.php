@extends('admin.common.common')

@section('title')
	@if($dataDictionary->id)
		修改数据表(Excel)
	@else
		添加数据表(Excel)
	@endif
@stop

@section('content')
  <div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($dataDictionary->id)?'修改数据表(Excel)':'添加数据表(Excel)' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($dataDictionary->id)?url('/helix_saga/data_dictionary/'.$dataDictionary->id):url('/helix_saga/data_dictionary')}}" enctype="multipart/form-data">
                        {{ csrf_field()}}
                        @if(isset($dataDictionary->id))
                            {{ method_field('PUT') }}
                        @endif
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">数据表(Excel)名<span class="tpl-form-line-small-title">Name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="name" placeholder="填写数据表(Excel)名" value="{{ $dataDictionary->name?$dataDictionary->name:''}}">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group am-form-file">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">数据表(Excel) <span class="tpl-form-line-small-title">zip</span></label>
                            <div class="am-u-sm-9">
                               <button type="button" class="am-btn am-btn-danger am-btn-sm">
                               <i class="am-icon-cloud-upload"></i> 选择要上传的文件(文件的大小限制为20M)</button>
                               <input id="doc-form-file" class="doc-form-file" type="file" multiple name="file">
                               <div id="file-list"></div>
                               <small>(数据表命名必须为 role.xlsx/tool.xlsx/skill.xlsx)</small>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('/helix_saga/data_dictionary')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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
</script>
@stop