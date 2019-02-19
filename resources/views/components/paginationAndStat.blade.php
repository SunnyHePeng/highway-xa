<div class="dataTables_info">共 <strong>{{ $_paginate->lastPage() }}</strong>页/<strong>{{ $_paginate->total() }}</strong>条</div>
<div class="dataTables_paginate">
    {!! $_paginate->appends(request()->query())->render() !!}
</div>