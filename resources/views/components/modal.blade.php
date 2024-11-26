@props(['modalId', 'title', 'action'])
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">{{ $title ?? 'O\'chirish' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form id="deleteForm" action="{{ $action }}" method="POST" class="needs-validation was-validated" novalidate>
                    <div class="modal-body">
                        @csrf
                        @method('PUT') <!-- Bu update so'rovini qo'llab-quvvatlaydi -->
    
                        <div class="mb-3">
                            <label for="reason" class="form-label">O'chirish uchun izoh kiriting:</label>
                            <textarea class="form-control" name="reason" id="reason" rows="3" required></textarea>
                            <div class="invalid-feedback">
                                Izoh majburiy
                            </div>
                        </div>
    
                        <!-- Foydalanuvchi ID sini yuborish -->
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }">
    
                    </div>
                    <div class="modal-footer">
                        <!-- Bekor qilish tugmasi -->
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Bekor qilish</button>

                        <!-- O'chirish tugmasi -->
                        <button type="submit" class="btn btn-danger">O'chirish</button>
                    </div>
                </form>
        </div>
    </div>
</div>
