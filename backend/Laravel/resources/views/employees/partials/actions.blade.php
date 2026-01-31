<div class="btn-group btn-group-sm">
    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-danger delete-btn" data-url="{{ route('employees.destroy', $employee) }}"
        title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>