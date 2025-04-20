document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on menu button click
    const menuToggles = document.querySelectorAll('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuToggle = Array.from(menuToggles).some(toggle => toggle.contains(event.target));
        
        if (!isClickInsideSidebar && !isClickOnMenuToggle && window.innerWidth < 768 && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
    
    // Responsive adjustments
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
});