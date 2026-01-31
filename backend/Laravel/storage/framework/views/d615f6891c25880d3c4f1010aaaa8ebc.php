<div class="btn-group btn-group-sm">
    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-danger delete-btn" data-url="<?php echo e(route('employees.destroy', $employee)); ?>"
        title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div><?php /**PATH C:\xampp\htdocs\employee-management\resources\views/employees/partials/actions.blade.php ENDPATH**/ ?>