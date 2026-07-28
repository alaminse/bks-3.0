<?php

namespace App\Helpers;

class AdHelper
{
    public static function wrapForModal(string $adCode, int $width = 300, int $height = 250): string
    {
        $html = '<!DOCTYPE html><html><head>'
            . '<meta charset="utf-8">'
            . '<style>'
            . 'html,body{margin:0;padding:0;background:#0a0a14;display:flex;align-items:center;justify-content:center;min-height:' . $height . 'px;overflow:hidden;}'
            . '</style>'
            . '</head><body>'
            . $adCode
            . '</body></html>';

        return $html;
    }

    public static function detectType(string $adCode): string
    {
        if (str_contains($adCode, 'effectivecpmnetwork.com/ab')) return 'native';
        if (str_contains($adCode, 'effectivecpmnetwork.com/1b')) return 'popunder';
        if (str_contains($adCode, 'effectivecpmnetwork.com/e0')) return 'socialbar';
        if (str_contains($adCode, 'highperformanceformat.com')) return 'banner';
        if (preg_match('/^https?:\/\//', trim($adCode))) return 'smartlink';
        return 'unknown';
    }
}
