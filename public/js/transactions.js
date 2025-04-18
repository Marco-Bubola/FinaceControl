document.addEventListener("DOMContentLoaded", function () {
  let currentMonth = window.currentMonth;
  let currentYear = window.currentYear;

  // Função para formatar o título do mês e ano
  function updateMonthTitle(month, year) {
    const monthNames = [
      "Janeiro",
      "Fevereiro",
      "Março",
      "Abril",
      "Maio",
      "Junho",
      "Julho",
      "Agosto",
      "Setembro",
      "Outubro",
      "Novembro",
      "Dezembro",
    ];
    const monthTitle = document.getElementById("monthTitle");
    monthTitle.textContent = `${monthNames[month - 1]} de ${year}`;
  }

  // Função para carregar transações dinamicamente
  function loadTransactions(month, year) {
    fetch(`/banks?month=${month}&year=${year}`, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erro HTTP: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        const container = document.getElementById("transactionsContainer");
        const totalMonthElement = document.getElementById("totalMonth");
        container.innerHTML = ""; // Limpa o conteúdo atual

        if (Object.keys(data.groupedInvoices).length === 0) {
          container.innerHTML =
            "<p>Você ainda não tem transações para este mês.</p>";
        } else {
          for (const [date, invoices] of Object.entries(data.groupedInvoices)) {
            const dateHeader = document.createElement("h6");
            dateHeader.className =
              "text-uppercase text-body text-xs font-weight-bolder mb-3";
            dateHeader.textContent = new Date(date).toLocaleDateString(
              "pt-BR",
              {
                day: "2-digit",
                month: "long",
                year: "numeric",
              }
            );
            container.appendChild(dateHeader);

            const listGroup = document.createElement("ul");
            listGroup.className = "list-group";

            invoices.forEach((invoice) => {
              const listItem = document.createElement("li");
              listItem.className =
                "list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg";

              listItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            <button class="btn btn-icon-only btn-rounded ${
                              invoice.value < 0
                                ? "btn-outline-danger"
                                : "btn-outline-success"
                            } mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                <i class="${
                                  invoice.value < 0
                                    ? "fas fa-arrow-down"
                                    : "fas fa-arrow-up"
                                }"></i>
                            </button>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">${
                                  invoice.description
                                }</h6>
                                <span class="text-xs">${new Date(
                                  invoice.invoice_date
                                ).toLocaleTimeString("pt-BR", {
                                  hour: "2-digit",
                                  minute: "2-digit",
                                })}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ${
                          invoice.value < 0
                            ? "text-danger text-gradient"
                            : "text-success text-gradient"
                        } text-sm font-weight-bold">
                            ${invoice.value < 0 ? "-" : "+"} $ ${Math.abs(
                invoice.value
              ).toFixed(2)}
                        </div>
                    `;

              listGroup.appendChild(listItem);
            });

            container.appendChild(listGroup);
          }
        }

        // Atualiza o valor total do mês
        totalMonthElement.textContent = `$ ${data.totalMonth.toFixed(2)}`;

        // Atualizar o título do mês
        updateMonthTitle(month, year);
      })
      .catch((error) => console.error("Erro ao carregar transações:", error));
  }

  // Função para alterar o mês e o ano
  function changeMonth(direction) {
    if (direction === "prev") {
      currentMonth = currentMonth - 1 <= 0 ? 12 : currentMonth - 1;
      currentYear = currentMonth === 12 ? currentYear - 1 : currentYear;
    } else if (direction === "next") {
      currentMonth = currentMonth + 1 > 12 ? 1 : currentMonth + 1;
      currentYear = currentMonth === 1 ? currentYear + 1 : currentYear;
    }
    loadTransactions(currentMonth, currentYear);
  }

  // Inicializar o título com o mês e ano atuais
  updateMonthTitle(currentMonth, currentYear);

  // Carregar as transações iniciais
  loadTransactions(currentMonth, currentYear);

  // Eventos para os botões de navegação
  document.getElementById("prevMonth").addEventListener("click", function () {
    changeMonth("prev");
  });

  document.getElementById("nextMonth").addEventListener("click", function () {
    changeMonth("next");
  });
});

