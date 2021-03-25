window.onload = function() {
    axios.get('/api/reports/users/role')
        .then(function (response) {
            let densityCanvas = document.getElementById("densityChart");

            Chart.defaults.global.defaultFontFamily = "-apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"";
            Chart.defaults.global.defaultFontSize = 16;

            let result = response.data.data;
            for (i = 0; i < result.length; i++) {
                if (result[i].role === 'Administrador') {
                    var admin = {
                        label: 'Administrador',
                        data: [
                            result[i].january,
                            result[i].february,
                            result[i].march,
                            result[i].march,
                            result[i].may,
                            result[i].june,
                            result[i].july,
                            result[i].august,
                            result[i].september,
                            result[i].october,
                            result[i].november,
                            result[i].december,
                        ],
                        backgroundColor: '#b8233c',
                        borderWidth: 0,
                        yAxisID: "y-axis-admin"
                    };
                }
                if (result[i].role === 'Distribuidor') {
                    var distributors = {
                        label: 'Distribuidores',
                        data: [
                            result[i].january,
                            result[i].february,
                            result[i].march,
                            result[i].march,
                            result[i].may,
                            result[i].june,
                            result[i].july,
                            result[i].august,
                            result[i].september,
                            result[i].october,
                            result[i].november,
                            result[i].december,
                        ],
                        backgroundColor: '#1882d0',
                        borderWidth: 0,
                        yAxisID: "y-axis-distributors"
                    };
                }
                if (result[i].role === 'Comercio') {
                    var commerces = {
                        label: 'Comercios',
                        data: [
                            result[i].january,
                            result[i].february,
                            result[i].march,
                            result[i].march,
                            result[i].may,
                            result[i].june,
                            result[i].july,
                            result[i].august,
                            result[i].september,
                            result[i].october,
                            result[i].november,
                            result[i].december,
                        ],
                        backgroundColor: '#39be28',
                        borderWidth: 0,
                        yAxisID: "y-axis-commerces"
                    };
                }
                if (result[i].role === 'Usuario') {
                    var clients = {
                        label: 'Clientes',
                        data: [
                            result[i].january,
                            result[i].february,
                            result[i].march,
                            result[i].march,
                            result[i].may,
                            result[i].june,
                            result[i].july,
                            result[i].august,
                            result[i].september,
                            result[i].october,
                            result[i].november,
                            result[i].december,
                        ],
                        backgroundColor: '#eec50b',
                        borderWidth: 0,
                        yAxisID: "y-axis-clients"
                    };
                } else {
                    var clients = {
                        label: 'Usuarios',
                        data: [
                            result[i].january,
                            result[i].february,
                            result[i].march,
                            result[i].march,
                            result[i].may,
                            result[i].june,
                            result[i].july,
                            result[i].august,
                            result[i].september,
                            result[i].october,
                            result[i].november,
                            result[i].december,
                        ],
                        backgroundColor: '#eec50b',
                        borderWidth: 0,
                        yAxisID: "y-axis-clients"
                    };
                }
            }

            var months = {
                labels: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    'Diciembre'
                ],
                datasets: [distributors, commerces, clients, admin]
            };

            var chartOptions = {
                animation: {
                    animateScale: true
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            precision: 0
                        }
                    }],
                    yAxes: [{
                        id: "y-axis-distributors"
                    }, {
                        id: "y-axis-commerces"
                    }, {
                        id: "y-axis-clients"
                    }, {
                        id: "y-axis-admin"
                    }]
                }
            };

            var barChart = new Chart(densityCanvas, {
                type: 'bar',
                data: months,
                options: chartOptions
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