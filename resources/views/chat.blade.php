<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Realtime Messenger</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #020617;
        }

        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</head>
<body class="min-h-screen text-white">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <div class="w-[320px] bg-slate-950 border-r border-slate-800 hidden md:flex flex-col">

        <div class="p-6 border-b border-slate-800">

            <h1 class="text-2xl font-bold">
                Messenger
            </h1>

            <p class="text-slate-400 text-sm mt-1">
                Login sebagai
            </p>

            <div class="mt-3 bg-slate-800 px-4 py-3 rounded-2xl">
                <p class="font-semibold text-cyan-400">
                    {{ $user->name }}
                </p>
            </div>

        </div>

        <div class="p-6">

            <h2 class="text-sm uppercase tracking-widest text-slate-500 mb-4">
                Online Users
            </h2>

            <div id="online-users" class="space-y-3">

            </div>

        </div>

    </div>

    <!-- CHAT -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER -->
        <div class="border-b border-slate-800 bg-slate-900 px-6 py-5 flex justify-between items-center">

            <div>
                <h2 class="font-bold text-xl">
                    Chat Room
                </h2>

                <p class="text-slate-400 text-sm">
                    Realtime Messaging
                </p>
            </div>

            <div class="bg-green-500/20 text-green-400 px-4 py-2 rounded-full text-sm font-semibold">
                ● Connected
            </div>

        </div>

        <!-- CHAT BOX -->
        <div
            id="chat-box"
            class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-900 chat-scroll"
        >

            @foreach($messages as $message)

                @if($message->user_id == $user->id)

                    <div class="flex justify-end">

                        <div class="max-w-[70%]">

                            <div class="bg-cyan-500 text-white px-5 py-3 rounded-3xl rounded-br-md shadow-lg">
                                {{ $message->message }}
                            </div>

                            <p class="text-xs text-slate-500 mt-1 text-right">
                                You
                            </p>

                        </div>

                    </div>

                @else

                    <div class="flex justify-start">

                        <div class="max-w-[70%]">

                            <div class="bg-slate-800 text-white px-5 py-3 rounded-3xl rounded-bl-md shadow-lg">
                                {{ $message->message }}
                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $message->user->name }}
                            </p>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

        <!-- FORM -->
        <div class="p-5 border-t border-slate-800 bg-slate-950">

            <form id="chat-form" class="flex gap-4">

                <input
                    type="text"
                    id="message-input"
                    placeholder="Type a message..."
                    class="flex-1 bg-slate-800 border border-slate-700 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-cyan-500"
                    required
                >

                <button
                    type="submit"
                    class="bg-cyan-500 hover:bg-cyan-600 transition px-8 rounded-2xl text-white font-bold"
                >
                    Send
                </button>

            </form>

        </div>

    </div>

</div>

<script>

const USER_ID = {{ $user->id }};
const USER_NAME = "{{ $user->name }}";

const chatBox = document.getElementById('chat-box');
const form = document.getElementById('chat-form');
const input = document.getElementById('message-input');

chatBox.scrollTop = chatBox.scrollHeight;

function appendMyMessage(message)
{
    chatBox.innerHTML += `
        <div class="flex justify-end">
            <div class="max-w-[70%]">
                <div class="bg-cyan-500 text-white px-5 py-3 rounded-3xl rounded-br-md shadow-lg">
                    ${message}
                </div>
                <p class="text-xs text-slate-500 mt-1 text-right">
                    You
                </p>
            </div>
        </div>
    `;

    chatBox.scrollTop = chatBox.scrollHeight;
}

function appendOtherMessage(name, message)
{
    chatBox.innerHTML += `
        <div class="flex justify-start">
            <div class="max-w-[70%]">
                <div class="bg-slate-800 text-white px-5 py-3 rounded-3xl rounded-bl-md shadow-lg">
                    ${message}
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    ${name}
                </p>
            </div>
        </div>
    `;

    chatBox.scrollTop = chatBox.scrollHeight;
}

form.addEventListener('submit', async function(e) {

    e.preventDefault();

    const message = input.value;

    if(message.trim() === '') return;

    appendMyMessage(message);

    await fetch(`/send-message/${USER_ID}`, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },

        body: JSON.stringify({
            message: message
        })

    });

    input.value = '';

});

window.Echo.join('chat-room')

    .here((users) => {

        let html = '';

        users.forEach(user => {

            html += `
                <div class="bg-slate-800 px-4 py-3 rounded-2xl">
                    🟢 ${user.name}
                </div>
            `;
        });

        document.getElementById('online-users').innerHTML = html;

    })

    .joining((user) => {

        document.getElementById('online-users').innerHTML += `
            <div id="user-${user.id}" class="bg-slate-800 px-4 py-3 rounded-2xl">
                🟢 ${user.name}
            </div>
        `;

    })

    .leaving((user) => {

        document.getElementById(`user-${user.id}`)?.remove();

    })

    .listen('.message.sent', (e) => {

        if(e.user.id != USER_ID)
        {
            appendOtherMessage(
                e.user.name,
                e.message.message
            );
        }

    });

</script>

</body>
</html>