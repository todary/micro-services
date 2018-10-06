<?php
namespace Skopenow\PeopleData\Clients;

class SoapClient extends \SoapClient implements SoapInterface
{
    protected $_wsdl;
    protected $_options;

    public function __construct($wsdl, array $options = array())
    {
        $this->_wsdl = $wsdl;
        $this->_options = $options;

        parent::__construct($wsdl, $options);
    }

    public function call($request)
    {
        list($url, $data, $action) = $request;
        $handle   = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);

        // If you need to handle headers like cookies, session id, etc. you will have
        // to set them here manually
        $headers = array("Content-Type: text/xml", 'SOAPAction: "' . $action . '"');
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($handle, CURLOPT_HEADER, false);


        curl_setopt($handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($handle, CURLOPT_ENCODING, "");
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($handle, CURLOPT_REFERER, $url);
        curl_setopt($handle, CURLOPT_MAXREDIRS, 5);
    
        if (PHP_VERSION<7 && defined("CURLOPT_SAFE_UPLOAD")) {
            curl_setopt($handle, CURLOPT_SAFE_UPLOAD, false);
        }
            
        curl_setopt($handle, CURLOPT_NOSIGNAL, 1);

        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);

        if (isset($this->_options['connection_timeout'])) {
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->_options['connection_timeout']);
            curl_setopt($handle, CURLOPT_TIMEOUT, $this->_options['connection_timeout']);
        }

        if (isset($this->_options['proxy_host']) && isset($this->_options['proxy_port'])) {
            curl_setopt($handle, CURLOPT_PROXY, $this->_options['proxy_host'] . ":" . $this->_options['proxy_port']);
        }
        
        if (isset($this->_options['proxy_login']) && isset($this->_options['proxy_password'])) {
            curl_setopt($handle, CURLOPT_PROXYUSERPWD, $this->_options['proxy_login'] . ":" . $this->_options['proxy_password']);
        }

        $response = curl_exec($handle);

        if ($response === false) {
            $error = curl_error($handle);
            $error_no = curl_errno($handle);

            // \Yii::trace("SoapRequest error#$error_no: $error!");
            // if (get_class(\Yii::app())=="CConsoleApplication" and \Yii::app()->params['runCommandLog']){echo "SoapRequest error#$error_no: $error!\n";}

            throw new \Exception("SoapRequest error#$error_no: $error!");
        }

        curl_close($handle);
        
        return $response;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        return $this->call([$location, $request, $action]);
    }
}
