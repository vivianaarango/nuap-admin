window.onload = function() {
    axios.get('/api/reports/users/tickets')
        .then(function (response) {
            new Chart(document.getElementById("pie-chart"), {
                type: 'pie',
                data: {
                    labels: ["Cerrado", "Pendiente Administrador", "Pendiente Usuario"],
                    datasets: [{
                        label: "Population (millions)",
                        backgroundColor: ["#d42d3f", "#18a7ba","#e7da0e"],
                        data: [
                            response.data.data.closed,
                            response.data.data.admin_pending,
                            response.data.data.user_pending
                        ]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Información estado de tickets actuales'
                    }
                }
            });
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}