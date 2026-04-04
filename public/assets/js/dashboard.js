// Sidebar functionality
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const overlay = document.getElementById('overlay');
const mainContent = document.querySelector('div[style*="margin-left: 250px"]');
const profileButton = document.getElementById('profileButton');
const profileDropdown = document.getElementById('profileDropdown');

// Handle responsive behavior
function handleResize() {
    if (window.innerWidth <= 768) {
        // Mobile: hide sidebar by default
        sidebar.style.transform = 'translateX(-100%)';
        sidebarToggle.style.display = 'flex';
        if (mainContent) mainContent.style.marginLeft = '0';
    } else {
        // Desktop: show sidebar
        sidebar.style.transform = 'translateX(0)';
        sidebarToggle.style.display = 'none';
        overlay.style.display = 'none';
        if (mainContent) mainContent.style.marginLeft = '250px';
    }
}

// Toggle sidebar on mobile
sidebarToggle.addEventListener('click', () => {
    const isOpen = sidebar.style.transform === 'translateX(0px)';
    if (isOpen) {
        sidebar.style.transform = 'translateX(-100%)';
        overlay.style.display = 'none';
    } else {
        sidebar.style.transform = 'translateX(0)';
        overlay.style.display = 'block';
    }
});

// Close sidebar when clicking overlay
overlay.addEventListener('click', () => {
    sidebar.style.transform = 'translateX(-100%)';
    overlay.style.display = 'none';
});

// Hover effects for menu items
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('mouseenter', function() {
        if (!this.style.background) {
            this.style.background = '#f9fafb';
        }
    });
    link.addEventListener('mouseleave', function() {
        if (!this.style.background || this.style.background === 'rgb(249, 250, 251)') {
            this.style.background = '';
        }
    });
});

// Profile dropdown functionality
profileButton.addEventListener('click', (e) => {
    e.stopPropagation();
    const isVisible = profileDropdown.style.display === 'block';
    profileDropdown.style.display = isVisible ? 'none' : 'block';
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.style.display = 'none';
    }
});

// Prevent dropdown from closing when clicking inside it
profileDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
});

// Initialize on load
handleResize();
window.addEventListener('resize', handleResize);