
<!-- Toast de progresso de download PDF -->
<style>
#pdf-download-toast, #pdf-success-toast {
    position: fixed;
    top: 32px;
    right: 32px;
    z-index: 9999;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px #0002;
    padding: 20px 32px 20px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 320px;
    border: 1px solid #e0e7ef;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}
#pdf-download-toast.active, #pdf-success-toast.active {
    opacity: 1;
    pointer-events: auto;
}
#pdf-download-toast .progress-circle {
    width: 48px;
    height: 48px;
    position: relative;
}
#pdf-download-toast .progress-circle svg {
    transform: rotate(-90deg);
}
#pdf-download-toast .progress-circle .progress-value {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #2563eb;
    font-size: 1.1rem;
}
#pdf-download-toast .toast-text {
    flex: 1;
}
#pdf-success-toast {
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    color: #166534;
}
#pdf-success-toast .success-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #bbf7d0;
    border-radius: 50%;
}
#pdf-success-toast .success-icon svg {
    width: 28px;
    height: 28px;
    color: #22c55e;
}
#pdf-success-toast .toast-text {
    flex: 1;
}
</style>
<div id="pdf-download-toast">
    <div class="progress-circle">
        <svg width="48" height="48">
            <circle cx="24" cy="24" r="20" stroke="#e5e7eb" stroke-width="6" fill="none"/>
            <circle id="pdf-progress-bar" cx="24" cy="24" r="20" stroke="#2563eb" stroke-width="6" fill="none"
                stroke-linecap="round" stroke-dasharray="125.6" stroke-dashoffset="125.6"/>
        </svg>
        <span class="progress-value" id="pdf-progress-value">0%</span>
    </div>
    <div class="toast-text">
        <strong>Baixando PDF...</strong>
        <div style="font-size:0.97rem;color:#666;">Aguarde enquanto o arquivo é gerado</div>
    </div>
</div>
<div id="pdf-success-toast">
    <div class="success-icon">
        <svg viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="12" fill="#bbf7d0"/>
            <path d="M7 13l3 3 7-7" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="toast-text">
        <strong>Download com sucesso!</strong>
        <div style="font-size:0.97rem;color:#166534;">O PDF foi baixado corretamente.</div>
    </div>
</div>

@push('scripts')
<script>
function showPdfToast() {
    const toast = document.getElementById('pdf-download-toast');
    const progressBar = document.getElementById('pdf-progress-bar');
    const progressValue = document.getElementById('pdf-progress-value');
    toast.classList.add('active');
    progressBar.setAttribute('stroke-dashoffset', 125.6);
    progressValue.textContent = '0%';
}
function updatePdfToast(percent) {
    const progressBar = document.getElementById('pdf-progress-bar');
    const progressValue = document.getElementById('pdf-progress-value');
    let offset = 125.6 - (125.6 * percent / 100);
    progressBar.setAttribute('stroke-dashoffset', offset);
    progressValue.textContent = percent + '%';
}
function hidePdfToast() {
    const toast = document.getElementById('pdf-download-toast');
    toast.classList.remove('active');
}
function showSuccessToast() {
    const toast = document.getElementById('pdf-success-toast');
    toast.classList.add('active');
    setTimeout(() => {
        toast.classList.remove('active');
    }, 2500);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.export-pdf-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('data-export-url');
            showPdfToast();

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/pdf'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erro ao baixar PDF');
                const contentLength = response.headers.get('Content-Length');
                if (!response.body || !contentLength) {
                    // fallback: download sem progresso real, simula progresso animado
                    let fakePercent = 0;
                    updatePdfToast(1); // começa em 1%
                    return response.blob().then(blob => {
                        return new Promise(resolve => {
                            let interval = setInterval(() => {
                                fakePercent += 7 + Math.floor(Math.random()*7); // anima mais suave
                                if (fakePercent >= 100) {
                                    fakePercent = 100;
                                    updatePdfToast(fakePercent);
                                    clearInterval(interval);
                                    setTimeout(() => resolve({blob, percent: 100, response}), 200);
                                } else {
                                    updatePdfToast(fakePercent);
                                }
                            }, 60);
                        });
                    });
                }
                const total = parseInt(contentLength, 10);
                let loaded = 0;
                let chunks = [];
                const reader = response.body.getReader();

                // Progresso real
                function read() {
                    return reader.read().then(({done, value}) => {
                        if (done) return;
                        if (value) {
                            chunks.push(value);
                            loaded += value.byteLength;
                            let percent = Math.floor((loaded / total) * 100);
                            if (percent < 1) percent = 1; // nunca mostrar 0%
                            if (percent > 100) percent = 100;
                            updatePdfToast(percent);
                        }
                        return read();
                    });
                }
                return read().then(() => {
                    let blob = new Blob(chunks, {type: 'application/pdf'});
                    updatePdfToast(100);
                    return {blob, percent: 100, response};
                });
            })
            .then(({blob, response}) => {
                hidePdfToast();
                showSuccessToast();
                // Cria link temporário para download
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                let filename = 'relatorio-venda.pdf';
                if (response && response.headers) {
                    const disposition = response.headers.get('Content-Disposition');
                    if (disposition && disposition.indexOf('filename=') !== -1) {
                        let match = disposition.match(/filename="?([^"]+)"?/);
                        if (match && match[1]) filename = match[1];
                    }
                }
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                setTimeout(() => {
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(link.href);
                }, 100);
            })
            .catch(() => {
                hidePdfToast();
                alert('Erro ao baixar PDF');
            });
        });
    });
});
</script>
@endpush
