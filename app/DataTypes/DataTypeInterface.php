<?php
namespace App\DataTypes;

/**
 * DataType Interface
 */
interface DataTypeInterface
{
    // public function __construct();
    public function setValues(string $value, string $source, array $extras = []);
    //public function fromArray(array $value, string $source);
}
