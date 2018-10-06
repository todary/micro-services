<?php
namespace Skopenow\PeopleData\Clients;

class CURLClient implements ClientInterface
{
    public function call($request)
    {
        try {
            $entry = loadService('HttpRequestsService');
            
            $options = [];
            $options['max_retries'] = 2;
            $options['timeout'] = app()->environment(['production'])?2:10;
            $options['connect_timeout'] = app()->environment(['production'])?2:10;
            $options['ignore_auto_select_ip'] = 1;

            $response = $entry->fetch($request, 'GET', $options);
            $response->getBody()->rewind();
            $output = $response->getBody()->getContents();
            \Log::debug('Request: ' . $request, [$output]);
            return $output;
        } catch (\Exception $ex) {
            notifyDevForException($ex);
            // throw $ex;
        }
    }
}
