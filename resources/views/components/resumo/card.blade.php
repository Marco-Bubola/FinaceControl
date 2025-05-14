{{-- resources/views/components/resumo/card.blade.php --}}
@props(['label', 'value', 'color', 'icon'])

<div class="card bg-dark text-white shadow-sm mb-4 h-100">
    <div class="card-body d-flex align-items-center gap-3">
        <div class="bg-{{ $color }} rounded-circle d-flex justify-content-center align-items-center"
            style="width: 50px; height: 50px;">
            <i class="{{ $icon }} text-white"></i>
        </div>
        <div>
            <p class="mb-0 text-sm">{{ $label }}</p>
            <h6 class="text-{{ $color }} font-weight-bold">
                R$ {{ number_format($value, 2, ',', '.') }}
            </h6>
        </div>
    </div>
</div>