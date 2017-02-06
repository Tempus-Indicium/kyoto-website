<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Source: https://gist.github.com/petehouston/b9e10d81464e537b341590781a6bfedc
        Response::macro('xml', function(array $vars, $status = 200, array $header = [], $rootElement = 'response', $xml = null)
        {
            if (is_null($xml)) {
                $xml = new \SimpleXMLElement('<'.$rootElement.'/>');
            }

            foreach ($vars as $key => $value) {
                if (is_array($value)) {
                    Response::xml($value, $status, $header, $rootElement, $xml->addChild(is_numeric($key) ? "item" : $key));
                } else {
                    if( preg_match('/^@.+/', $key) ) {
                        $attributeName = preg_replace('/^@/', '', $key);
                        $xml->addAttribute($attributeName, $value);
                    } else {
                        $xml->addChild($key, $value);
                    }
                }
            }

            if (empty($header)) {
                $header['Content-Type'] = 'application/xml';
            }

            return Response::make($xml->asXML(), $status, $header);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
