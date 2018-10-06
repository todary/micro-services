<?php

namespace Skopenow\PeopleData;

interface ResultNormalizerInterface
{
    public function normalize(OutputModel $result);
}
