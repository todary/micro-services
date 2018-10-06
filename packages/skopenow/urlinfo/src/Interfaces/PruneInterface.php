<?php
namespace App\Interfaces;

interface PruneInterface
{
    public function prepareContent(string $url) : string;
}
