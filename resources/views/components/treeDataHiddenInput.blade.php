<input type="hidden" value=@json($tree_data = auth()->user()->getProjectTreeData()) id="tree_data">
<input type="hidden" value="{{ \App\Extend\Helpers::resolveZtreeSearchInRequest($tree_data) }}" id="tree_name">
<input type="hidden" value="{{ url(request()->path()) }}" id="tree_url">
<input type="hidden" value="" id="tree_page">