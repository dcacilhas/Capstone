<ul class="nav nav-pills nav-justified">
    <li role="presentation"
        class="{{ (Request::route()->getName() == 'profile') ? 'active' : '' }}">{!! link_to_route('profile', 'Profile', ['username' => $user->username]) !!}</li>
    @if (Auth::check() && Auth::user()->username === $user->username)
        <li role="presentation"
            class="{{ (Request::route()->getName() == 'profile/edit') ? 'active' : '' }}">{!! link_to_route('profile/edit', 'Edit Profile', ['username' => $user->username]) !!}</li>
        <li role="presentation"
            class="{{ (Request::route()->getName() == 'profile/account') ? 'active' : '' }}">{!! link_to_route('profile/account', 'Edit Account', ['username' => $user->username]) !!}</li>
    @endif
    <li role="presentation" class="{{ (Request::route()->getName() == 'profile/list') ? 'active' : '' }}">{!! link_to_route('profile/list', 'List', ['username' => $user->username]) !!}</li>
    <li role="presentation" class="{{ (Request::route()->getName() == 'profile/list/watchHistory') ? 'active' : '' }}">{!! link_to_route('profile/list/watchHistory', 'Watch History', ['username' => $user->username]) !!}</li>
    <li role="presentation"class="{{ (Request::route()->getName() == 'profile/list/favourites') ? 'active' : '' }}">{!! link_to_route('profile/favourites', 'Favourites', ['username' => $user->username]) !!}</li>
    <li role="presentation"><a href="#">Friends</a></li>
    @if (Auth::check() && Auth::user()->username === $user->username)
        <li role="presentation"><a href="#">Notifications</a></li>
    @endif
</ul>
