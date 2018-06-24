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
