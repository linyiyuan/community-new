@extends('admin.common.common')

@section('title')
数据管理
@stop

@section('content')
<div class="row-content am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title  am-cf"  style="float:left">数据管理列表</div>
                    </div>
                    <div class="widget-body  am-fr">

                        <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                            <div class="am-form-group">
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                      <a class="am-btn am-btn-success" href="{{ url('/helix_saga/data_dictionary/create')}}">添加数据表(Excel)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-sm-12">
                            <table width="100%" class="am-table-hover am-table am-table-compact am-table-striped tpl-table-black am-table-centered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>数据表名</th>
                                        <th>路径</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($dataDictionary as $k)
                                        <tr class="gradeX">
                                            <td class="am-text-middle">{{ $k->id }}</td>
                                            <td class="am-text-middle">{{ $k->name }}</td>
                                             <td class="am-text-middle"><div class="am-text-truncate" style="width: 300px; padding: 10px;"><input type="text" value="{{ $k->url}}" style="opacity:0;position:absolute" id="copy">{{ $k->url}}</<div></td>
                                            <td class="am-text-middle">{{ $k->created_at }}</td>
                                            <td class="am-text-middle">{{ $k->updated_at }}</td>
                                            <td class="am-text-middle">
                                                 <div class="tpl-table-black-operation">
	                                                        <a href="{{ url('helix_saga/data_dictionary/'.$k->id.'/edit') }}">
	                                                            <i class="am-icon-pencil"></i>编辑
	                                                        </a>
                                                            <a href="javascript:;" style="border:1px solid grey;color:grey" class="copy" url="{{ $k->url}}">
                                                                <i class="am-icon-copy" style="color:grey"></i>复制链接
                                                            </a>
                                                            <!-- <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 复制</button> -->
                                                        </a>
	                                             </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- more data -->
                                </tbody>
                            </table>
                        </div>
                        <div class="am-u-lg-12 am-cf">
                            	{{ $dataDictionary->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@stop
@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
<script>
 $('.copy').on('click',function(){
    var content = $(this).attr('url'); 
    var clipboard = new ClipboardJS('.copy', {  
        text: function() {  
            return content;  
        }  
    }); 
    alert('复制成功'); 
  
 })
</script>
@stop