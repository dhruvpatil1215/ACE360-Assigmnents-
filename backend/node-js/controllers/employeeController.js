const Employee = require('../models/Employee');
const Department = require('../models/Department');

// List employees with pagination, search, and filtering
exports.index = async (req, res) => {
    try {
        const page = parseInt(req.query.page) || 1;
        const limit = parseInt(req.query.limit) || 10;
        const search = req.query.search || '';
        const departmentFilter = req.query.department || '';
        const sortField = req.query.sortField || 'createdAt';
        const sortOrder = req.query.sortOrder === 'asc' ? 1 : -1;

        // Build query
        let query = {};

        if (search) {
            query.$or = [
                { firstName: { $regex: search, $options: 'i' } },
                { lastName: { $regex: search, $options: 'i' } },
                { email: { $regex: search, $options: 'i' } },
                { jobTitle: { $regex: search, $options: 'i' } }
            ];
        }

        if (departmentFilter) {
            query.department = departmentFilter;
        }

        const total = await Employee.countDocuments(query);
        const totalPages = Math.ceil(total / limit);

        const employees = await Employee.find(query)
            .populate('department', 'name')
            .populate('supervisor', 'firstName lastName')
            .sort({ [sortField]: sortOrder })
            .skip((page - 1) * limit)
            .limit(limit);

        const departments = await Department.find().sort({ name: 1 });

        res.render('employees/index', {
            title: 'Employees',
            employees,
            departments,
            pagination: {
                page,
                limit,
                total,
                totalPages,
                hasNext: page < totalPages,
                hasPrev: page > 1
            },
            filters: {
                search,
                department: departmentFilter,
                sortField,
                sortOrder: sortOrder === 1 ? 'asc' : 'desc'
            }
        });
    } catch (error) {
        console.error(error);
        req.flash('error', 'Error fetching employees');
        res.redirect('/');
    }
};

// API endpoint for DataTables server-side processing
exports.datatable = async (req, res) => {
    try {
        const draw = parseInt(req.query.draw) || 1;
        const start = parseInt(req.query.start) || 0;
        const length = parseInt(req.query.length) || 10;
        const search = req.query.search?.value || '';
        const departmentFilter = req.query.department || '';

        // Build query
        let query = {};

        if (search) {
            query.$or = [
                { firstName: { $regex: search, $options: 'i' } },
                { lastName: { $regex: search, $options: 'i' } },
                { email: { $regex: search, $options: 'i' } },
                { jobTitle: { $regex: search, $options: 'i' } }
            ];
        }

        if (departmentFilter) {
            query.department = departmentFilter;
        }

        const totalRecords = await Employee.countDocuments();
        const filteredRecords = await Employee.countDocuments(query);

        const employees = await Employee.find(query)
            .populate('department', 'name')
            .populate('supervisor', 'firstName lastName')
            .sort({ createdAt: -1 })
            .skip(start)
            .limit(length);

        const data = employees.map(emp => ({
            _id: emp._id,
            fullName: emp.fullName,
            email: emp.email,
            jobTitle: emp.jobTitle,
            department: emp.department?.name || 'N/A',
            supervisor: emp.supervisor ? `${emp.supervisor.firstName} ${emp.supervisor.lastName}` : 'None',
            hireDate: emp.hireDate ? new Date(emp.hireDate).toLocaleDateString() : 'N/A'
        }));

        res.json({
            draw,
            recordsTotal: totalRecords,
            recordsFiltered: filteredRecords,
            data
        });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error fetching employees' });
    }
};

// Show create form
exports.create = async (req, res) => {
    try {
        const departments = await Department.find().sort({ name: 1 });
        const supervisors = await Employee.find().select('firstName lastName').sort({ firstName: 1 });

        res.render('employees/create', {
            title: 'Add Employee',
            employee: {},
            departments,
            supervisors
        });
    } catch (error) {
        req.flash('error', 'Error loading form');
        res.redirect('/employees');
    }
};

// Store new employee
exports.store = async (req, res) => {
    try {
        const employeeData = {
            firstName: req.body.firstName,
            lastName: req.body.lastName,
            email: req.body.email,
            phone: req.body.phone,
            jobTitle: req.body.jobTitle,
            salary: req.body.salary,
            hireDate: req.body.hireDate,
            department: req.body.department,
            supervisor: req.body.supervisor || null,
            country: req.body.country,
            state: req.body.state,
            city: req.body.city,
            address: req.body.address
        };

        await Employee.create(employeeData);
        req.flash('success', 'Employee added successfully');
        res.redirect('/employees');
    } catch (error) {
        if (error.code === 11000) {
            req.flash('error', 'Email already exists');
        } else {
            req.flash('error', error.message || 'Error creating employee');
        }
        res.redirect('/employees/create');
    }
};

// Show single employee
exports.show = async (req, res) => {
    try {
        const employee = await Employee.findById(req.params.id)
            .populate('department')
            .populate('supervisor', 'firstName lastName email');

        if (!employee) {
            req.flash('error', 'Employee not found');
            return res.redirect('/employees');
        }

        // Find subordinates
        const subordinates = await Employee.find({ supervisor: employee._id })
            .select('firstName lastName jobTitle');

        res.render('employees/show', {
            title: employee.fullName,
            employee,
            subordinates
        });
    } catch (error) {
        req.flash('error', 'Error fetching employee');
        res.redirect('/employees');
    }
};

// Show edit form
exports.edit = async (req, res) => {
    try {
        const employee = await Employee.findById(req.params.id);
        if (!employee) {
            req.flash('error', 'Employee not found');
            return res.redirect('/employees');
        }

        const departments = await Department.find().sort({ name: 1 });
        const supervisors = await Employee.find({ _id: { $ne: employee._id } })
            .select('firstName lastName')
            .sort({ firstName: 1 });

        res.render('employees/edit', {
            title: 'Edit Employee',
            employee,
            departments,
            supervisors
        });
    } catch (error) {
        req.flash('error', 'Error loading form');
        res.redirect('/employees');
    }
};

// Update employee
exports.update = async (req, res) => {
    try {
        const employeeData = {
            firstName: req.body.firstName,
            lastName: req.body.lastName,
            email: req.body.email,
            phone: req.body.phone,
            jobTitle: req.body.jobTitle,
            salary: req.body.salary,
            hireDate: req.body.hireDate,
            department: req.body.department,
            supervisor: req.body.supervisor || null,
            country: req.body.country,
            state: req.body.state,
            city: req.body.city,
            address: req.body.address
        };

        await Employee.findByIdAndUpdate(req.params.id, employeeData, { runValidators: true });
        req.flash('success', 'Employee updated successfully');
        res.redirect('/employees');
    } catch (error) {
        if (error.code === 11000) {
            req.flash('error', 'Email already exists');
        } else {
            req.flash('error', error.message || 'Error updating employee');
        }
        res.redirect(`/employees/${req.params.id}/edit`);
    }
};

// Delete employee
exports.destroy = async (req, res) => {
    try {
        // Update subordinates to remove supervisor reference
        await Employee.updateMany(
            { supervisor: req.params.id },
            { $set: { supervisor: null } }
        );

        await Employee.findByIdAndDelete(req.params.id);
        req.flash('success', 'Employee deleted successfully');
        res.redirect('/employees');
    } catch (error) {
        req.flash('error', 'Error deleting employee');
        res.redirect('/employees');
    }
};
