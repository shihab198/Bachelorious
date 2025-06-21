document.addEventListener('DOMContentLoaded', function() {
    // Property Search and Filter
    const searchForm = document.getElementById('property-search-form');
    const propertyGrid = document.getElementById('property-grid');
    
    if(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            searchProperties();
        });
        
        // Also trigger search when filter inputs change
        const filterInputs = this.querySelectorAll('input, select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                searchProperties();
            });
        });
    }
    
    function searchProperties() {
        const formData = new FormData(searchForm);
        const params = new URLSearchParams();
        
        // Append form data to params
        formData.forEach((value, key) => {
            if(value) params.append(key, value);
        });
        
        // Fetch API to get filtered properties
        fetch('search_properties.php?' + params.toString())
            .then(response => response.text())
            .then(data => {
                if(propertyGrid) {
                    propertyGrid.innerHTML = data;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Sort Properties
    const sortSelect = document.getElementById('sort-properties');
    if(sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    }
});