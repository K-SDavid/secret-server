<?php

namespace App\Services\ResponseFormatter;

use Illuminate\Http\Response;
use Spatie\ArrayToXml\ArrayToXml;

class XmlResponseFormatter implements ResponseFormatterInterface
{
    public function format($data, $status)
    {
        $data = ArrayToXml::convert($data);
        return response($data, $status)
            ->header('Content-Type', 'application/xml');
    }
}