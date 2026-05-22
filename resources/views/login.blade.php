<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Chat</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-slate-950 min-h-screen flex items-center justify-center">

<div class="bg-slate-900 p-10 rounded-3xl shadow-2xl w-full max-w-md border border-slate-800">

    <h1 class="text-3xl font-bold text-white mb-2">
        💬 Realtime Chat
    </h1>

    <p class="text-slate-400 mb-8">
        Choose user to continue
    </p>

    <div class="space-y-4">

        @foreach($users as $user)

            <form action="/login-user" method="POST">
                @csrf

                <input
                    type="hidden"
                    name="user_id"
                    value="{{ $user->id }}"
                >

                <button
                    class="w-full bg-cyan-500 hover:bg-cyan-600 transition text-white py-4 rounded-2xl font-bold"
                >
                    Login as {{ $user->name }}
                </button>

            </form>

        @endforeach

    </div>

</div>

</body>
</html>