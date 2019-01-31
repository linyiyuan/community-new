@extends('admin.common.common')


@section('title')

@if(isset($role->id))
	修改角色
@else
	添加角色
@endif
@stop

@section('content')
 <div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">
									@if(isset($role->id))
										修改角色
									@else
										添加角色
									@endif
                                </div>
                            </div>
                            <div class="widget-body am-fr">

                                <form class="am-form tpl-form-line-form" method="post" action="{{ isset($role->id)?url('admin/role').'/'.$role->id:url('admin/role') }}">
                                	{{ csrf_field() }}
                                	@if(isset($role->id))
										{{ method_field('PUT') }}
                                	@endif
                                    <div class="am-form-group">
                                        <label for="user-name" class="am-u-sm-3 am-form-label">角色名(唯一性)<span class="tpl-form-line-small-title">Role</span></label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input" id="user-name" placeholder="请输入角色名" name="name" value="{{ $role->name?	$role->name:''}}" {{ $role->id?'disabled':''}} >
                                            <small>请填写角色名文字8-10左右。</small>
                                        </div>
                                    </div>
								
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label">角色展示名称 <span class="tpl-form-line-small-title"></span></label>
                                        <div class="am-u-sm-9">
                                            <input type="text" placeholder="请输入角色展示名称" name="display_name" value="{{ $role->display_name?$role->display_name:''}}" >
                                        </div>
                                    </div>

                                    <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">角色描述</label>
                                        <div class="am-u-sm-9">
                                            <textarea class="" rows="10" id="user-intro" placeholder="请输入角色描述" name="description">{{ $role->description? $role->description:''}}</textarea>
                                        </div>
                                    </div>
                                    @if($role->id != '1')
                                    <div class="am-form-group">
                                            <label for="user-intro" class="am-u-sm-3 am-form-label">权限选择</label></br>
                                            <div class="am-u-sm-9">
                                                @foreach($permissionsList as $key => $val)
                                                    <div>
                                                       <span class="am-badge am-badge-success am-radius am-text-sm">{{ $key }}:</span>
                                                    </div>
                                                    <span class="label label-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                      @if($role->id)
                                                          @foreach($val as $k)
                                                              <label class="am-checkbox-inline">
                                                                <input type="checkbox"  {{ array_key_exists($k->id,$role_permissions)?'checked':'' }}  name="permission[]" value="{{ $k->id}}" data-am-ucheck >{{ $k->display_name}}
                                                              </label>
                                                          @endforeach
                                                      @else
                                                         @foreach($val as $k)
                                                              <label class="am-checkbox-inline">
                                                                <input type="checkbox"  name="permission[]" value="{{ $k->id}}" data-am-ucheck >{{ $k->display_name}}
                                                              </label>
                                                          @endforeach
                                                      @endif
                                                     </br>
                                                     </br>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endif
                                    <div class="am-form-group">
                                        <div class="am-u-sm-9 am-u-sm-push-3">
                                            <a class="am-btn am-btn-warning tpl-btn-bg-color-success " href="{{ url('admin/role')}}">返回</a>
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
