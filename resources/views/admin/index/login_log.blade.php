@extends('admin.common.common')

@section('title')
后台操作记录
@stop

@section('content')
		<div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title  am-cf">操作记录列表</div>


                            </div>
                            <div class="widget-body  am-fr">

                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                    <div class="am-form-group">
                                        <div class="am-btn-toolbar">
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                                    <div class="am-form-group tpl-table-list-select">
                                    </div>
                                </div>
                                <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                     <form action="{{ url('admin/log')}}" method="get">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                             <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'years'}" style="float:left">
                                                             <input type="text" class="am-form-field" placeholder="点击选择日期" data-am-datepicker="{theme: 'success'}" readonly/ name="created_at">
                                                              <span class="am-input-group-btn">
                                                                <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                                              </span>
                                             </div>
                                        </div>
                                     </form>
                                </div>

                                <div class="am-u-sm-12 am-scrollable-horizontal">
                                    <table width="100%" class="am-text-nowrap am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>用户名</th>
                                                <th>角色</th>
                                                <th>访问ip</th>
                                                <th>操作</th>
                                                <th>详情</th>
                                                <th>操作时间</th>
                                                <th>结果</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(empty($loginLog))
                                                <tr class="gradeX">
                                                     <td colspan="8">暂无数据</td>
                                                </tr>
                                            @else
                                                @foreach($loginLog as $key)
                                                    <tr class="gradeX">
                                                        <td>{{ $key->id}}</td>
                                                        <td>{{ $key->username}}</td>
                                                        <td>
                                                         @foreach(explode(',',$key->role) as $kS => $v)
																<span class="am-badge am-badge-warning">{{ $v }}</span>

                                                         @endforeach
                                                        </td>
                                                        <td>{{ $key->ip}}</td>
                                                        <td>{{ $key->operate}}</td>
                                                        <td>{{ $key->detail}}</td>
                                                        <td>{{ $key->created_at}}</td>
                                                        <td>
															@if($key->result == 1)
															    <span class="am-badge am-badge-success">成功</span>
															@elseif($key->result === 0)
																<span class="am-badge am-badge-danger">失败</span>
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
                                        {{ $loginLog->links() }}
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
	
	
</script>
@stop