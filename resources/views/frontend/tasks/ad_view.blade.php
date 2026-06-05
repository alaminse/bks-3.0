<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement — TopTrade</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #070710;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            color: #e8e8f0;
            padding: 20px;
        }
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 56px 56px;
        }
        .wrap {
            position: relative; z-index: 1;
            display: flex; flex-direction: column; align-items: center;
            gap: 20px; width: 100%; max-width: 480px;
        }
        .ad-header {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.62rem; text-transform: uppercase;
            letter-spacing: 0.12em; color: rgba(255,255,255,0.3);
        }
        .ad-header span {
            display: inline-block; width: 6px; height: 6px;
            border-radius: 50%; background: #00f5d4;
            animation: blink 1.5s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
        .ad-box {
            background: #111119;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            display: flex; align-items: center; justify-content: center;
            min-height: 200px;
            position: relative; overflow: hidden;
        }
        .ad-box::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, #00f5d4, transparent);
        }
        .notice {
            background: #111119;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.4);
            text-align: center;
            line-height: 1.65;
            width: 100%;
        }
        .notice strong { color: #00f5d4; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="ad-header">
            <span></span>
            Advertisement — TopTrade
        </div>

        <div class="ad-box">
            {!! $task->adsterra_ad_code !!}
        </div>

        <div class="notice">
            Keep this tab open. Return to the previous page and click
            <strong>Skip & Claim</strong> after the timer finishes.
        </div>
    </div>

    <script>
        if (window.opener && !window.opener.closed) {
            window.opener.postMessage({ type: 'AD_TAB_READY', taskId: '{{ $task->id }}' }, '*');
        }
        window.addEventListener('beforeunload', function () {
            if (window.opener && !window.opener.closed) {
                window.opener.postMessage({ type: 'AD_TAB_CLOSED', taskId: '{{ $task->id }}' }, '*');
            }
        });
    </script>
</body>
</html>
