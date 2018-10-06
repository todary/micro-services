<?php
namespace App\DataTypes;

use App\DataTypes\DataTypeInterface;
use Illuminate\Support\Facades\Validator;

/**
 * DataType Abstract Class
 */
class DataType implements \ArrayAccess, DataTypeInterface
{
    protected $type;
    protected $value;
    protected $source;
    protected $data;
    protected $extras;

    protected function validate($inputs)
    {
        $data = array_filter($inputs);
        $validator = Validator::make($data, static::RULES);

        if ($validator->fails()) {
            throw new \Exception('Invalid Data: ' . print_r($data, true) . '\nReason: ' .
                print_r($validator->errors()->messages(), true));
        }
    }

    public static function create(array $data, string $source, array $extras = [])
    {
        $dataType = new static;

        $dataType->normalizeInputs($data);
        $dataType->validate($data);
        foreach ($data as $index => $value) {
            $dataType->data[$index] = $value;
        }

        $dataType->source = $source;
        $dataType->extras = $extras;

        return $dataType;
    }

    public function formatData($data): string
    {
        if (!empty($this->type)) {
            switch ($this->type) {
                case 'nicknames':
                case 'relatives':
                    $this->type = 'names';
                    // set diffrent [nickname|relatives] formation type for formatting service
                default:
                    $formatter = loadService('formatter');
                    $formatted = $formatter->format(new \ArrayIterator([$this->type => [$data]]));
                    return $formatted[$this->type][0]['formatted'];
            }
        }
    }

    protected function normalizeInputs(array &$data)
    {
        if (!empty($data['end']) && strtolower($data['end']) == 'present') {
            $data['end'] = null;
        }
    }

    public function setValues(string $value, string $source, array $extras = [])
    {
        $this->value = $value;
        $this->source = $source;
        $this->extras = $extras;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function appendToExtras($offset, $value)
    {
        if (is_null($offset)) {
            $this->extras[] = $value;
        } else {
            $this->extras[$offset] = $value;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getExtras()
    {
        return $this->extras;
    }

    public function __get($property)
    {
        $methodName = 'get' . ucfirst($property);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return isset($this->data[$property]) ? $this->data[$property] : null;
    }

    public static function getMainValues(\Iterator $dataTypes): \Iterator
    {
        $mainValues = new \ArrayIterator();
        $dataTypes->rewind();
        while ($dataTypes->valid()) {
            $dataType = $dataTypes->current();
            $mainValue = $dataType->value;
            if (!empty($mainValue)) {
                $mainValues->append($mainValue);
            }
            $dataTypes->next();
        }
        return $mainValues;
    }

    public static function getMainValue(self $dataType)
    {
        return $dataType->value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
