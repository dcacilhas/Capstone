<ul class="nav nav-tabs nav-justified">
    <li role="presentation" class="dropdown @if (isset($selectedFilter)) {{ 'active' }} @endif">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
           aria-expanded="false">
            Shows Starting With <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-column columns-3">
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($filters, 0, 9) as $filter)
                        <li role="presentation" class="@if (isset($selectedFilter) && $selectedFilter == $filter) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $filter, ['filter' => $filter]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($filters, 9, 9) as $filter)
                        <li class="@if (isset($selectedFilter) && $selectedFilter == $filter) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $filter, ['filter' => $filter]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($filters, 18, 9) as $filter)
                        <li class="@if (isset($selectedFilter) && $selectedFilter == $filter) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $filter, ['filter' => $filter]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </li>
    <li role="presentation" class="dropdown @if (isset($selectedGenre)) {{ 'active' }} @endif">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
           aria-expanded="false">
            Genres <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-column columns-3">
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($genres, 0, 9) as $genre)
                        <li class="@if (isset($selectedGenre) && $selectedGenre == $genre->genre) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $genre->genre, ['genre' => $genre->genre]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($genres, 9, 9) as $genre)
                        <li class="@if (isset($selectedGenre) && $selectedGenre == $genre->genre) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $genre->genre, ['genre' => $genre->genre]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="col-sm-4">
                <ul class="multi-column-dropdown">
                    @foreach(array_slice($genres, 18, 9) as $genre)
                        <li class="@if (isset($selectedGenre) && $selectedGenre == $genre->genre) {{ 'active' }} @endif">
                            {!! link_to_route('shows', $genre->genre, ['genre' => $genre->genre]) !!}
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </li>
</ul>