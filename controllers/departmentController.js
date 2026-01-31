const Department = require('../models/Department');
const Employee = require('../models/Employee');

// List all departments
exports.index = async (req, res) => {
    try {
        const departments = await Department.find().sort({ name: 1 });
        res.render('departments/index', {
            title: 'Departments',
            departments
        });
    } catch (error) {
        req.flash('error', 'Error fetching departments');
        res.redirect('/');
    }
};

// Show create form
exports.create = (req, res) => {
    res.render('departments/create', {
        title: 'Create Department',
        department: {}
    });
};

// Store new department
exports.store = async (req, res) => {
    try {
        const { name, description } = req.body;
        await Department.create({ name, description });
        req.flash('success', 'Department created successfully');
        res.redirect('/departments');
    } catch (error) {
        if (error.code === 11000) {
            req.flash('error', 'Department name already exists');
        } else {
            req.flash('error', error.message || 'Error creating department');
        }
        res.redirect('/departments/create');
    }
};

// Show single department
exports.show = async (req, res) => {
    try {
        const department = await Department.findById(req.params.id);
        if (!department) {
            req.flash('error', 'Department not found');
            return res.redirect('/departments');
        }

        const employees = await Employee.find({ department: department._id })
            .populate('supervisor', 'firstName lastName');

        res.render('departments/show', {
            title: department.name,
            department,
            employees
        });
    } catch (error) {
        req.flash('error', 'Error fetching department');
        res.redirect('/departments');
    }
};

// Show edit form
exports.edit = async (req, res) => {
    try {
        const department = await Department.findById(req.params.id);
        if (!department) {
            req.flash('error', 'Department not found');
            return res.redirect('/departments');
        }
        res.render('departments/edit', {
            title: 'Edit Department',
            department
        });
    } catch (error) {
        req.flash('error', 'Error fetching department');
        res.redirect('/departments');
    }
};

// Update department
exports.update = async (req, res) => {
    try {
        const { name, description } = req.body;
        await Department.findByIdAndUpdate(req.params.id, { name, description });
        req.flash('success', 'Department updated successfully');
        res.redirect('/departments');
    } catch (error) {
        if (error.code === 11000) {
            req.flash('error', 'Department name already exists');
        } else {
            req.flash('error', error.message || 'Error updating department');
        }
        res.redirect(`/departments/${req.params.id}/edit`);
    }
};

// Delete department
exports.destroy = async (req, res) => {
    try {
        // Check if department has employees
        const employeeCount = await Employee.countDocuments({ department: req.params.id });
        if (employeeCount > 0) {
            req.flash('error', 'Cannot delete department with employees. Please reassign or remove employees first.');
            return res.redirect('/departments');
        }

        await Department.findByIdAndDelete(req.params.id);
        req.flash('success', 'Department deleted successfully');
        res.redirect('/departments');
    } catch (error) {
        req.flash('error', 'Error deleting department');
        res.redirect('/departments');
    }
};
