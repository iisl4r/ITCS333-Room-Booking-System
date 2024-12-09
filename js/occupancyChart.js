document.addEventListener("DOMContentLoaded", () => {
    const totalRooms = parseInt(document.querySelector("#occupancyChart").getAttribute("data-total"));
    const occupiedRooms = parseInt(document.querySelector("#occupancyChart").getAttribute("data-occupied"));
    const availableRooms = parseInt(document.querySelector("#occupancyChart").getAttribute("data-available"));

    echarts.init(document.querySelector("#occupancyChart")).setOption({
        tooltip: {
            trigger: 'item'
        },
        legend: {
            top: '5%',
            left: 'center'
        },
        series: [{
            name: 'Rooms Occupancy',
            type: 'pie',
            radius: '50%',
            data: [
                {
                    value: occupiedRooms,
                    name: 'Occupied Rooms',
                    itemStyle: {
                        color: '#ff7070'
                    }
                },
                {
                    value: availableRooms,
                    name: 'Available Rooms',
                    itemStyle: {
                        color: '#9fe080'
                    }
                }
            ],
            label: {
                show: true,
                formatter: '{b}: {d}%'
            }
        }]
    });
});
