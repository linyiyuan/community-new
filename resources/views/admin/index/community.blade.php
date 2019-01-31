@extends('admin.common.common')

@section('title')
社区列表
@stop

@section('content')
<div class="row-content am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title  am-cf"  style="float:left">社区列表</div>
                    </div>
                    <div class="widget-body  am-fr">

                        <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                            <div class="am-form-group">
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                      <button type="button" class="am-btn am-btn-success" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 1, width: 800, height: 385,dimmer:false}">添加社区</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-sm-12 am-scrollable-horizontal">
                            <table width="100%" class="am-text-nowrap am-table am-table-compact am-table-striped tpl-table-black am-table-hover">
                                <thead>
                                    <tr>
                                        <th>顺序</th>
                                        <th>游戏</th>
                                        <th>状态</th>
                                        <th>地址</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@foreach($list as $k)
                                        <tr class="gradeX">
                                            <td>{{ $k->sort }}</td>
                                            <td class="am-text-middle">{{ $k->name }}</td>
                                            <td class="am-text-middle">
												@if($k->is_show === 0)
                                               		 <a href="{{ url('config/community/'.$k->id.'?is_show=0')}}" class="am-badge am-badge-danger am-text-sm">下线</a>
                                                @elseif($k->is_show == 1)
                                                     <a href="{{ url('config/community/'.$k->id.'?is_show=1')}}" class="am-badge am-badge-success am-text-sm">上线</a>
                                                @endif
                                            </td>
                                            <td class="am-text-middle">
                                                <a href="{{ $k->address}}"> {{ $k->address}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- more data -->
                                </tbody>
                            </table>
                        </div>
                        <div class="am-u-lg-12 am-cf">
                            	{{ $list->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="1" id="doc-modal-1">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
        <br>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
        <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">
                        {{ isset($community->id)?'修改栏目':'添加栏目' }}
                    </div>
                </div>
                <div class="widget-body am-fr">

                    <form class="am-form tpl-form-border-form tpl-form-border-br" method="post" action="{{isset($community->id)?url('config/community/'.$community->id):url('config/community')}}" enctype="multipart/form-data" data-am-validator>
                        @if(isset($community->id))
                            {{ method_field('PUT') }}
                        @endif
                       
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">社区名<span class="tpl-form-line-small-title">Name</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="name" placeholder="填写社区名" value="" required>
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">地址<span class="tpl-form-line-small-title">Address</span></label>
                            <div class="am-u-sm-9">
                                <input type="text" name="address" placeholder="填写地址" value="{{ isset($community->address)?$community->address:''}}" required>
                                <div>

                                </div>
                            </div>
                        </div>
                       <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">顺序<span class="tpl-form-line-small-title">Sort</span></label>
                            <div class="am-u-sm-9">
                                <input type="number" name="sort" placeholder="默认0，数字大优先，同级新发布排前" value="{{ isset($community->sort)?$community->sort:''}}" max="200">
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <div class="am-u-sm-9 am-u-sm-push-3">
                                <a href="javascript:;" class="am-btn am-btn-warning tpl-btn-bg-color-success" data-am-modal-close>返回</a>
                                <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success ">提交</button>
                            </div>
                        </div>
                    </form>
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