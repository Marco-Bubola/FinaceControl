@if ($errors->any())
    <div id="error-modal" class="custom-modal message-modal error-modal modal fade" data-bs-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center">
                    <div id="error-icon" class="modal-icon error-icon">✖</div>
                    <div id="error-timer" class="progress-circle d-none">
                        <svg>
                            <circle cx="60" cy="60" r="54"></circle>
                            <circle cx="60" cy="60" r="54" id="error-progress"></circle>
                        </svg>
                        <div class="timer-text">3</div>
                    </div>
                    <h4 class="modal-title text-danger mt-4" id="errorModalLabel">Erro!</h4>
                    <ul class="mt-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn btn-danger mt-4" onclick="closeModal('error-modal')">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('success'))
    <div id="success-modal" class="custom-modal message-modal success-modal modal fade" data-bs-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 ">
                <div class="modal-body text-center">
                    <div id="success-icon" class="modal-icon success-icon">✔</div>
                    <div id="success-timer" class="progress-circle d-none">
                        <svg>
                            <circle cx="60" cy="60" r="54"></circle>
                            <circle cx="60" cy="60" r="54" id="success-progress"></circle>
                        </svg>
                        <div class="timer-text">3</div>
                    </div>
                    <h4 class="modal-title text-success mt-4" id="successModalLabel">Sucesso!</h4>
                    <p class="mt-3">{{ session('success') }}</p>
                    <button type="button" class="btn btn-success mt-4" onclick="closeModal('success-modal')">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Função para exibir o modal e iniciar o fluxo de exibição
        function showModal(modalId, iconId, timerId, progressId) {
            const modalElement = document.getElementById(modalId);
            const iconElement = document.getElementById(iconId);
            const timerElement = document.getElementById(timerId);
            const progressElement = document.getElementById(progressId);

            if (modalElement && iconElement && timerElement && progressElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show(); // Exibe o modal

                // Exibe o ícone por 2 segundos antes de mostrar o timer
                setTimeout(() => {
                    iconElement.classList.add("fade-out"); // Animação para ocultar o ícone
                    iconElement.classList.add("d-none"); // Oculta o ícone
                    timerElement.classList.remove("d-none"); // Mostra o timer
                    timerElement.classList.add("fade-in"); // Animação para exibir o timer
                    startModalTimer(modalId, timerId, progressElement); // Inicia o timer de 3 segundos
                }, 2000); // 2 segundos
            }
        }

        // Função para iniciar o timer e ocultar o modal após 3 segundos
        function startModalTimer(modalId, timerId, progressElement) {
            let timeLeft = 3; // Tempo em segundos
            const timerText = document.querySelector(`#${timerId} .timer-text`);
            const progressCircle = progressElement;
            const circumference = 2 * Math.PI * 54; // Circunferência do círculo

            progressCircle.style.strokeDasharray = `${circumference}`;
            progressCircle.style.strokeDashoffset = `${circumference}`;

            const interval = setInterval(() => {
                if (timeLeft > 0) {
                    timerText.textContent = timeLeft--; // Atualiza o texto do timer
                    const offset = (circumference * timeLeft) / 3;
                    progressCircle.style.strokeDashoffset = offset; // Atualiza o progresso
                } else {
                    clearInterval(interval);
                    closeModal(modalId); // Fecha o modal após 3 segundos
                }
            }, 1000); // Atualiza a cada segundo
        }

        // Exibir o modal de erro (se existir)
        if (document.getElementById("error-modal")) {
            showModal("error-modal", "error-icon", "error-timer", "error-progress");
        }

        // Exibir o modal de sucesso (se existir)
        if (document.getElementById("success-modal")) {
            showModal("success-modal", "success-icon", "success-timer", "success-progress");
        }
    });

    // Função para fechar o modal
    function closeModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide(); // Utiliza o método do Bootstrap para esconder o modal
            }
        }
    }
</script>

<style>
    .custom-modal.message-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1050;
        animation: fadeIn 0.5s ease-in-out;
        background: transparent;
    }

    .custom-modal.message-modal .modal-content {
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        padding: 20px;
        background: #fff;
    }

    .custom-modal.message-modal .modal-body {
        font-size: 16px;
        color: #555;
    }

    .custom-modal.message-modal .modal-icon {
        font-size: 100px;
        margin-bottom: 20px;
        animation: scaleIn 0.5s ease-in-out;
    }

    .custom-modal.message-modal.success-modal .modal-icon {
        color: #4caf50;
    }

    .custom-modal.message-modal.error-modal .modal-icon {
        color: #f44336;
    }

    .custom-modal.message-modal .progress-circle {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }

    .custom-modal.message-modal .progress-circle svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .custom-modal.message-modal .progress-circle circle {
        fill: none;
        stroke-width: 6;
    }

    .custom-modal.message-modal .progress-circle circle:first-child {
        stroke: #e6e6e6;
    }

    .custom-modal.message-modal .progress-circle circle:last-child {
        stroke: #6c757d;
        stroke-dasharray: 0;
        stroke-dashoffset: 0;
        transition: stroke-dashoffset 1s linear;
    }

    .custom-modal.message-modal.success-modal .progress-circle circle:last-child {
        stroke: #4caf50;
    }

    .custom-modal.message-modal.error-modal .progress-circle circle:last-child {
        stroke: #f44336;
    }

    .custom-modal.message-modal .timer-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 36px;
        font-weight: bold;
        color: #6c757d;
    }

    .custom-modal.message-modal.success-modal .timer-text {
        color: #4caf50;
    }

    .custom-modal.message-modal.error-modal .timer-text {
        color: #f44336;
    }

    .custom-modal.message-modal .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .custom-modal.message-modal .fade-out {
        animation: fadeOut 0.5s ease-in-out;
    }

    .custom-modal.message-modal .d-none {
        display: none !important;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
