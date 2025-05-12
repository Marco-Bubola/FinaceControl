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
            const row = document.createElement("div");
            row.className = "row";

            // Itera sobre todas as transações do mês
            Object.values(data.groupedInvoices).forEach((invoices) => {
              invoices.forEach((invoice) => {
                const card = renderTransactionCard(invoice);
                row.innerHTML += card;
              });
            });

            container.appendChild(row);
          }

          // Atualiza o valor total do mês
          totalMonthElement.textContent = `$ ${data.totalMonth.toFixed(2)}`;

          // Atualizar o título do mês
          updateMonthTitle(month, year);

          // Atualizar os gráficos
          updateChart(data.totals, data.totalMonth);
          // Atualizar o gráfico de linha com os dados diários
          addLineChart(data.dailyData);
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

    function renderTransactionCard(transaction) {
      const borderColor = transaction.type_id == 2 ? "danger" : "success";
      const categoryColor = transaction.category_hexcolor_category || "#cccccc";
      const categoryIcon = transaction.category_icone || "fas fa-question";
      const transactionDate = transaction.invoice_date
          ? new Date(transaction.invoice_date).toLocaleDateString("pt-BR", {
                day: "2-digit",
                month: "2-digit",
            })
          : "Sem data";
      const transactionValue = `${
          transaction.type_id == 2 ? "-" : "+"
      } R$ ${Math.abs(transaction.value).toFixed(2)}`;

      return `
          <div class="col-md-4">
              <div class="card border" style="border: 3px solid ${categoryColor};">
                  <div class="card-body">
                      <div class="d-flex align-items-center">
                          <button class="btn btn-icon-only btn-rounded btn-outline-${borderColor} mb-0 me-3 d-flex align-items-center justify-content-center"
                              style="border: 3px solid ${categoryColor}; width: 50px; height: 50px;">
                              <i class="${categoryIcon}" style="font-size: 1.7rem;"></i>
                          </button>
                          <div class="d-flex flex-column">
                              <h6 class="mb-1 text-dark text-sm">${transaction.description}</h6>
                              <span class="text-xs">${transactionDate}</span>
                          </div>
                      </div>
                      <div class="d-flex justify-content-center align-items-center mt-3">
                          <span class="text-sm font-weight-bold text-${borderColor}">
                              ${transactionValue}
                          </span>
                      </div>
                  </div>
              </div>
          </div>
      `;
    }

   // Função para atualizar o gráfico de categorias
   function updateCategoryChart(categories, totalInvoices) {

      // Converte o objeto de categorias em um array, se necessário
      if (categories && typeof categories === 'object' && !Array.isArray(categories)) {
          categories = Object.values(categories);
      }

      const ctx = document.getElementById('updateCategoryChart');
      if (!ctx) {
          console.error('Elemento canvas para o gráfico de categorias não encontrado.');
          return;
      }

      // Se não houver dados, mostra a mensagem "Sem dados" e cria o gráfico com valor zero
      if (!categories || categories.length === 0) {
          console.warn('Nenhum dado encontrado para o gráfico de categorias.');
          document.getElementById('no-data-message').style.display = 'block'; // Mostra a mensagem "Sem dados"
          categories = [{ label: 'Nenhuma Categoria', value: 0 }];
      } else {
          document.getElementById('no-data-message').style.display = 'none'; // Esconde a mensagem "Sem dados"
      }

      // Se já existir um gráfico, destrua-o antes de criar um novo
      if (window.categoryChart) {
          window.categoryChart.destroy();
      }

      // Processa os dados para o gráfico
      const categoryData = categories.map(category => category.value);
      const labels = categories.map(category => category.label);

      // Ajusta dinamicamente o tamanho do gráfico com base no número de categorias
      const chartHeight = Math.max(300, categories.length * 30);
      ctx.height = chartHeight;

      // Cria o gráfico
      try {
          window.categoryChart = new Chart(ctx.getContext('2d'), {
              type: 'doughnut',
              data: {
                  labels: labels,
                  datasets: [{
                      data: categoryData,
                      backgroundColor: [
                        categoryData[0] === 0 ? '#FF6384' : '#36A2EB', '#FF6384',
                        '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF5733',
                        '#C70039', '#900C3F', '#DAF7A6', '#FFC300',
                        '#581845', '#FF6F61', '#1E90FF', '#00FA9A',
                        '#FF1493'
                    ],
                    hoverBackgroundColor: [
                        categoryData[0] === 0 ? '#FF6384' : '#36A2EB', '#FF6384',
                        '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF5733',
                        '#C70039', '#900C3F', '#DAF7A6', '#FFC300',
                        '#581845', '#FF6F61', '#1E90FF', '#00FA9A',
                        '#FF1493'
                    ]
                  }]
              },
              options: {
                  responsive: true,
                  plugins: {
                      legend: {
                          position: false,
                      },
                      tooltip: {
                          enabled: true,
                      },
                  },
                  cutout: '70%', // Cria espaço no centro do gráfico
                  layout: {
                      padding: 40
                  }
              },
              plugins: [{
                  id: 'centerText',
                  beforeDraw: (chart) => {
                      const { width } = chart;
                      const ctx = chart.ctx;
                      ctx.save();
                      ctx.font = 'bold 20px Arial';
                      ctx.textAlign = 'center';
                      ctx.textBaseline = 'middle';

                      // Exibe o texto "Total de Faturas"
                      ctx.fillStyle = '#333';
                      ctx.fillText('Total de Faturas', width / 2, chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2 - 10);

                      // Exibe o valor total no centro
                      ctx.fillStyle = '#4caf50';
                      ctx.fillText(`R$ ${totalInvoices.toFixed(2)}`, width / 2, chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2 + 15);
                      ctx.restore();
                  }
              }]
          });
      } catch (error) {
          console.error('Erro ao criar o gráfico de categorias:', error);
      }
  }

  // Atualiza o gráfico e os dados do mês
  $('.btn-change-month').on('click', function(e) {
      e.preventDefault();
      const month = $(this).data('month');
      updateMonthData(month);
  });

  // Função para adicionar o gráfico de linha com dados reais
  function addLineChart(dailyData) {

      const ctx = document.getElementById('lineChart');
      if (!ctx) {
          console.error('Elemento canvas para o gráfico de linha não encontrado.');
          return;
      }

      const labels = dailyData.labels || []; // Dias do mês
      const dataset = dailyData.values || []; // Valores reais das faturas por dia

      if (labels.length === 0 || dataset.length === 0) {
          console.warn('Nenhum dado encontrado para o gráfico de linha.');
      }

      // Gera cores diferentes para cada dia
      const colors = labels.map((_, index) => `hsl(${(index * 360) / labels.length}, 70%, 50%)`);

      const chartData = {
          labels: labels,
          datasets: [{
              label: 'Faturas por Dia',
              data: dataset,
              borderColor: colors,
              backgroundColor: colors.map(color => color.replace('70%', '90%')),
              borderWidth: 2,
              pointRadius: 5,
              pointBackgroundColor: colors,
              fill: true,
              tension: 0.4
          }]
      };

      const config = {
          type: 'line',
          data: chartData,
          options: {
              plugins: {
                  title: {
                      display: true,
                      text: 'Faturas por Dia no Mês'
                  },
                  tooltip: {
                      callbacks: {
                          label: function(context) {
                              return `R$ ${context.raw.toFixed(2)}`;
                          }
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      };

      // Verifica se o gráfico já existe antes de destruí-lo
      if (window.lineChart && typeof window.lineChart.destroy === 'function') {
          window.lineChart.destroy();
      }

      // Cria o novo gráfico
      try {
          window.lineChart = new Chart(ctx.getContext('2d'), config);
      } catch (error) {
          console.error('Erro ao criar o gráfico de linha:', error);
      }
  }

  // Função para atualizar os gráficos
  function updateChart(categoryData, totalInvoices) {
      updateCategoryChart(categoryData, totalInvoices); // Atualiza o gráfico de categorias
      addLineChart(categoryData); // Atualiza o gráfico de linha
  }

  });
