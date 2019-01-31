@extends('admin.common.common')

@section('title')
后台角色管理页
@stop

@section('content')
<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf">角色列表</div>


                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                            <div class="am-btn-group am-btn-group-xs">
                                                <a href="{{ url('admin/role/create')}}" class="am-btn am-btn-default am-btn-success"><span class="am-icon-plus"></span> 添加角色</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                     <form action="{{ url('admin/role')}}" method="get">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                           
                                                <input type="text" class="am-form-field" placeholder='请输入要搜索的角色' name="name">
                                                <span class="am-input-group-btn">
                                                    <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                           
                                                </span>
                                        </div>
                                     </form>
                                </div>

                                <div class="am-u-sm-12 am-scrollable-horizontal">
                                    <table width="100%" class="am-text-nowrap am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>角色名称</th>
                                                <th>角色展示名称</th>
                                                <th>角色描述</th>
                                                <th>创建时间	</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(empty($list))
                                                <tr class="gradeX">
                                                     <td colspan="8">暂无数据</td>
                                                </tr>
                                            @else
                                                @foreach($list as $key)
                                                    <tr class="gradeX">
                                                        <td>{{ $key->id}}</td>
                                                        <td>{{ $key->name}}</td>
                                                        <td>{{ $key->display_name}}</td>
                                                        <td>{{ $key->description}}</td>
                                                        <td style="width:250px">{{ $key->created_at}}</td>
                                                        <td>
                                                            @if($key->id != 1)
                                                                <div class="tpl-table-black-operation">
                                                                    <a href="{{ url('admin/role').'/'.$key->id.'/edit' }}">
                                                                        <i class="am-icon-pencil"></i> 编辑
                                                                    </a>
                                                                    <a href="javascript:;" class="tpl-table-black-operation-del del" data-id="{{ $key->id }}">
                                                                        <i class="am-icon-trash"></i> 删除
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <!-- more data -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="am-u-lg-12 am-cf">
                                    <div class="am-fr">
                                        {{ $list->appends(['name' => $name ])->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop
@section('javascript')
    <script>
        $('.del').on('click',function(){
            var that  = $(this);
            var id = that.attr('data-id');
            if (confirm('确定删除该角色?')) {
                 $.ajax({
                    url:"{{ url('admin/role') }}/"+id,
                        method:'delete',
                        data:{id:id},
                        dataType:'json',
                        success:function(msg)
                        {
                            if(msg['code'] == 200){
                                that.parent().parent().parent().remove();
                            }else if(msg['code'] == 500){
                                alert(msg['data']);
                            }

                        }   
                })

            }
           
        })
    </script>
@stop