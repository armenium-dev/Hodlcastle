const Statistic = {
    init: function () {
        this.initChartHome();
        this.initReportingRateChart();
        this.initAttachmentChart();
        // this.initNoResponseChart();
        this.initSmishRateChart();
        this.renderPieChart();
        this.renderPieChartSmish();
        this.initChartSmishPerLocation();
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
                        backgroundColor: "rgba(221,75,57,1)",
                        data: dataClicks
                    },
                    {
                        label: "reported",
                        backgroundColor: "rgba(1,165,89,1)",
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
                        backgroundColor: "rgba(97,8,130,1)",
                        borderColor: "rgba(97,8,130,0.2)",
                        data: dataAttachments
                    },
                    {
                        label: "data entry",
                        backgroundColor: "rgba(255,193,7,1)",
                        borderColor: "rgba(255,193,7,0.2)",
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
    initChartSmishPerLocation() {
        const ctx = $('#chartSmishingPerLocation');
        let recipients = ctx.data('recipients');
        let labels = [];
        let dataSet = [];

        recipients.map((recipient) => {
            labels.push(recipient.location);
            dataSet.push(recipient.results_count)
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Rate",
                        backgroundColor: "#DD4B39",
                        data: dataSet
                    }
                ],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Smishing rate per location',
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
                            text: 'Locations'
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Rate'
                        },
                    }
                },
            }
        });
    },
    renderPieChart: function () {
        const elems = $('.chart-campaign');
        let labels = ['Clicks', 'Opened email, only', 'Unread/No Response', 'Reported Only'];

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
                            'rgba(221,75,57,1)',
                            'rgba(243,156,18,1)',
                            '#cbcedd',
                            'rgba(1,165,89,1)'
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
    },
    initChartHome() {
        if ($('#chartHome').length == 0)
            return;
        var labels_str = $('#chartHome').data('labels');
        var labels = labels_str.split(',');
        var short_labels_str = $('#chartHome').data('short-labels');
        var short_labels = short_labels_str.split(',');

        var data_sents = $('#chartHome').data('data-sents');
        var data_opens = $('#chartHome').data('data-opens');
        var data_clicks = $('#chartHome').data('data-clicks');
        var data_fake_auth = $('#chartHome').data('data-fakeauth');
        var data_attachments = $('#chartHome').data('data-attachments');
        var data_smishs = $('#chartHome').data('data-smishs');
        var data_reports = $('#chartHome').data('data-reports');

        var lineChart       = new Chart( $('#chartHome'), {
            type: 'line',
            data: {
                tooltip_labels: labels,
                labels :    short_labels,
                datasets :  [
                    {
                        label : "Sent",
                        borderColor: "rgba(1,191,238,1)",
                        backgroundColor: "rgba(1,191,238,0.2)",
                        fill : true,
                        tension: 0.3,
                        data : data_sents
                    },
                    {
                        label : "Opened",
                        borderColor: "rgba(243,156,18,1)",
                        backgroundColor: "rgba(243,156,18,0.2)",
                        fill : true,
                        tension: 0.3,
                        data : data_opens
                    },
                    {
                        label : "Click(s)",
                        backgroundColor : "rgba(221,75,57,0.2)",
                        borderColor : "rgba(221,75,57,1)",
                        fill : true,
                        tension: 0.3,
                        data : data_clicks
                    },
                    {
                        label : "Attachments",
                        backgroundColor : "rgba(97,8,130,0.2)",
                        borderColor : "rgba(97,8,130,1)",
                        fill : true,
                        tension: 0.3,
                        data : data_attachments
                    },
                    {
                        label : "Data entry",
                        backgroundColor : "rgba(255,193,7,0.2)",
                        borderColor : "rgba(255,193,7,1)",
                        fill : true,
                        tension: 0.3,
                        data : data_fake_auth
                    },
                    {
                        label : "Smishing",
                        backgroundColor : "rgba(136,29,2,0.2)",
                        borderColor : "rgba(136,29,2,1)",
                        fill : true,
                        tension: 0.3,
                        data : data_smishs
                    },
                    {
                        label : "Reported",
                        backgroundColor : "rgba(1,165,89,0.2)",
                        borderColor : "rgba(1,165,89,1)",
                        fill : true,
                        tension: 0.3,
                        data : data_reports
                    }
                ],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Campaigns',
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
                showTooltips: true,
                multiTooltipTemplate: "<%= value %>% - <%= datasetLabel %>",
                interaction: {
                    intersect: false,
                },
            }
        });
    }
};

