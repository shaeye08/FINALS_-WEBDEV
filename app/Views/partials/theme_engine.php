<script>
    /**
     * ====================================================================
     * 🌗 CORE SYSTEM THEME VECTOR INITIALIZATION
     * ====================================================================
     * Executes early in the header rendering pipeline to eliminate white screen flashes.
     */
    (function () {
        const savedTheme = localStorage.getItem('assetflow_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    })();

    /**
     * Global utility function to change application display matrices.
     * Committed out to DOM state and device disk storage.
     */
    function toggleSystemTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-bs-theme', targetTheme);
        localStorage.setItem('assetflow_theme', targetTheme);
        
        // Dispatch custom event to let running canvas modules (like Chart.js) re-render scales
        window.dispatchEvent(new Event('themeChanged'));
    }
</script>