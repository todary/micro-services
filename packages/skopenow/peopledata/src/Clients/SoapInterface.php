<?php
namespace Skopenow\PeopleData\Clients;

interface SoapInterface extends ClientInterface
{
    public function __call($function_name, $arguments);
    public function __construct($wsdl, array $options = []);
    public function __doRequest($request, $location, $action, $version, $one_way = 0);
    public function __getFunctions();
    public function __getLastRequest();
    public function __getLastRequestHeaders();
    public function __getLastResponse();
    public function __getLastResponseHeaders();
    public function __getTypes();
    public function __setCookie($name, $value);
    public function __setLocation($new_location);
    public function __setSoapHeaders($soapheaders);
    public function __soapCall($function_name, $arguments, $options, $input_headers, &$output_headers);
}
