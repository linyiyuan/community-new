@extends('admin.common.common')


@section('title')

@if(isset($user->id))
	修改管理员
@else
	添加管理员
@endif
@stop

@section('content')
 <div class="row-content am-cf">
                <div class="row">
                    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                        <div class="widget am-cf">
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">
									@if(isset($user->id))
										修改管理员
									@else
										添加管理员
									@endif
                                </div>
                            </div>
                            <div class="widget-body am-fr">

                                <form class="am-form tpl-form-line-form" method="post" action="{{ isset($user->id)?url('admin/user').'/'.$user->id:url('admin/user') }}"  enctype="multipart/form-data">
                                	{{ csrf_field() }}
                                	@if(isset($user->id))
										{{ method_field('PUT') }}
                                	@endif
                                    <div class="am-form-group">
                                        <label for="user-intro" class="am-u-sm-3 am-form-label">用户邮箱(唯一性)</label>
                                        <div class="am-u-sm-9">
                                
                                            <input type="email" id="doc-vld-email-2-1" data-validation-message="请输入正确的邮箱格式" placeholder="请输入用户邮箱" required/ name="email" value="{{ $user->email? $user->email:''}}" {{ $user->id?'disabled':''}}  >
                                            <small>请填写用户邮箱8-10左右。</small>
                                        </div>
                                    </div>

                                    <div class="am-form-group">
                                        <label for="user-name" class="am-u-sm-3 am-form-label">用户名<span class="tpl-form-line-small-title">user</span></label>
                                        <div class="am-u-sm-9">
                                            <input type="text" class="tpl-form-input" id="user-name" placeholder="请输入用户名" name="name" value="{{ $user->name? $user->name:''}}">
                                            <small>请填写用户名文字8-10左右。</small>
                                        </div>
                                    </div>

                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label">用户密码 <span class="tpl-form-line-small-title"></span></label>
                                        <div class="am-u-sm-9">
                                            <input type="password" placeholder="请输入用户密码" name="password" value="" {{ $user->id?'':'required'}}>
                                            <small>请填写用户密码在6位以上。</small>
                                        </div>
                                    </div>
                                    
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label">确定密码 <span class="tpl-form-line-small-title"></span></label>
                                        <div class="am-u-sm-9">
                                            <input type="password" placeholder="请再次输入用户密码" name="password_confirmation" value="" {{ $user->id?'':'required'}}>
                                            <small>请填写用户密码在6位以上。</small>
                                        </div>
                                    </div>
                                     <div class="am-form-group">
                                        <label for="user-phone" class="am-u-sm-3 am-form-label">角色<span class="tpl-form-line-small-title">Role</span></label>
                                        <div class="am-u-sm-9">
                                            <select multiple data-am-selected="{searchBox: 1,maxHeight: 200}" style="display: none;" name="role[]" required>
                                              @foreach($role as $k)
                                                  <option value="{{ $k->id }}" {{ $user->hasRole($k->name)?'selected':''}}>-{{ $k->display_name }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                       <div class="am-form-group">
                            <label for="user-weibo" class="am-u-sm-3 am-form-label">用户头像 <span class="tpl-form-line-small-title">Img</span></label>
                            <div class="am-u-sm-9">
                                <div class="am-form-group am-form-file">
                                    <div class="tpl-form-file-img">
                                    </div>
                                    <button type="button" class="am-btn am-btn-danger am-btn-sm">
                                        <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                                      <input class="doc-form-file" type="file" multiple name="img">
                                </div>
                                <div class="file-list">
                                    @if($user->img)<img src="{{ $user->img }}" alt="" class="title_pic" style="width:300px;height:150px">@endif
                                </div>
                            </div>
                        </div>
                                    <div class="am-form-group">
                                        <div class="am-u-sm-9 am-u-sm-push-3">
                                            <a class="am-btn am-btn-warning tpl-btn-bg-color-success " href="{{ url('admin/user')}}">返回</a>
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
