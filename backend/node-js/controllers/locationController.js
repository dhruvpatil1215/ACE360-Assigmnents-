const axios = require('axios');

const API_BASE_URL = 'https://countriesnow.space/api/v0.1/countries';

// Get all countries
exports.getCountries = async (req, res) => {
    try {
        const response = await axios.get(`${API_BASE_URL}`);

        if (response.data && response.data.data) {
            // Format response to match frontend expectations
            const countries = response.data.data.map(item => ({
                country_name: item.country
            }));
            res.json(countries);
        } else {
            res.json([]);
        }
    } catch (error) {
        console.error('Error fetching countries:', error.message);
        res.status(500).json({ error: 'Failed to fetch countries' });
    }
};

// Get states by country
exports.getStates = async (req, res) => {
    try {
        const country = req.params.country;

        const response = await axios.post(`${API_BASE_URL}/states`, {
            country: country
        });

        if (response.data && response.data.data && response.data.data.states) {
            // Format response to match frontend expectations
            const states = response.data.data.states.map(item => ({
                state_name: item.name
            }));
            res.json(states);
        } else {
            res.json([]);
        }
    } catch (error) {
        console.error('Error fetching states:', error.message);
        res.status(500).json({ error: 'Failed to fetch states' });
    }
};

// Get cities by state and country
exports.getCities = async (req, res) => {
    try {
        const state = req.params.state;
        const country = req.query.country || '';

        const response = await axios.post(`${API_BASE_URL}/state/cities`, {
            country: country,
            state: state
        });

        if (response.data && response.data.data) {
            // Format response to match frontend expectations
            const cities = response.data.data.map(cityName => ({
                city_name: cityName
            }));
            res.json(cities);
        } else {
            res.json([]);
        }
    } catch (error) {
        console.error('Error fetching cities:', error.message);
        res.status(500).json({ error: 'Failed to fetch cities' });
    }
};
