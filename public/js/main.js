// Main JavaScript for Office Management System

// Store selected country for city API call
let selectedCountry = '';

// Load countries from API
async function loadCountries(isEdit = false) {
    const countrySelect = document.getElementById('country');
    if (!countrySelect) return;

    try {
        const response = await fetch('/api/location/countries');
        if (!response.ok) throw new Error('Failed to fetch countries');

        const countries = await response.json();
        countrySelect.innerHTML = '<option value="">Select Country</option>';

        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country.country_name;
            option.textContent = country.country_name;
            countrySelect.appendChild(option);
        });

        // If editing, set the current value and load states
        if (isEdit && countrySelect.dataset.current) {
            countrySelect.value = countrySelect.dataset.current;
            selectedCountry = countrySelect.dataset.current;
            await loadStates(countrySelect.dataset.current, true);
        }
    } catch (error) {
        console.error('Error loading countries:', error);
    }
}

// Load states based on selected country
async function loadStates(country, isEdit = false) {
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');

    if (!stateSelect) return;

    // Store selected country for city API
    selectedCountry = country;

    stateSelect.disabled = true;
    stateSelect.innerHTML = '<option value="">Loading...</option>';
    citySelect.disabled = true;
    citySelect.innerHTML = '<option value="">Select City</option>';

    if (!country) {
        stateSelect.innerHTML = '<option value="">Select State</option>';
        return;
    }

    try {
        const response = await fetch(`/api/location/states/${encodeURIComponent(country)}`);
        if (!response.ok) throw new Error('Failed to fetch states');

        const states = await response.json();
        stateSelect.innerHTML = '<option value="">Select State</option>';

        states.forEach(state => {
            const option = document.createElement('option');
            option.value = state.state_name;
            option.textContent = state.state_name;
            stateSelect.appendChild(option);
        });

        stateSelect.disabled = false;

        // If editing, set the current value and load cities
        if (isEdit && stateSelect.dataset.current) {
            stateSelect.value = stateSelect.dataset.current;
            await loadCities(stateSelect.dataset.current, true);
        }
    } catch (error) {
        console.error('Error loading states:', error);
        stateSelect.innerHTML = '<option value="">Error loading states</option>';
    }
}

// Load cities based on selected state (requires country too for CountriesNow API)
async function loadCities(state, isEdit = false) {
    const citySelect = document.getElementById('city');

    if (!citySelect) return;

    citySelect.disabled = true;
    citySelect.innerHTML = '<option value="">Loading...</option>';

    if (!state) {
        citySelect.innerHTML = '<option value="">Select City</option>';
        return;
    }

    try {
        // Pass country as query parameter for CountriesNow API
        const url = `/api/location/cities/${encodeURIComponent(state)}?country=${encodeURIComponent(selectedCountry)}`;
        const response = await fetch(url);
        if (!response.ok) throw new Error('Failed to fetch cities');

        const cities = await response.json();
        citySelect.innerHTML = '<option value="">Select City</option>';

        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.city_name;
            option.textContent = city.city_name;
            citySelect.appendChild(option);
        });

        citySelect.disabled = false;

        // If editing, set the current value
        if (isEdit && citySelect.dataset.current) {
            citySelect.value = citySelect.dataset.current;
        }
    } catch (error) {
        console.error('Error loading cities:', error);
        citySelect.innerHTML = '<option value="">Error loading cities</option>';
    }
}

// Event listeners for location dropdowns
document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');

    if (countrySelect) {
        countrySelect.addEventListener('change', function () {
            loadStates(this.value);
        });
    }

    if (stateSelect) {
        stateSelect.addEventListener('change', function () {
            loadCities(this.value);
        });
    }
});
