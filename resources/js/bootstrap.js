function beaconTopbarBind(userId) {
    if (!window.Echo || !userId) return;

    const channel = `App.Models.User.${userId}`;


    window.Echo.private(channel)
        .notification((notification) => {
            window.dispatchEvent(new CustomEvent('beacon:notification', { detail: notification }));

            if (document.visibilityState !== 'visible') return;
            if (!('Notification' in window)) return;

            if (Notification.permission === 'granted') {
                const n = new Notification(notification.title ?? 'Notification', {
                    title: notification.title ?? 'Notification',
                    icon: notification.icon ?? null,
                    body: notification.body ?? '',
                    data: { url: '/users' },
                });
                n.onclick = () => {
                    window.focus();
                    if (notification.url) {
                        window.location.href = notification.url;
                    }
                };
            }


        });

    async function enableDesktopToasts() {
        const perm = await Notification.requestPermission();
        return perm === 'granted';
    }
}

window.beaconTopbarBind = beaconTopbarBind;