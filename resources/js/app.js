import './bootstrap';

const currentUser =
document.getElementById('current-user-name')?.value;

const chatBox =
document.getElementById('chat-box');

const form =
document.getElementById('chat-form');

const input =
document.getElementById('message-input');

const onlineUsers =
document.getElementById('online-users');


// AUTO SCROLL
function scrollBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

scrollBottom();


// SEND MESSAGE
form.addEventListener('submit', async (e) => {

    e.preventDefault();

    const message = input.value;

    if (!message.trim()) return;

    await fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ message })
    });

    input.value = '';
});


// REALTIME PRESENCE + CHAT
window.Echo.join('chat-room')

.here((users) => {
    renderUsers(users);
})

.joining((user) => {

    const div = document.createElement('div');
    div.id = `user-${user.id}`;
    div.className = 'bg-slate-800 rounded-2xl px-4 py-3 text-white';

    div.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center font-bold">
                ${user.name.charAt(0)}
            </div>
            <div>
                <h4 class="font-semibold">${user.name}</h4>
                <p class="text-green-400 text-xs">● Online</p>
            </div>
        </div>
    `;

    onlineUsers.appendChild(div);
})

.leaving((user) => {
    document.getElementById(`user-${user.id}`)?.remove();
})

.listen('message.sent', (e) => {

    const isMine = e.user.name === currentUser;

    const wrapper = document.createElement('div');

    wrapper.className = isMine
        ? 'flex justify-end'
        : 'flex justify-start';

    wrapper.innerHTML = `
        <div class="max-w-[70%]">

            <div class="${
                isMine ? 'bg-cyan-500' : 'bg-slate-800'
            } text-white px-5 py-3 rounded-3xl shadow-lg">

                ${e.message.message}

            </div>

            <p class="text-slate-500 text-xs mt-1 ${isMine ? 'text-right' : ''}">
                ${isMine ? 'You' : e.user.name}
            </p>

        </div>
    `;

    chatBox.appendChild(wrapper);
    scrollBottom();
});


// RENDER USERS
function renderUsers(users)
{
    onlineUsers.innerHTML = '';

    users.forEach((user) => {

        const div = document.createElement('div');

        div.id = `user-${user.id}`;
        div.className = 'bg-slate-800 rounded-2xl px-4 py-3 text-white';

        div.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center font-bold">
                    ${user.name.charAt(0)}
                </div>
                <div>
                    <h4 class="font-semibold">${user.name}</h4>
                    <p class="text-green-400 text-xs">● Online</p>
                </div>
            </div>
        `;

        onlineUsers.appendChild(div);
    });
}