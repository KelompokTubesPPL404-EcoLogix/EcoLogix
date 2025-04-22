document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const sidebar = document.querySelector('.sidebar');
    const contentContainer = document.querySelector('.content-container');
    const menuToggles = document.querySelectorAll('.menu-toggle, .btn-bars');

    // Initialize sidebar state - collapsed on mobile, expanded on desktop
    if (window.innerWidth < 768) {
        sidebar.classList.add('collapsed');
        contentContainer.classList.add('sidebar-collapsed');
    }

    // Toggle sidebar on menu button click
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            sidebar.classList.toggle('collapsed');
            contentContainer.classList.toggle('sidebar-collapsed');
            
            // Add overlay for mobile when sidebar is expanded
            if (window.innerWidth < 768 && !sidebar.classList.contains('collapsed')) {
                addOverlay();
            } else {
                removeOverlay();
            }

            // Store user preference in localStorage
            localStorage.setItem('sidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        });
    });
    
    // Create and handle overlay for mobile
    function addOverlay() {
        let overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        `;
        document.body.appendChild(overlay);
        
        overlay.addEventListener('click', function() {
            sidebar.classList.add('collapsed');
            contentContainer.classList.add('sidebar-collapsed');
            removeOverlay();
        });
    }
    
    function removeOverlay() {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuToggle = Array.from(menuToggles).some(toggle => 
                toggle.contains(event.target)
            );
            
            if (!isClickInsideSidebar && !isClickOnMenuToggle && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                contentContainer.classList.add('sidebar-collapsed');
                removeOverlay();
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            removeOverlay();
        } else if (!sidebar.classList.contains('collapsed')) {
            addOverlay();
        }
    });

    // Load user preference from localStorage (if available)
    const savedState = localStorage.getItem('sidebarState');
    if (savedState) {
        if (savedState === 'collapsed') {
            sidebar.classList.add('collapsed');
            contentContainer.classList.add('sidebar-collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            contentContainer.classList.remove('sidebar-collapsed');
            
            // Add overlay if on mobile and sidebar is expanded
            if (window.innerWidth < 768) {
                addOverlay();
            }
        }
    }
});