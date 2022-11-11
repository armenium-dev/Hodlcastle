const Statistic = {
    init: function () {
        this.initReportingRateChart();
        this.initAttachmentChart();
        // this.initNoResponseChart();
        this.initSmishRateChart();
        this.renderPieChart();
        this.renderPieChartSmish();
    },
    initReportingRateChart: function () {
        const ctx1 = $('#chart1');
        let dataClicks = ctx1.data('data-clicks');
        let dataReports = ctx1.data('data-reports');
        let labels = ctx1.data('short-labels');
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "clicked",
                        backgroundColor: "#DD4B39",
                        data: dataClicks
                    },
                    {
                        label: "reported",
                        backgroundColor: "#56a501",
                        data: dataReports
                    }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Click rate versus reporting rate',
                        font: {
                            size: 18,
                            family: "Helvetica",
                        },
                        padding: 15
                    },
                    legend: {
                        display: true,
                        position: 'right', // place legend on the right side of chart
                        labels: {
                            strokeStyle: '#ffffff',
                            lineCap: 0,
                            lineWidth: 0,
                            boxWidth: 14,
                        }
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Campaign'
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Rate as a percentage of unique recipients'
                        },
                    }
                },
            }
        });
    },
    initAttachmentChart: function () {
        const ctx = $('#chart2');
        let dataAttachments = ctx.data('data-attachments');
        let dataFakeAuth = ctx.data('data-fake-auth');
        let labels = ctx.data('short-labels');

        const chart1 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "attachments",
                        backgroundColor: "#0800dd",
                        data: dataAttachments
                    },
                    {
                        label: "data entry",
                        backgroundColor: "#FFC107",
                        data: dataFakeAuth
                    }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Compromised users: data entry versus malicious attachment',
                        font: {
                            size: 18,
                            family: "Helvetica",
                        },
                        padding: 15
                    },
                    legend: {
                        display: true,
                        position: 'right', // place legend on the right side of chart
                        labels: {
                            strokeStyle: '#ffffff',
                            lineCap: 0,
                            lineWidth: 0,
                            boxWidth: 14,
                        }
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Campaign'
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Percentage of unique recipients'
                        },
                    }
                },
            }
        });
    },
    initNoResponseChart: function () {
        const ctx = $('#chart3');
        let dataNoResponse = ctx.data('data-no-response');
        let labels = ctx.data('short-labels');

        const chart1 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "no response",
                        backgroundColor: "#cbcedd",
                        data: dataNoResponse
                    },
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'No Response',
                        font: {
                            size: 18,
                            family: "Helvetica",
                        },
                        padding: 15
                    },
                    legend: {
                        display: true,
                        position: 'right', // place legend on the right side of chart
                        labels: {
                            strokeStyle: '#ffffff',
                            lineCap: 0,
                            lineWidth: 0,
                            boxWidth: 14,
                        }
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Campaign'
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Percent'
                        },
                    }
                },
            }
        });
    },
    initSmishRateChart: function () {
        const ctx = $('#chart4');
        let dataSmishs = ctx.data('data-smishs');
        let labels = ctx.data('short-labels');

        const chart1 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Smish rate",
                        backgroundColor: "#DD4B39",
                        data: dataSmishs
                    },
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Smish rate',
                        font: {
                            size: 18,
                            family: "Helvetica",
                        },
                        padding: 15
                    },
                    legend: {
                        display: true,
                        position: 'right', // place legend on the right side of chart
                        labels: {
                            strokeStyle: '#ffffff',
                            lineCap: 0,
                            lineWidth: 0,
                            boxWidth: 14,
                        }
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Campaign'
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Percent'
                        },
                    }
                },
            }
        });
    },
    renderPieChart: function () {
        const elems = $('.chart-campaign');
        let labels = ['CLick Link', 'Opened email, only', 'Unread/No Response', 'Reported Only'];

        for (let i = 0; i < elems.length; i++) {
            let ctx = $(elems[i]);
            let sentsOnlyCount = ctx.data('sents-only-count');
            let clicksOnlyCount = ctx.data('clicks-only-count');
            let opensOnlyCount = ctx.data('opens-only-count');
            let reportsOnlyCount = ctx.data('reports-only-count');

            let clickCount = ctx.data('clicks-count');
            let recipientsCount = ctx.data('recipients-count');

            let clickOnlyCountPercent = (clickCount * 100 / recipientsCount).toFixed(1);
            let sentsOnlyPercent = (sentsOnlyCount * 100 / recipientsCount).toFixed(1);
            let opensOnlyPercent = (opensOnlyCount * 100 / recipientsCount).toFixed(1);
            let reportsOnlyPercent = (reportsOnlyCount * 100 / recipientsCount).toFixed(1);



            const chart1 = new Chart(ctx, {
                type: 'pie',
                cutout: 100,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'My First Dataset',
                        data: [clickOnlyCountPercent, opensOnlyPercent, sentsOnlyPercent, reportsOnlyPercent],
                        backgroundColor: [
                            '#DD4B39',
                            '#292ca5',
                            '#FFC107',
                            '#56a501'
                        ]
                    }],
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                strokeStyle: '#ffffff',
                                lineCap: 0,
                                lineWidth: 0,
                                boxWidth: 5,
                            }
                        },
                    }
                },
            });
        }
    },
    renderPieChartSmish: function () {
        const elems = $('.chart-campaign-smish');
        let labels = ['Unread/No response', 'Smished'];

        for (let i = 0; i < elems.length; i++) {
            let ctx = $(elems[i]);
            let sentsCount = ctx.data('data-sents');
            let smishCount = ctx.data('data-smishs');

            const chart1 = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'My First Dataset',
                        data: [sentsCount, smishCount],
                        backgroundColor: [
                            '#cbcedd',
                            '#DD4B39'
                        ]
                    }],
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                strokeStyle: '#ffffff',
                                lineCap: 0,
                                lineWidth: 0,
                                boxWidth: 5,
                            }
                        },
                    }
                },
            });
        }
    }
};
