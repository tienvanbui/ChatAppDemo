import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPath: import.meta.env.VITE_PUSHER_PATH,
    wsPort: import.meta.env.VITE_PUSHER_PATH ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "http") === "https",
    encrypted: true,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
});
