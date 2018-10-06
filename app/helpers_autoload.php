<?php

foreach (new \DirectoryIterator(__DIR__ . '/Helpers') as $fileinfo) {
    if ($fileinfo->isFile()) {
        include_once $fileinfo->getPathname();
    }
}
