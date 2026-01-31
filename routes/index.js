const express = require('express');
const router = express.Router();
const Department = require('../models/Department');
const Employee = require('../models/Employee');

// Dashboard
router.get('/', async (req, res) => {
    try {
        const [departmentCount, employeeCount, recentEmployees] = await Promise.all([
            Department.countDocuments(),
            Employee.countDocuments(),
            Employee.find()
                .populate('department', 'name')
                .sort({ createdAt: -1 })
                .limit(5)
        ]);

        res.render('index', {
            title: 'Dashboard',
            stats: {
                departments: departmentCount,
                employees: employeeCount
            },
            recentEmployees
        });
    } catch (error) {
        console.error(error);
        res.render('index', {
            title: 'Dashboard',
            stats: { departments: 0, employees: 0 },
            recentEmployees: []
        });
    }
});

module.exports = router;
