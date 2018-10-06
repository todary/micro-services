<?php

namespace App\Libraries;

use Aws\DynamoDb\Marshaler;

class FlexMarchal extends Marshaler
{
    public function marshalValue($value)
    {
        $type = gettype($value);

        if ($type === 'integer' || $type === 'double' || is_numeric($value)) {
            $type = 'N';
            $value = (string) $value;
        } elseif ($type === 'string' && $value !== '') {
            $type = 'S';
        } elseif ($type === 'boolean') {
            $type = 'BOOL';
        } elseif ($type === 'NULL') {
            $type = 'NULL';
            $value = true;
        } elseif ($type === 'array'
                || $value instanceof \Traversable
                || $value instanceof \stdClass
        ) {
            $type = $value instanceof \stdClass ? 'M' : 'L';
            $data = array();
            $expectedIndex = -1;
            $dataSkipped = false;

            $allNumbers = true;
            $allStrings = true;
            foreach ($value as $k => $v) {
                if ($v!==0 && $v!=="0" && $v!==array() && !$v) {
                    ++$expectedIndex;
                    $dataSkipped=true;
                    continue;
                }
    
                $data[$k] = $v;
                if ($type === 'L' && (!is_int($k) || $k != ++$expectedIndex)) {
                    $type = 'M';
                }

                if (!is_numeric($v)) {
                    $allNumbers = false;
                }
                if (!is_string($v)) {
                    $allStrings = false;
                }
            }

            if ($dataSkipped && $type === 'L') {
                $data = array_values($data);
            }

            if ($value instanceof \Traversable) {
                if ($type === 'L' && $allNumbers) {
                    $type = 'NS';
                } else if ($type === 'L' && $allStrings) {
                    $type = 'SS';
                }
            }


            if ($data!==array() && $type != 'SS' && $type != 'NS') {
                foreach ($data as $k => $v) {
                    $data[$k] = $this->marshalValue($v);
                }
            }

            $value = $data;
        } else {
            $type = $type === 'object' ? get_class($value) : $type;
            throw new \UnexpectedValueException('Marshaling error: ' . ($value
                    ? "encountered unexpected type \"{$type}\"."
                    : 'encountered empty value.'
            ));
        }
    
        return array($type => $value);
    }
    
    public function unmarshalItem(array $data, $mapAsObject = false)
    {
        return $this->unmarshalValue(array('M' => $data), $mapAsObject);
    }

    public function unmarshalItems(array $data, $mapAsObject = false)
    {
        $items=array();
        foreach ($data as $item) {
            $items[] = $this->unmarshalItem($item);
        }

        return $items;
    }
    
    /**
     * Unmarshal a value from a DynamoDB operation result into a native PHP
     * value. Will return a scalar, array, or (if you set $mapAsObject to true)
     * stdClass value.
     *
     * @param array $value       Value from a DynamoDB result.
     * @param bool  $mapAsObject Whether maps should be represented as stdClass.
     *
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function unmarshalValue(array $value, $mapAsObject = false)
    {
        list($type, $value) = each($value);
        switch ($type) {
            case 'S':
            case 'BOOL':
                return $value;
            case 'NULL':
                return null;
            case 'N':
                // Use type coercion to unmarshal numbers to int/float.
                return $value + 0;
            case 'M':
                if ($mapAsObject) {
                    $data = new \stdClass;
                    foreach ($value as $k => $v) {
                        $data->$k = $this->unmarshalValue($v, $mapAsObject);
                    }
                    return $data;
                }
                // NOBREAK: Unmarshal M the same way as L, for arrays.
            case 'L':
                foreach ($value as $k => $v) {
                    $value[$k] = $this->unmarshalValue($v, $mapAsObject);
                }
                return $value;
            case 'B':
                return $value;
            case 'SS':
            case 'NS':
            case 'BS':
                foreach ($value as $k => $v) {
                    $value[$k] = $this->unmarshalValue([$type[0] => $v]);
                }
                return $value;
        }

        throw new \UnexpectedValueException("Unexpected type: {$type}.");
    }
}
