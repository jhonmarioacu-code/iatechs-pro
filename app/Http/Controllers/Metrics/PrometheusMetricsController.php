<?php

declare(strict_types=1);

namespace App\Http\Controllers\Metrics;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Support\Observability\PrometheusMetricsExporter;

class PrometheusMetricsController extends Controller
{
    public function __invoke(PrometheusMetricsExporter $exporter): Response
    {
        return response(
            $exporter->render(),
            200,
            ['Content-Type' => 'text/plain; version=0.0.4; charset=utf-8']
        );
    }
}

