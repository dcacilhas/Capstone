<?php

// From: https://laracasts.com/discuss/channels/general-discussion/whats-the-cleanest-way-to-add-the-active-class-to-bootstrap-link-components/replies/4321

/**
 * Compare given route with current route and return output if they match.
 * Very useful for navigation, marking if the link is active.
 *
 * @param $route
 * @param string $output
 * @return string
 */
function isActiveRoute($route, $output = "active")
{
    if (Route::currentRouteName() == $route) return $output;
}

/**
 * Compare given routes with current route and return output if they match.
 * Very useful for navigation, marking if the link is active.
 *
 * @param array $routes
 * @param string $output
 * @return string
 */
function areActiveRoutes(Array $routes, $output = "active")
{
    foreach ($routes as $route)
    {
        if (Route::currentRouteName() == $route) return $output;
    }

}
