document.addEventListener('DOMContentLoaded', function() {
    // Show loading screen
    const loadingScreen = document.getElementById('loading-screen');
    
    // Hide loading screen when page is fully loaded
    window.addEventListener('load', function() {
        setTimeout(function() {
            loadingScreen.classList.add('hidden');
            
            // Remove loading screen from DOM after animation completes
            setTimeout(function() {
                loadingScreen.remove();
            }, 500);
        }, 500); // You can adjust this delay as needed
    });
    
    // Also hide loading screen if there's an error
    window.addEventListener('error', function() {
        loadingScreen.classList.add('hidden');
        setTimeout(function() {
            loadingScreen.remove();
        }, 500);
    });
});