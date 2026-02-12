$(document).ready(function () {
    // Sound file (simple beep)
    const notificationSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); // Simple beep
    // Or use a local asset if available. For now using external URL for simplicity as requested "sound notifications".
    // Better to use a reliable source or base64.

    function fetchNotifications() {
        $.ajax({
            url: '/admin/notifications/unread-count', // Route to get count
            type: 'GET',
            success: function (response) {
                let badge = $('#notification-count');
                let currentCount = parseInt(badge.text()) || 0;

                if (response.count > 0) {
                    badge.text(response.count);
                    badge.show();

                    // Play sound if count increased
                    if (response.count > currentCount) {
                        try {
                            notificationSound.play().catch(e => console.log('Sound blocked by browser policy'));
                        } catch (e) { }
                    }
                } else {
                    badge.hide();
                    badge.text('');
                }
            }
        });

        // Also fetch latest items for the dropdown
        $.ajax({
            url: '/admin/notifications/get',
            type: 'GET',
            success: function (notifications) {
                let list = $('#notification-items');
                list.empty();

                if (notifications.length === 0) {
                    list.append('<li class="text-center">No new notifications</li>');
                    return;
                }

                notifications.forEach(function (notif) {
                    let timeAgo = timeSince(new Date(notif.created_at));
                    let icon = 'bell';
                    let color = 'primary';

                    if (notif.data.type == 'order') { icon = 'shopping-bag'; color = 'success'; }
                    else if (notif.data.type == 'return') { icon = 'rotate-ccw'; color = 'warning'; }
                    else if (notif.data.type == 'stock') { icon = 'alert-triangle'; color = 'danger'; }
                    else if (notif.data.type == 'review') { icon = 'star'; color = 'info'; }

                    let linkUrl = (notif.data.link && notif.data.link !== 'undefined') ? notif.data.link : '';
                    let html = `
                        <li class="b-l-${color} border-4" style="cursor: pointer;" onclick="markAsRead('${notif.id}', '${linkUrl}')">
                            <div class="media">
                                <i data-feather="${icon}" class="font-${color} mr-3"></i>
                                <div class="media-body">
                                    <p>${notif.data.message}</p>
                                    <span>${timeAgo}</span>
                                </div>
                            </div>
                        </li>
                    `;
                    list.append(html);
                });

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });
    }

    // Initial fetch
    fetchNotifications();

    // Poll every 30 seconds
    setInterval(fetchNotifications, 30000);

    window.markAsRead = function (id, link) {
        $.ajax({
            url: '/admin/notifications/read',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                if (link && link !== '#' && link !== 'undefined' && link !== '') {
                    try {
                        let url = new URL(link);
                        window.location.href = url.pathname + url.search;
                    } catch (e) {
                        // If not a valid URL (already relative), use as is
                        window.location.href = link;
                    }
                } else {
                    fetchNotifications();
                }
            }
        });
    };

    function timeSince(date) {
        var seconds = Math.floor((new Date() - date) / 1000);
        var interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " years ago";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months ago";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours ago";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes ago";
        return Math.floor(seconds) + " seconds ago";
    }
});
