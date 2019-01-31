@extends('admin.common.common')

@section('title')
	@if($column->id)
        修改栏目
    @else
        添加栏目
    @endif
@stop

@section('content')
<div class="row-content am-cf">
 <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($column->id)?'修改栏目':'添加栏目' }}
                    </div>
                    <div class="widget-function am-fr">
                        <a href="javascript:;" class="am-icon-cog"></a>
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($column->id)?url('nba/nba_column/'.$column->id):url('nba/nba_column')}}" enctype="multipart/form-data" data-am-validator>
                        {{ csrf_field()}}
                        @if(isset($column->id))
                            {{ method_field('PUT') }}
                        @endif
                       
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">栏目名<span class="tpl-form-line-small-title">Title</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="column_name" placeholder="填写栏目名(默认为标签/集合名)" value="{{ $column->column_name?base64_decode($column->column_name):''}}" required>
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">栏目类型<span class="tpl-form-line-small-title">Type</span></label>
                                        <div class="am-u-sm-9">
                                            <select class="type" data-am-selected="{maxHeight: 150,searchBox: 1}" name="type"required >
                                                 <option></option>
                                                 <option value="1" {{ $column->type == 1?'selected':''}}>视频集合</option>
                                                 <option value="2" {{ $column->type == 2?'selected':''}}>视频标签</option>
                                                 <option value="3" {{ $column->type == 3?'selected':''}}>漫画标签</option>
                                            </select>
                                        </div>
                       </div>
                         <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">标签/集合<span class="tpl-form-line-small-title">Data_id</span></label>
                                        <div class="am-u-sm-9">
                                            <select class="data_id" data-am-selected="{maxHeight: 100,searchBox: 1}" name="data_id" required>
                                            </select>
                                        </div>
                       </div>
                       <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">顺序<span class="tpl-form-line-small-title">Sort</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="sort" placeholder="默认0，数字小优先，同级新发布排前" value="{{ isset($column->sort)?$column->sort:''}}" max="200">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="{{ url('nba/nba_column')}}" class="am-btn am-btn-warning tpl-btn-bg-color-success ">返回</a>
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
    $(function(){
        var type = $('.type').val();
        var data_id = "{{ $column->data_id}}" ;
        if (type != '') {
             var option = '';//存储遍历出来下拉选项
                $.ajax({
                    url:"{{ url('nba/nba_column_data') }}",
                    type:'post',
                    data:{type:type},
                    dataType:'json',
                    success:function(msg)
                    {
                        for(key in msg['data']){
                            if (key == data_id) {
                               option += '<option value="'+key+'" selected>'+msg['data'][key]+'</option>'; 
                           }else{
                               option += '<option value="'+key+'">'+msg['data'][key]+'</option>'; 
                           }
                            
                        }

                        $('.data_id').html(option);
                    }

                })
        }
    })
    
    $('.type').on('change',function(){
        var that = $(this);
        var type = that.val();
        var option = '';//存储遍历出来下拉选项
        $.ajax({
            url:"{{ url('nba/nba_column_data') }}",
            type:'post',
            data:{type:type},
            dataType:'json',
            success:function(msg)
            {
                for(key in msg['data']){
                   option += '<option value="'+key+'">'+msg['data'][key]+'</option>';
                }
                $('.data_id').html(option);
                // $('input[name=column_name]').val($(".data_id option:selected").text());
            }

        })
    })
    var default_column_name = "{{base64_decode($column->column_name)}}";
        if (!default_column_name) {
           $('.data_id').on('change',function(){
            var column_name = $(".data_id option:selected").text();
            $('input[name=column_name]').val(column_name);
         });
    }
     


</script>


@stop

