<?php
namespace Skopenow\HttpRequests\Interfaces;

interface ProxyRetryRuleInterface
{
    public function resolveOptions(
        $request,
        $options,
        \Psr\Http\Message\ResponseInterface $response = null,
        $exception = null
    );
}
