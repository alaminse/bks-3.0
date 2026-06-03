<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #050d1a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: #fff;
        }
        .ad-label {
            font-size: 11px;
            color: #555;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: monospace;
        }
        .ad-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-note {
            margin-top: 20px;
            font-size: 12px;
            color: #444;
            text-align: center;
            max-width: 300px;
            line-height: 1.6;
        }
        .close-note span {
            color: #00f5ff;
        }
    </style>
</head>
<body>

    <div class="ad-label">// Advertisement</div>

    <div class="ad-container">
        {!! $task->adsterra_ad_code !!}
    </div>

    <div class="close-note">
        Keep this tab open. Return to the previous page and click
        <span>Skip & Claim</span> after the timer finishes.
    </div>

    <script>
        // Notify the parent/opener that this ad page has loaded
        // This confirms the ad tab is open and the user is viewing it
        if (window.opener && !window.opener.closed) {
            window.opener.postMessage({ type: 'AD_TAB_READY', taskId: '{{ $task->id }}' }, '*');
        }

        // When this tab is about to close, notify parent
        window.addEventListener('beforeunload', function() {
            if (window.opener && !window.opener.closed) {
                window.opener.postMessage({ type: 'AD_TAB_CLOSED', taskId: '{{ $task->id }}' }, '*');
            }
        });
    </script>

</body>
</html>
