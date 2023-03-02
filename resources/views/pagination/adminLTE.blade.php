@if ($paginator->hasPages())
<ul class="pagination pagination-sm m-0 float-left">
    <li class="page-item"><a class="page-link">共&nbsp;&nbsp;{{$paginator->total()}}&nbsp;&nbsp;筆</a></li>
    <li class="page-item">
        <a class="page-link">
            <select onchange="javascript:location.href=$(this).val()">
            @foreach ($elements as $element)    
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                    <option value="{{$url}}" @if ($page == $paginator->currentPage()){{'selected'}}@endif>{{ $page }}頁</option>
                    @endforeach
                @endif
            @endforeach
            </select>
        </a>
    </li>
    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">«</a></li>
    @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page-item"><a class="page-link" href="#">{{ $element }}</a></li>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <li class="page-item"><a class="page-link" href="#">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach
    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">»</a></li>
</ul>
@endif