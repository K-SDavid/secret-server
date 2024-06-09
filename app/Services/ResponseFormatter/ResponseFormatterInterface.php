<?php

namespace App\Services\ResponseFormatter;

interface ResponseFormatterInterface
{
    public function format($data, $status);
}