<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiment Complete</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            min-height: 100vh;
            color: #f4efe6;
            background: radial-gradient(circle at top, rgba(241,181,107,0.18), transparent 24%), linear-gradient(160deg, #07111c, #0d2030);
        }

        .font-ar {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
        }
    </style>
</head>
<body class="grid place-items-center px-4 py-8">
    <main class="w-full max-w-2xl rounded-[2rem] border border-white/10 bg-white/5 p-8 text-center backdrop-blur-xl md:p-12">
        <p class="text-xs uppercase tracking-[0.35em] text-white/45">Experiment complete</p>
        <h1 class="mt-4 text-4xl font-bold">Thank you for participating.</h1>
        <p class="font-ar mt-4 text-2xl text-white/80" dir="rtl">شكرًا لمشاركتك في التجربة.</p>
        <p class="mt-6 text-white/68">Responses recorded: {{ $responsesCount }} videos</p>
        <p class="mt-2 text-sm text-white/45">Participant token: {{ $participant->public_token }}</p>
        <a href="{{ route('experiment.landing') }}" class="mt-8 inline-flex rounded-full border border-white/10 px-6 py-3 text-sm text-white/80 transition hover:bg-white/8">
            Start another participant
        </a>
    </main>
</body>
</html>
