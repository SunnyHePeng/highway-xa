<div class="dataTables_info">共 <strong>{{ $last_page }}</strong>页/<strong>{{ $total }}</strong>条</span></div>
<div class="dataTables_paginate paging_simple_numbers">
  <?php
    $show_page = 10;     
    if($last_page >$show_page){
      if($current_page > $show_page){
        $endpage = ($last_page - $current_page) >= $show_page ? ($current_page + $show_page) : $last_page;
        $startpage = $endpage - $show_page;
      }else{
        $startpage = 1;
        $endpage = $show_page;
      }
    }else{
      $startpage = 1;
      $endpage = $last_page;
    }
    if(strpos($url, '?')){
      $url .= '&';
    }else{
      $url .= '?';
    }
    $next_page = $current_page+1;
    $pre_page = $current_page-1;
  ?>
  @if($current_page== 1)
  <a class="paginate_button" href="javascript:;">首页</a>
  <a class="paginate_button" href="javascript:;">上一页</a>
  @else
  <a class="paginate_button" href="{{ url($url) }}">首页</a>
  <a class="paginate_button" href="{{ url($url.'page='.$pre_page) }}">上一页</a>
  @endif
  
  @for($i=$startpage;$i<=$endpage;$i++)
    @if($i == $current_page)
    <a class="paginate_button current" href="javascript:;">{{ $i }}</a>
    @else
    <a class="paginate_button" href="{{ url($url.'page='.$i) }}">{{ $i }}</a>
    @endif
  @endfor

  @if($current_page < $last_page)
  <a class="paginate_button" href="{{ url($url.'page='.$next_page) }}">下一页</a>
  <a class="paginate_button" href="{{ url($url.'page='.$last_page) }}">末页</a>  
  @else
  <a class="paginate_button" href="javascript:;">下一页</a>
  <a class="paginate_button" href="javascript:;">末页</a>
  @endif
</ul>   
