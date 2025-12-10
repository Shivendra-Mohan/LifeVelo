<!DOCTYPE html>
<html>
<head>
    <title>Reset Exit Popup Cookie</title>
    <style>
        body {
            background: #101112;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: rgba(39, 40, 41, 0.5);
            border-radius: 12px;
            border: 1px solid rgba(145, 75, 241, 0.3);
        }
        button {
            background: rgb(145, 75, 241);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        button:hover {
            background: rgb(165, 95, 255);
        }
        .success {
            color: #4ade80;
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍪 Reset Exit Popup Cookie</h1>
        <p>Click the button below to delete the exit popup cookie.<br>This will allow the popup to show again.</p>

        <button onclick="resetCookie()">Reset Cookie</button>
        <button onclick="window.location.href='<?php echo home_url('/'); ?>'">Go to Homepage</button>

        <div class="success" id="success">
            ✅ Cookie deleted! The exit popup will show again.<br>
            <small>Redirecting to homepage in 2 seconds...</small>
        </div>
    </div>

    <script>
        function resetCookie() {
            // Delete the cookie
            document.cookie = 'exit_popup_shown=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';

            // Show success message
            document.getElementById('success').style.display = 'block';

            // Redirect to homepage after 2 seconds
            setTimeout(() => {
                window.location.href = '<?php echo home_url('/'); ?>';
            }, 2000);
        }
    </script>
</body>
</html>
