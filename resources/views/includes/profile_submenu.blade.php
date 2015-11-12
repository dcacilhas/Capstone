<ul class="nav nav-tabs nav-justified">
    <li role="presentation" class="{{ isActiveRoute('profile') }}">
        {!! link_to_route('profile', 'Home', ['username' => $user->username]) !!}
    </li>
    @if (Auth::check() && Auth::user()->username === $user->username)
        <li role="presentation" class="dropdown {{ areActiveRoutes(['profile.edit.profile', 'profile.edit.account']) }}">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">
                Edit <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li role="presentation" class="{{ isActiveRoute('profile.edit.profile') }}">
                    {!! link_to_route('profile.edit.profile', 'Profile', ['username' => $user->username]) !!}
                </li>
                <li role="presentation" class="{{ isActiveRoute('profile.edit.account') }}">
                    {!! link_to_route('profile.edit.account', 'Account', ['username' => $user->username]) !!}
                </li>
            </ul>
        </li>
    @endif
    <li role="presentation" class="{{ isActiveRoute('profile.list') }}">
        {!! link_to_route('profile.list', 'List', ['username' => $user->username]) !!}
    </li>
    <li role="presentation" class="{{ areActiveRoutes(['profile.list.history', 'profile.list.history.show']) }}">
        {!! link_to_route('profile.list.history', 'History', ['username' => $user->username]) !!}
    </li>
    <li role="presentation" class="{{ isActiveRoute('profile.favourites') ? 'active' : '' }}">
        {!! link_to_route('profile.favourites', 'Favourites', ['username' => $user->username]) !!}
    </li>
    <li role="presentation" class="{{ isActiveRoute('profile.friends') ? 'active' : '' }}">
        {!! link_to_route('profile.friends', 'Friends', ['username' => $user->username]) !!}
    </li>
    @if (Auth::check() && Auth::user()->username === $user->username)
        <li role="presentation"><a href="#">Notifications</a></li>
    @endif
</ul>
