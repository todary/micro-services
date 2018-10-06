<?php 

namespace Skopenow\Matching\Interfaces;

interface AnalyzerInterface
{
    public function isMatch() : bool;
}