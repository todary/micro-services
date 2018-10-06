<?php

/**
 * FormaterEntrypoint
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
namespace Skopenow\Formatter;

use Skopenow\Formatter\Classes\FormaterEntrypointInterface;
use Skopenow\Formatter\Classes\FormatterInterface;

/**
 * FormaterEntrypoint
 *
 * @category Class
 * @package  MyPackage
 * @author   Queen tech <info@queentechsolutions.net>
 * @license  license http://www.queentechsolutions.net/
 * @link     http://www.queentechsolutions.net/
 */
class EntryPoint implements FormaterEntrypointInterface
{
    /**
     * [format description]
     *
     * @return \Iterator $inputs [description]
     */
    
    public function format(\Iterator $inputs)
    {
        foreach ($inputs as $method => $inputsArr) {
            $inputsArr = new \ArrayIterator($inputsArr);

            switch ($method) {
                case 'names':
                    $class = 'NameFormatter';
                    break;
                case 'addresses':
                    $class = 'AddressFormatter';
                    break;
                case 'phones':
                    $class = 'PhoneFormatter';
                    break;
                case 'websites':
                    $class = 'WebsiteFormatter';
                    break;
                case 'emails':
                    $class = 'EmailFormatter';
                    break;
                default:
                    $class ="";
                    break;
            }
            $class = 'Skopenow\Formatter\Classes\\'.$class;
            
            if (class_exists($class)) {
                $formater =  new $class($inputsArr);
            } else {
                throw new \Exception('Invalid Formatter type: "' . $method . '"');
            }

            $output[$method] = $formater->format();
        }

        $loggerData["input"] = $inputs;
        $loggerData["format_type"] = $method;
        $loggerData["output"] = $output;

        $state = [
            "report_id" => config("state.report_id"),
            "combination_id" => config("state.combination_id"),
            "combination_level_id" => config("state.combination_level_id"),
            "environment" => env("APP_ENV")
        ];

        $logger = loadService("logger", [130]);
        $logger->addLog($state, $loggerData);

        return new \ArrayIterator($output);
    }
}
