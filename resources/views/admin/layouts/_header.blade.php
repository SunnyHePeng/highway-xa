<header class="navbar-wrapper">
	<div class="navbar navbar-black navbar-fixed-top" style="border-bottom: none;">
		<div class="cl" style="height: 130px;">
			<div class="visible-lg">
				<div class="top-left">
					<div class="weather">
						<div class="weather_left">
							<img src="/static/admin/images/weather/{{$weather['code']}}.png" alt="">
						</div>
						<div class="weather_right">
							<h5>今日天气</h5>
							<p>
								<span>{{$weather['text']}}</span><br/>
								<span>温度：{{$weather['temperature_low']}}-{{$weather['temperature_high']}}℃</span>
							</p>
						</div>
					</div>
					@if($user['module_name'] != '重要通知公告')
						<div class="top_notice" data-url="{{url('notice/get_new_notice')}}">
						<span class="notice_hint">
							@if($notice)
								@if($notice['is_read']==0)
									<span><i class="Hui-tags-icon Hui-iconfont f-18 c-fff trumpet">&#xe62f;</i></span>
								@else
									<span><i class="Hui-tags-icon Hui-iconfont f-18 c-red trumpet">&#xe62f;</i></span>
									<span class="notice_hint c-red">新公告：</span>
								@endif
							@endif
						</span>
							<a href="{{url('notice/index')}}" style="text-decoration: none;color: #fff;">
								<span class="notice_box f-18">@if($notice){{$notice['title']}}@endif</span>
							</a>
						</div>
					@endif
				</div>
                <div class="topcenter">
					<a href="{{url('index')}}"><img src="/static/admin/images/top/logo.png" title="系统首页"></a>
				</div>
					<div class="nav-module">
							@if(isset($user['module_list']))
								<ul class="nav-module" data-url="{{url('index')}}" style="float: right">
									{{--@foreach($user['module_list'] as $k=>$v)--}}
									{{--@if($k < 7)--}}
									{{--<li class="change-module" data-id="{{$v['id']}}" data-is-new="{{$v['is_new']}}">--}}
									{{--<a href="javascript:;" @if($v['id'] == $user['module_id'])class="selected"@endif>--}}
									{{--<img src="/static/admin/images/icon/{{$v['icon']}}.jpg" title="{{$v['name']}}">--}}
									{{--<h2>{{$v['name']}}</h2>--}}
									{{--</a>--}}
									{{--</li>--}}
									{{--@endif--}}
									{{--@endforeach--}}

									{{--@if($k >= 7)--}}
									<li>
			    		<span class="dropDown"> <a class="dropDown_A" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="Hui-iconfont" style="font-size:60px;color:#fff;">&#xe610;</i></a>
							<ul class="dropDown-menu menu radius box-shadow" data-url="{{url('index')}}">
				    		@foreach($user['module_list'] as $k=>$v)
									{{--@if($k >= 7)--}}
										<li class="change-module" data-id="{{$v['id']}}" data-is-new="{{$v['is_new']}}">
									<a href="javascript:;" @if($v['id'] == $user['module_id'])class="selected"@endif>
										{{$v['name']}}
									</a>
								</li>
									{{--@endif--}}
								@endforeach
				    		</ul>
						</span>
									</li>
									{{--@endif--}}
								</ul>
							@endif
					</div>
			    <div class="topright">
					<ul>
						<li class="dropDown dropDown_hover" style="padding-right: 4px">
							<a href="#" class="dropDown_A" style="padding: 0 6px">帮助</a>
							<ul class="dropDown-menu menu radius box-shadow" style="padding:0;left:-66px">
								<li style="padding:0;background:none;width:100%"><a href="/static/admin/files/operating_manual.doc" style="color:#000">信息化管理系统使用说明书V1.0</a></li>
								<li style="padding:0;background:none;width:100%"><a href="/static/admin/files/video_help.doc" style="color:#000">实时视频预览帮助</a></li>
								<li style="padding:0;background:none;width:100%"><a href="/static/admin/files/patch.doc" style="color:#000">补丁包安装帮助</a></li>
							</ul>
						</li>
					    <li><a href="{{url('manage/logout')}}">退出</a></li>
				    </ul>
				    <div class="user">
					    <span>{{$user['role_name']}} {{$user['username']}}</span>
					    <!-- <i>消息</i>
					    <b>5</b> -->
				    </div>

			    </div>
		    </div>
		    <div class="header-container"> 
				<a href="{{url('index')}}" class="logo navbar-logo f-l mr-10 hidden-lg navbar-home"><i class="Hui-iconfont">&#xe625;</i></a>
				<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-lg">
					<ul class="cl">
						@if(isset($user['module_list']))
						<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A">切换模块<i class="Hui-iconfont">&#xe6d5;</i></a>
							<ul class="dropDown-menu menu radius box-shadow" data-url="{{url('index')}}">
							    @foreach($user['module_list'] as $v)
							    @if($v['id'] != 1)
							    	<li class="change-module" data-id="{{$v['id']}}"><a href="javascript:;">{{$v['name']}}</a></li>
								@endif
							    @endforeach
							</ul>
						</li>
						@endif
						<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A">{{$user['role_name']}} {{$user['username']}} <i class="Hui-iconfont">&#xe6d5;</i></a>
							<ul class="dropDown-menu menu radius box-shadow">
								<li><a href="{{url('manage/logout')}}">切换账户</a></li>
								<li><a href="{{url('manage/logout')}}">退出</a></li>
							</ul>
						</li>
						<li>
							<a aria-hidden="false" class="navbar-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</header>