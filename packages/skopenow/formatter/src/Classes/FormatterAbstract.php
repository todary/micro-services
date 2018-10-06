<?php

/**
 * abstract class for formatter Classes
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Formatter\Classes;

/**
 * abstract class for formatter Classes
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
abstract class FormatterAbstract
{
	/**
	 * [$inputs description]
	 * 
	 * @var array
	 */
	protected $inputs = [];

	/**
     * [__construct set full name value in it]
     * 
     * @param array $inputs [description]
     */
    public function __construct(\ArrayIterator $inputs)
    {
        $this->inputs = $inputs;
    }

	/**
     * [format Formate input and set it in its attr]
     * 
     * @return [ArrayIterator] [object of NameFormatter]
     */
    public function format()
    {
        $formattedInputs = new \ArrayIterator;
        foreach ($this->inputs as $input) {
            $newInput["original"] = $input;
            $newInput["formatted"] = $this->formatSingle($input);
            $formattedInputs->append($newInput);
        }
        return $formattedInputs;
    }
	

	/**
	 * [formatSingle description]
	 * 
	 * @param  \ArrayIterator $singleInput [description]
	 * @return [\ArrayIterator]                [description]
	 */
	abstract protected function formatSingle($singleInput);
}

?>