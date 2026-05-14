import './bootstrap';

window.Echo.channel('photo-channel')
    .listen('.photo.liked', (event) => {

        const likeElement =
            document.getElementById(`likes-${event.photo.id}`);

        likeElement.innerText = event.photo.likes;
    });