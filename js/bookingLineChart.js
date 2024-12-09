document.addEventListener("DOMContentLoaded", () => {
    function generateDecemberDates() {
        const dates = [];
        for (let day = 1; day <= 31; day++) {
            const date = new Date(2024, 11, day); // 11 represents December (0 = January, 11 = December)
            dates.push(date.toISOString().split('T')[0] + 'T00:00:00.000Z');
        }
        return dates;
    }

    // Fake data for testing
    const fakeData = Array.from({
        length: 31
    }, (_, i) => {
        return {
            totalRooms: Math.floor(Math.random() * 15) + 5,
            availableRooms: Math.floor(Math.random() * 10) + 1,
            occupiedRooms: Math.floor(Math.random() * 10) + 1,
        };
    });

    // Filter Data
    const filterData = {
        today: {
            series: [{
                    name: 'Total Bookings',
                    data: [5, 8]
                },
                {
                    name: 'Confirmed Bookings',
                    data: [3, 4]
                },
                {
                    name: 'Canceled Bookings',
                    data: [2, 4]
                }
            ],
            categories: [
                "2024-12-06T08:00:00.000Z",
                "2024-12-06T21:00:00.000Z"
            ]
        },
        month: {
            series: [{
                    name: 'Total Rooms',
                    data: fakeData.map(item => item.totalRooms)
                },
                {
                    name: 'Available Rooms',
                    data: fakeData.map(item => item.availableRooms)
                },
                {
                    name: 'Occupied Rooms',
                    data: fakeData.map(item => item.occupiedRooms)
                }
            ],
            categories: generateDecemberDates()
        },
        year: {
            series: [{
                    name: 'Total Rooms',
                    data: [120, 100, 110, 130, 125, 140, 135, 125, 145, 150, 160, 170]
                },
                {
                    name: 'Available Rooms',
                    data: [40, 30, 50, 60, 55, 70, 65, 60, 75, 80, 85, 90]
                },
                {
                    name: 'Occupied Rooms',
                    data: [60, 70, 80, 90, 85, 90, 95, 100, 105, 110, 115, 120]
                }
            ],
            categories: [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ]
        }
    };

    const chart = new ApexCharts(document.querySelector("#bookingLineChart"), {
        series: filterData.today.series,
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        markers: {
            size: 4
        },
        colors: ['#5c7bd9', '#9fe080', '#fe7979'],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'datetime',
            categories: filterData.today.categories
        },
        tooltip: {
            x: {
                format: 'dd/MM/yyyy HH:mm'
            }
        }
    });

    chart.render();

    document.querySelector("#filter-today").addEventListener("click", (e) => {
        e.preventDefault();
        updateChart("Today", filterData.today);
    });

    document.querySelector("#filter-month").addEventListener("click", (e) => {
        e.preventDefault();
        updateChart("This Month", filterData.month);
    });

    document.querySelector("#filter-year").addEventListener("click", (e) => {
        e.preventDefault();
        updateChart("This Year", filterData.year);
    });

    // Update Chart Function
    function updateChart(filterTitle, data) {
        document.querySelector("#filter-title").innerText = `| ${filterTitle}`;

        if (filterTitle === "This Year") {
            chart.updateOptions({
                xaxis: {
                    type: 'category',
                    categories: data.categories
                }
            });
        } else {
            chart.updateOptions({
                xaxis: {
                    type: 'datetime',
                    categories: data.categories
                }
            });
        }

        chart.updateSeries(data.series);
    }
});
