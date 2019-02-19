<aside class="Hui-aside left_menu_down">
	<div class="left-menu"><span></span>选择菜单</div>
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<div class="menu_dropdown bk_2">
		@if($menu)
          	@foreach($menu as $v)
          	<dl id="menu-article">
				<dt>
				@if(isset($v['child']) && !empty($v['child']))
				<i class="Hui-iconfont">@if(isset($v['icon']) && !empty($v['icon'])){{$v['icon']}}@endif</i> {{ $v['name'] }}
				<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
				<dd @if($active && $active['pid'] == $v['id'])style="display:block"@endif>
					<ul>
						@foreach($v['child'] as $ck=>$cv)
						<li @if($active && $active['url'] == $cv['url'])class="current"@endif><a href="{{url($cv['url'])}}" title="{{ $cv['name'] }}">{{ $cv['name'] }}</a></li>
						@endforeach
					</ul>
				</dd>
				@else
				<a href="{{url($v['url'])}}" @if($active && $active['url'] == $v['url'])class="current"@endif><i class="Hui-iconfont">{{$v['icon']}}</i> {{ $v['name'] }}</a>
				</dt>
				@endif
			</dl>
          	@endforeach
      	@else
        	获取菜单出错
      	@endif
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>