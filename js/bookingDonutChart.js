document.addEventListener("DOMContentLoaded", () => {
    // Retrieve the real data from the HTML element's data attributes
    const bookingDonutChartElement = document.querySelector("#bookingDonutChart");
    const totalBookings = parseInt(bookingDonutChartElement.getAttribute("data-total"));
    const confirmedBookings = parseInt(bookingDonutChartElement.getAttribute("data-confirmed"));
    const canceledBookings = parseInt(bookingDonutChartElement.getAttribute("data-canceled"));

    // Initialize the chart with the real data
    echarts.init(bookingDonutChartElement).setOption({
        tooltip: {
            trigger: 'item'
        },
        legend: {
            top: '5%',
            left: 'center'
        },
        color: ['#5c7bd9', '#9fe080', '#ff7b7b'],
        series: [{
            name: 'This Year',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            label: {
                show: false,
                position: 'center'
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: '18',
                    fontWeight: 'bold'
                }
            },
            labelLine: {
                show: false
            },
            data: [
                {
                    value: totalBookings,
                    name: 'Total Booking'
                },
                {
                    value: confirmedBookings,
                    name: 'Confirmed'
                },
                {
                    value: canceledBookings,
                    name: 'Canceled'
                }
            ]
        }]
    });
});
