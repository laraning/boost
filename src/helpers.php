<?php

if (!function_exists('d')) {
    /**
     * Same as dd() but doesn't die.
     *
     * @param ... $args The arguments.
     */
    function d(...$args)
    {
        foreach ($args as $x) {
            (new Dumper())->dump($x);
        }
    }
}

if (!function_exists('get_uri_collection')) {
    /**
     * Gets the uri collection (segments).
     *
     * @return \Illuminate\Support\Collection
     */
    function get_uri_collection()
    {
        $router = app()->make('router');
        $segments = collect(explode('/', $router->getCurrentRoute()->uri));

        return $segments;
    }
}

if (!function_exists('get_uri_collection_without_bindings')) {
    /**
     * Gets the uri collection without bindings.
     *
     * @return \Illuminate\Support\Collection
     */
    function get_uri_collection_without_bindings()
    {
        $uri = get_uri_collection();

        // Remove { .. } items.
        $segments = $uri->filter(function ($value, $key) {
            return $value[0] != '{';
        });

        return $segments;
    }
}
