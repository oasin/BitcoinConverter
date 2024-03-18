<?php

/*
 * Copyright (c) 2023.
 */

use Composer\Factory;

if (!function_exists('project_root_path')) {
    /**
     * Get project root path.
     *
     * @param  string  $path
     * @return string
     */
    function project_root_path($path = '')
    {
        return path_helper . phpdirname(Factory::getComposerFile()) . DIRECTORY_SEPARATOR . $path;
    }
}
