@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="utf-pagination-wrap">
        <ul class="utf-pagination">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled arrow" aria-disabled="true">
                    <span class="page-link">&laquo; Prev</span>
                </li>
            @else
                <li class="page-item arrow">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item arrow">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a>
                </li>
            @else
                <li class="page-item disabled arrow" aria-disabled="true">
                    <span class="page-link">Next &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>

    @once
        <style>
            /* === Site-wide pagination (clean dark active + outlined Prev/Next) === */
            .utf-pagination-wrap { display: flex; justify-content: center; padding: 18px 0; }
            .utf-pagination {
                display: inline-flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 6px;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .utf-pagination .page-item { margin: 0; }
            .utf-pagination .page-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 42px;
                height: 42px;
                padding: 0 14px;
                font-size: 14px;
                font-weight: 600;
                color: #1a1a1a;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                text-decoration: none;
                line-height: 1;
                transition: background .15s ease, color .15s ease, border-color .15s ease, transform .12s ease;
                cursor: pointer;
                box-shadow: none;
                outline: none;
            }
            .utf-pagination .page-link:focus { box-shadow: 0 0 0 3px rgba(10,10,10,.10); }
            .utf-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
                background: #f3f4f6;
                border-color: #0a0a0a;
                color: #0a0a0a;
                transform: translateY(-1px);
            }
            .utf-pagination .page-item.active .page-link {
                background: #0a0a0a;
                color: #fff;
                border-color: #0a0a0a;
                box-shadow: 0 4px 10px rgba(10,10,10,.18);
                cursor: default;
            }
            .utf-pagination .page-item.disabled .page-link {
                color: #9ca3af;
                background: #fff;
                border-color: #e5e7eb;
                cursor: not-allowed;
                opacity: .65;
            }
            .utf-pagination .arrow .page-link {
                padding: 0 16px;
                font-weight: 600;
            }
            @media (max-width: 575.98px) {
                .utf-pagination .page-link { min-width: 38px; height: 38px; font-size: 13.5px; padding: 0 11px; }
                .utf-pagination .arrow .page-link { padding: 0 12px; }
            }
        </style>
    @endonce
@endif
