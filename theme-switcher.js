/**
 * Theme Switcher Functionality
 * Handles dynamic theme switching with smooth transitions and persistence
 * Saves user theme preference to server and updates UI instantly
 */

/**
 * Changes the site theme dynamically
 * @param {string} themePath - Path to the new theme CSS file
 */
function changeTheme(themePath) {
    // Fade out body content during theme switch for smooth transition
    document.body.style.opacity = '0';
    
    // Send AJAX request to save theme preference to server
    fetch('includes/save_theme.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'theme=' + encodeURIComponent(themePath)
    })
    .then(response => response.text())
    .then(data => {
        // Create a new link element for the selected theme
        const newLink = document.createElement('link');
        newLink.rel = 'stylesheet';
        newLink.href = '/DigitalNovels/' + themePath;
        
        // Add load listener to fade the body back in once theme is loaded
        newLink.onload = () => {
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 50);
        };
        
        // Find the current theme link and replace it with the new one
        const currentThemeLink = document.querySelector('link[href*="themes"]');
        if (currentThemeLink) {
            currentThemeLink.parentNode.replaceChild(newLink, currentThemeLink);
        }
    })
    .catch(error => {
        // Handle errors gracefully - restore visibility if theme switch fails
        console.error('Error switching theme:', error);
        document.body.style.opacity = '1';
    });
}
