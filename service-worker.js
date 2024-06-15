self.addEventListener('push', event => {
    const data = event.data.json();
    const options = {
        body: data.message,
        icon: 'Logo Centered.png', // Replace with your icon path
    };
    event.waitUntil(
        self.registration.showNotification('Pitch Booking', options)
    );
});