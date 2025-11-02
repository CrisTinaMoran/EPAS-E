<?php

if (!function_exists('dynamic_asset')) {
    function dynamic_asset($path)
    {
        return app()->environment('production') || env('FORCE_HTTPS', false) 
            ? secure_asset($path) 
            : asset($path);
    }
}

if (!function_exists('dynamic_url')) {
    function dynamic_url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($secure)) {
            $secure = app()->environment('production') || env('FORCE_HTTPS', false);
        }
        
        return $secure ? secure_url($path, $parameters) : url($path, $parameters);
    }
}

if (!function_exists('dynamic_route')) {
    function dynamic_route($name, $parameters = [], $absolute = true)
    {
        $secure = app()->environment('production') || env('FORCE_HTTPS', false);
        
        if ($absolute) {
            return $secure 
                ? secure_url(route($name, $parameters, false))
                : url(route($name, $parameters, false));
        }
        
        return route($name, $parameters, $absolute);
    }
}