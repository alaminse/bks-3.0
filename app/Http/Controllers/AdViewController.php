<?php

namespace App\Http\Controllers;

use App\Models\Task;

class AdViewController extends Controller
{
    public function show($taskId)
    {
        $task = Task::findOrFail($taskId);
        $adCode = $task->adsterra_ad_code ?? '';

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 100%;
            height: 100%;
            background: #0a0a14;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-height: 180px;
        }
        .wrap {
            width: 100%;
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Force adsterra iframes to fit */
        iframe {
            max-width: 100% !important;
            border: none !important;
        }
        ins, .adsterra-ad {
            max-width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="wrap">
        '.$adCode.'
    </div>
</body>
</html>';

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('Content-Security-Policy', "frame-ancestors 'self'");
    }
}
