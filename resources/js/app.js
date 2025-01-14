import './bootstrap';
import './echo';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

jQuery(function($){

    /* Funções */
    if (Notification.permission === "granted") {
        // Permissão já concedida
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                //alert('Permissão concedida!');
            }
        });
    }

    let $clientId = $('meta[name="client-id"]').attr('content');

    Echo.private(`channel-order-status-updated.${$clientId}`)
    .listen('.order.status.updated', (event) => {
        if (Notification.permission === "granted") {
            new Notification(event.notification.title, {
                body: event.notification.body,
                icon: event.notification.icon,
                tag: event.notification.tag
            });
        }

        $(`.orders #status-${event.orderId}, .order #status-${event.orderId}`).html(event.tagStatus);
        $('.page-order #order-statuses').html(event.htmlStatus);

    });
    
    /* Funções */
});

