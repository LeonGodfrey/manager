@extends('vendor/pagination/default')

@section('paginator')
    <div class="pb-2">
        <nav aria-label="Contacts Page Navigation">
            <ul class="pagination m-0">
                @foreach ($paginator as $page => $data)
                    <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $data['url'] }}">{{ $data['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
@endsection