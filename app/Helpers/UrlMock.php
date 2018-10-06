<?php

function setUrlMock($url, $contents, $headers = ["HTTP/1.1 200 OK"])
{
    $url = rtrim($url, '/?&');
    $key = md5(str_replace("https://", "http://", $url));
    config(["HTTPRequests.mock.$key" => ["url"=>$url, "contents"=>$contents, "headers"=>$headers]]);
}
