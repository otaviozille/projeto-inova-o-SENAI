document.addEventListener("DOMContentLoaded", function () {
    fetch('obter_dados.php')
        .then(response => response.json())
        .then(data => {
            // Função para criar gráficos
            const createChart = (id, type, labels, dataset) => {
                const ctx = document.getElementById(id).getContext('2d');
                new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [dataset]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: dataset.label
                            }
                        }
                    }
                });
            };

            // Gráfico 1: Educação
            createChart("chart1", "bar", ["Bom", "Moderado", "Ruim"], {
                label: "Acesso à Educação",
                data: [data.educacao.bom, data.educacao.moderado, data.educacao.ruim],
                backgroundColor: ["#4CAF50", "#FFEB3B", "#F44336"]
            });

            // Gráfico 2: Água
            createChart("chart2", "bar", ["Bom", "Moderado", "Ruim"], {
                label: "Acesso à Água",
                data: [data.agua.bom, data.agua.moderado, data.agua.ruim],
                backgroundColor: ["#4CAF50", "#FFEB3B", "#F44336"]
            });

            // Gráfico 3: Saúde
            createChart("chart3", "pie", ["Bom", "Moderado", "Ruim"], {
                label: "Condições de Saúde",
                data: [data.saude.bom, data.saude.moderado, data.saude.ruim],
                backgroundColor: ["#4CAF50", "#FFEB3B", "#F44336"]
            });

            // Gráfico 4: Moradia
            createChart("chart4", "doughnut", ["Bom", "Moderado", "Ruim"], {
                label: "Condições de Moradia",
                data: [data.moradia.bom, data.moradia.moderado, data.moradia.ruim],
                backgroundColor: ["#4CAF50", "#FFEB3B", "#F44336"]
            });

            // Gráfico 5: Renda Familiar Média
            createChart("chart5", "line", data.comunidades, {
                label: "Renda Familiar Média (R$)",
                data: data.renda,
                borderColor: "#4CAF50",
                fill: false,
                tension: 0.1
            });

            // Gráfico 6: Taxa de Emprego
            createChart("chart6", "polarArea", ["Alto", "Moderado", "Baixo"], {
                label: "Taxa de Emprego",
                data: [data.emprego.alto, data.emprego.moderado, data.emprego.baixo],
                backgroundColor: ["#4CAF50", "#FFEB3B", "#F44336"]
            });
        })
        .catch(error => {
            console.error("Erro ao carregar dados:", error);
        });
});
