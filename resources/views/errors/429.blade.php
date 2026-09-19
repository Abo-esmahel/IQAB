<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwindcss.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: '#f8f9fa',
                            100: '#eef0f2',
                            200: '#dfe2e6',
                            300: '#c3c8cf',
                            400: '#9aa2ad',
                            500: '#727a86',
                            600: '#565d68',
                            700: '#3f454e',
                            800: '#262b33',
                            900: '#161a21',
                            950: '#0a0d13',
                        },
                        primary: {
                            50: '#fdf9ec',
                            100: '#f9f0cd',
                            200: '#f2df9c',
                            300: '#eac968',
                            400: '#e2b342',
                            500: '#d89c2b',
                            600: '#b8861f',
                            700: '#8f6518',
                            800: '#754f19',
                            900: '#634317',
                            950: '#38250b',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark-950 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-7xl sm:text-9xl font-bold text-white mb-4">429</h1>
        <p class="text-xl text-dark-400 mb-8">Too many requests. Please slow down and try again later.</p>
        <a href="/" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
            Go Home
        </a>
    </div>
</body>
</html>
