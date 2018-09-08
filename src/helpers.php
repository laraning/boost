<?php

if (!function_exists('path_separators')) {
    /**
     * Transforms your path with the right system separators.
     *
     * @param string $path Your path.
     *
     * @return string Your path but with the right separators.
     */
    function path_separators($path)
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return $path;
    }
}

if (!function_exists('public_ip')) {
    /**
     * Get server/localhost public IP.
     *
     * @return string
     */
    function public_ip()
    {
        // In case it's a localhost IP then retrieve the location from the public internet router IP.
        return request()->ip() == '127.0.0.1' ?
            @file_get_contents('https://api.ipify.org') :
            request()->ip();
    }
}

if (!function_exists('delete_files')) {
    function delete_files($collection)
    {
        if ($collection->count() > 0) {
            $collection->each(function ($item) {
                if (is_file($item)) {
                    File::delete($item);
                }

                if (is_dir($item)) {
                    File::deleteDirectory($item);
                }
            });
        }
    }
}
