// Index.php Dashboard JS

$(document).ready(function() {
    // Fetch total sales
    $.ajax({
        url: 'actions/total_sales.php',
        method: 'GET',
        success: function(response) {
            const data = JSON.parse(response);
            $('#totalSalesCount').text(data.total_sales);
        },
        error: function(error) {
            console.log('Error fetching total sales:', error);
        }
    });

    // Fetch total returns
    $.ajax({
        url: 'actions/get_total_returns.php',
        method: 'GET',
        success: function(response) {
            const data = JSON.parse(response);
            $('#totalReturnsCount').text(data.total_sales);
        },
        error: function(error) {
            console.log('Error fetching total returns:', error);
        }
    });


    // Fetch inventory count
    $.ajax({
        url: 'actions/get_inventory_count.php',
        method: 'GET',
        success: function(response) {
            const data = JSON.parse(response);
            $('#inventoryCount').text(data.inventory_count);
        },
        error: function(error) {
            console.log('Error fetching inventory count:', error);
        }
    });

    // Fetch sales count
    $.ajax({
        url: 'actions/get_sales_count.php', 
        method: 'GET',
        success: function(response) {
            console.log('Response:', response); 
            try {
                const data = JSON.parse(response);
                $('#salesCount').text(data.sales_count);
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        },
        error: function(error) {
            console.log('Error fetching sales count:', error);
        }
    });

    
    // Fetch unique customers count
    $.ajax({
        url: 'actions/get_unique_customers_count.php',
        method: 'GET',
        success: function(response) {
            const data = JSON.parse(response);
            $('#customersCount').text(data.unique_customers_count);
        },
        error: function(error) {
            console.log('Error fetching unique customers count:', error);
        }
    });
});






// Charts 


//Fetch and Display Top Products By Sales Count
$(document).ready(function() {
    $.ajax({
        url: 'actions/get_top_products.php', 
        method: 'GET',
        success: function(response) {
            const topProductsList = $('.top-product');
            topProductsList.empty(); 

            response.forEach(product => {
                const productCard = `
                    <li class="col-lg-3">
                        <div class="card card-block card-stretch card-height mb-0">
                            <div class="card-body">
                                <div class="rounded">
                                    <img src="assets/images/stock/${product.image}" class="style-img img-fluid m-auto p-2" alt="${product.product_name}">
                                </div>
                                <div class="style-text text-left mt-3">
                                    <h6 class="mb-1">${product.product_name}</h6>
                                    <p class="mb-0">${product.total_sales} Items</p>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
                topProductsList.append(productCard);
            });
        },
        error: function(error) {
            console.log('Error fetching top products:', error);
        }
    });
});



$(document).ready(function() {
    $.ajax({
        url: 'actions/get_top_returned_products.php',
        method: 'GET',
        success: function(response) {
            const topReturnedProducts = response;
            const topReturnedProductsList = $('#top-returned-products-list');

            topReturnedProductsList.empty(); // Clear any existing content

            topReturnedProducts.forEach(product => {
                const productItem = `
                    <li class="col-lg-4">
                        <div class="card card-block card-stretch card-height mb-0">
                            <div class="card-body">
                                <div class=" rounded">
                                    <img src="assets/images/stock/${product.image}" class="style-img img-fluid m-auto p-3" alt="${product.product_name}">
                                </div>
                                <div class="style-text text-left mt-3">
                                    <h6 class="mb-1">${product.product_name}</h6>
                                    <p class="mb-0">${product.total_returns} Returns</p>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
                topReturnedProductsList.append(productItem);
            });
        },
        error: function(error) {
            console.log('Error fetching top returned products:', error);
        }
    });
});


// Fetch Top Profitable Products
$(document).ready(function() {
    $.ajax({
        url: 'actions/get_top_profitable_products.php', 
        method: 'GET',
        success: function(response) {
            // console.log("Response from server:", response); // Debug statement to see the response

            const container = $('#top-profitable-products');
            container.empty(); // Clear existing content

            if (response.length === 0) {
                container.append('<p>No data available</p>'); // Handle case where no data is returned
                return;
            }

            response.forEach(product => {
                const productCard = `
                    <div class="card card-block card-stretch card-height-helf">
                        <div class="card-body card-item-right">
                            <div class="d-flex align-items-top">
                                <div class=" rounded">
                                    <img src="assets/images/stock/${product.image}" class="style-img img-fluid m-auto" alt="${product.product_name}">
                                </div>
                                <div class="style-text text-left">
                                    <h6 class="mb-2">${product.product_name}</h6>
                                    <p class="mb-2">Total Earned: $${product.total_earned}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(productCard);
            });
        },
        error: function(error) {
            console.log('Error fetching top profitable products:', error);
        }
    });
});


// stacked barchart for sales and biller category
$(document).ready(function() {
    $.ajax({
        url: 'actions/get_sales_by_biller.php',
        method: 'GET',
        dataType: 'json', // Ensure the response is parsed as JSON
        success: function(response) {
            // Process data to group by biller_name and category_name
            const categories = [];
            const billers = {};

            response.forEach(item => {
                const category = item.category_name;
                if (!categories.includes(category)) {
                    categories.push(category);
                }
            });

            response.forEach(item => {
                const biller = item.biller_name;
                const category = item.category_name;
                const totalSales = parseFloat(item.total_sales);

                if (!billers[biller]) {
                    billers[biller] = categories.map(() => 0);
                }

                const categoryIndex = categories.indexOf(category);
                billers[biller][categoryIndex] += totalSales;
            });

            const series = Object.keys(billers).map(biller => ({
                name: biller,
                data: billers[biller]
            }));

            var options = {
                series: series,
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },
                },
                xaxis: {
                    categories: categories,
                },
                title: {
                    text: 'Sales by Category and Biller'
                }
            };

            var chart = new ApexCharts(document.querySelector("#treemap"), options);
            chart.render();
        },
        error: function(error) {
            console.log('Error fetching sales data:', error);
        }
    });
});


// stacked barchart for sales and biiler category
$(document).ready(function() {
    $.ajax({
        url: 'actions/get_sales_by_biller.php', 
        method: 'GET',
        success: function(response) {
            // Process data to group by biller_name and category_name
            const categories = [];
            const billers = {};

            response.forEach(item => {
                const category = item.category_name;
                if (!categories.includes(category)) {
                    categories.push(category);
                }
            });

            response.forEach(item => {
                const biller = item.biller_name;
                const category = item.category_name;
                const totalSales = parseFloat(item.total_sales);

                if (!billers[biller]) {
                    billers[biller] = categories.map(() => 0);
                }

                const categoryIndex = categories.indexOf(category);
                billers[biller][categoryIndex] += totalSales;
            });

            const series = Object.keys(billers).map(biller => ({
                name: biller,
                data: billers[biller]
            }));

            var options = {
                series: series,
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },
                },
                xaxis: {
                    categories: categories,
                },
                title: {
                    text: 'Sales by Category and Biller'
                }
            };

            var chart = new ApexCharts(document.querySelector("#treemap"), options);
            chart.render();
        },
        error: function(error) {
            console.log('Error fetching sales data:', error);
        }
    });
});



// Fetch and Display Category Distribution

$.ajax({
    url: 'actions/get_category_data.php',
    method: 'GET',
    success: function(response) {
        console.log('Raw response from server:', response);

        const seriesData = response.map(item => ({
            x: item.category_name,
            y: parseInt(item.total_sales_count), 
            z: parseFloat(item.total_sales_amount) 
        }));

        const options = {
            chart: {
                type: 'bubble',
                height: 350,
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Sold',
                data: seriesData
            }],
            fill: {
                opacity: 0.8
            },
            title: {
                text: 'Category Distribution'
            },
            xaxis: {
                title: {
                    text: 'Categories'
                }
            },
            yaxis: {
                title: {
                    text: 'Total Sales Amount'
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + ' Sales';
                    }
                },
                z: {
                    formatter: function(value) {
                        return '$' + value.toFixed(2) + ' Total Sales Amount';
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#category-bubble-chart"), options);
        chart.render();
    },
    error: function(error) {
        console.log('Error fetching category data:', error);
    }
});




// Product Distribution by Category
$.ajax({
    url: 'actions/get_product_distribution.php',
    method: 'GET',
    success: function(response) {
        console.log('Raw response from server:', response);

        const seriesData = response.map(item => ({
            x: item.category_name.trim(), 
            y: parseInt(item.product_count),
            name: item.category_name.trim() 
        }));

        const options = {
            chart: {
                type: 'scatter',
                height: 350,
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Products',
                data: seriesData
            }],
            fill: {
                opacity: 0.8
            },
            title: {
                text: 'Product Distribution by Category'
            },
            xaxis: {
                title: {
                    text: 'Categories'
                },
                labels: {
                    rotate: -45,
                    formatter: function(value) {
                        return ''; 
                    }
                },
                categories: seriesData.map(data => data.x)
            },
            yaxis: {
                title: {
                    text: 'Number of Products'
                }
            },
            tooltip: {
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    const data = w.globals.series[seriesIndex][dataPointIndex];
                    const categoryName = seriesData[dataPointIndex].name;
                    const productCount = seriesData[dataPointIndex].y;
                    return '<div class="arrow_box">' +
                        '<span>' + categoryName + '</span><br>' +
                        '<span>' + productCount + ' Products</span>' +
                        '</div>';
                }
            },
            markers: {
                size: 6,
                colors: ['#FF0000'], 
                shape: 'circle',
                hover: {
                    sizeOffset: 4
                }
            },
            theme: {
                monochrome: {
                    enabled: true,
                    color: '#FF0000',
                    shadeTo: 'light',
                    shadeIntensity: 0.65
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#category-scatter-chart"), options);
        chart.render();
    },
    error: function(error) {
        console.log('Error fetching product distribution data:', error);
    }
});
