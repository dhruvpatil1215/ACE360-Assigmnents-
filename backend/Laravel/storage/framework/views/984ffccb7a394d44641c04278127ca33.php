

<?php $__env->startSection('title', 'Companies'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-building me-2"></i>Companies</h4>
            <a href="<?php echo e(route('companies.create')); ?>" class="btn btn-light">
                <i class="fas fa-plus me-1"></i>Add Company
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="companiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Employees</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($company->id); ?></td>
                                <td><strong><?php echo e($company->name); ?></strong></td>
                                <td><?php echo e($company->location ?? '-'); ?></td>
                                <td><?php echo e($company->email ?? '-'); ?></td>
                                <td><?php echo e($company->phone ?? '-'); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($company->employees_count); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('companies.edit', $company)); ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('companies.destroy', $company)); ?>" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this company?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No companies found. <a href="<?php echo e(route('companies.create')); ?>">Add
                                            one!</a></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function () {
            $('#companiesTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    search: '<i class="fas fa-search"></i>',
                    searchPlaceholder: 'Search companies...'
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\employee-management\resources\views/companies/index.blade.php ENDPATH**/ ?>