<?php

if (!function_exists('str_get_html') && !class_exists('simple_html_dom_node', false)) {
    require_once __DIR__ . '/../../../automation/simple_html_dom.php';
}
