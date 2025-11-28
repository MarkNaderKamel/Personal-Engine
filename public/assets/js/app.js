async function fetchUnreadNotifications() {
    try {
        const response = await fetch('/api/notifications/unread');
        const notifications = await response.json();
        
        const badge = document.getElementById('notificationCount');
        if (badge && notifications.length > 0) {
            badge.textContent = notifications.length;
            badge.style.display = 'inline';
        } else if (badge) {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
}

if (document.getElementById('notificationCount')) {
    fetchUnreadNotifications();
    setInterval(fetchUnreadNotifications, 30000);
}

document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
