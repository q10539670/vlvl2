<div class="col-sm-4">
    <div class="dataTables_info">
        当前显示 <span class="badge bg-green">{{$paginator->total()>0?$paginator->perPage()*($paginator->currentPage()-1)+1:0}}</span>
        到
        <span class="badge bg-green">{{$paginator->total()>($paginator->perPage()*$paginator->currentPage())?$paginator->perPage()*$paginator->currentPage():$paginator->total()}}</span>
        条 总共查询到
        <span class="badge bg-aqua">{{$paginator->total()}}</span>
        条数据
    </div>
</div>

<div class="col-sm-8">
    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
        @if ($paginator->hasPages())
            <ul class="pagination" id="pagination-li-s">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="paginate_button previous disabled" aria-disabled="true"
                        aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="paginate_button previous">
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="paginate_button active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li class="paginate_button"><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="paginate_button next">
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="paginate_button next disabled" aria-disabled="true"
                        aria-label="@lang('pagination.next')">
                        <span aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
                <li class="hidden-xs" style="margin-left: 10px;">
                    <div class="input-group" id="pagination-table">
                        <input type="text" id="page-index" class="form-control" style="width: 45px;" placeholder="{{request()->page??1}}">
                        <span class="input-group-btn">
                          <button id="page-jump" type="button" class="btn btn-info">跳转</button>
                        </span>
                    </div>
                </li>
            </ul>
        @endif
    </div>
</div>
