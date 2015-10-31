@extends('search.search')

@section('search_results')
    <h3>Search Users: Results for "{{ $query }}"</h3>

    @foreach($users as $user)
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-fixed-height align-center">
            <div>
                <div>
                    <a href="{{ route('profile', ['username' => $user->username]) }}">
                        {!! Html::image($user->avatar->url(), 'Avatar', ['class' => 'img-thumbnail center-block']) !!}
                    </a>
                </div>
                <div class="text-center">
                    {!! link_to_route('profile', $user->username, ['username' => $user->username]) !!}
                </div>
            </div>
        </div>
    @endforeach

    {!! $users->render() !!}
@stop

@section('css')
    <style>
        .col-fixed-height {
            height: 158px;
        }
        .align-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination {
            width: 100%;
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
@stop
