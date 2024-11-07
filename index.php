
<?php
session_start();
require_once 'PolygonAnalyzer.php';

$apiKey = 'yB0m7BdNgmQ_hlU6MQxahE83l1Hlxppy';
$analyzer = new PolygonAnalyzer($apiKey);
$css = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticker = isset($_POST['ticker']) ? strtoupper($_POST['ticker']) : '';
    $removeCount = isset($_POST['remove_count']) ? intval($_POST['remove_count']) : 1;
    $additionalCount = isset($_POST['additional_count']) ? intval($_POST['additional_count']) : 1;

    if (!empty($ticker)) {
        $fullResult = $analyzer->analyzeTicker($ticker, 0, 0);
        $shiftedResult = $analyzer->analyzeTicker($ticker, $removeCount, $additionalCount);
    }

    $css = "
        <div class='container'>
            <div class='column'>
                <div class='column-content'>
                    <h2>Shifted Data (Removed: {$removeCount})</h2>
                    {$shiftedResult}
                </div>
            </div>
            <div class='column'>
                <div class='column-content'>
                    <h2>Full Data</h2>
                    {$fullResult}
                </div>
            </div>
        </div>
    ";
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Stock Analysis Tool</title>
    <style>
        html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    .container {
        display: flex;
        min-height: 100vh;
    }
    .column {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    .column-content {
        overflow-y: auto;
    }

        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .panel {
            width: 250px;
            background-color: #f0f0f0;
            padding: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .panel-left {
            transform: translateX(-0%);
        }

        .panel-right {
            transform: translateX(0%);
        }

        .panel-left-open {
            transform: translateX(-90%);
        }
        .panel-right-open {
            transform: translateX(100%);
        }

        .panel-toggle {
            cursor: pointer;
            padding: 10px;
            background-color: #ddd;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .stock-list {
            font-weight: bold;
        }

        #paymentPopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <div id="leftPanel" class="panel panel-left">
        <div class="panel-toggle" onclick="togglePanel('leftPanel')">☰</div>
        <form method="POST">
            <input type="text" name="ticker" placeholder="Enter ticker symbol"><br>
            <input type="number" name="remove_count" placeholder="Backward"><br>
            <input type="number" name="additional_count" placeholder="Forward"><br>
            <input type="submit" value="Analyze">
        </form>
    </div>


    <div class="main-content">
        <h1>Stock Analysis Tool</h1>
        <div id="analysisResults"></div>
    </div>
    <script>
    const css = `<?= $css ?>`;
    // document.getElementById('analysisResults').style.display = 'block';
    document.getElementById('analysisResults').innerHTML = css;
    </script>

    <div id="rightPanel" class="panel panel-right">
        <div class="panel-toggle" onclick="togglePanel('rightPanel')">☰</div>
        <h3>Your Top Stocks</h3>
        <ul id="topStocks" class="stock-list"></ul>
    </div>

    <div id="paymentPopup">
        <h2>Upgrade Your Account</h2>
        <p>Get access to 3 stocks for 1 week for just $1. Each additional dollar extends access by 1 week, and 3 stocks</p>
        <div id="paypal-button-container"></div>
        <div id="paypal-container-ZM54XX6WM6W92"></div>
        <button onclick="closePaymentPopup()">Close</button>
    </div>

    <script
        src="https://www.paypal.com/sdk/js?client-id=BAAIpcvUhDhZzXTQc2hJHrgEO6E1WgvUT7ANjujrRbyw2AZJRefbD2sbdPq4D3l8sJvUyXdb5RVV0XzKhM&components=hosted-buttons&enable-funding=venmo&currency=USD"></script>
    <div id="paypal-container-ZM54XX6WM6W92"></div>
    <script>
        paypal.HostedButtons({
            hostedButtonId: "ZM54XX6WM6W92",
        }).render("#paypal-container-ZM54XX6WM6W92")

        let stockCount = 0;

        function showPaymentPopup() {
            document.getElementById('paymentPopup').style.display = 'block';
        }

        function closePaymentPopup() {
            document.getElementById('paymentPopup').style.display = 'none';
        }

        function updateUserAccess(stocks, days) {
            // This function would communicate with your backend to update the user's access
            console.log(`Updating user access: ${stocks} stocks for ${days} days`);
        }

        function checkStockCount() {
            stockCount++;
            if (stockCount % 3 === 0) {
                showPaymentPopup();
            }
        }

        // Modify your form submission to include checkStockCount
        document.querySelector('form').addEventListener('submit', function (e) {
            // e.preventDefault();
            // Your existing form submission logic here
            checkStockCount();
        });

        function togglePanel(panelId) {
            document.getElementById(panelId).classList.toggle(panelId + '-open');
        }

        function loadTopStocks() {
            let stocks = getCookie('topStocks');
            if (stocks) {
                stocks = JSON.parse(stocks);
                let list = document.getElementById('topStocks');
                stocks.forEach(stock => {
                    let li = document.createElement('li');
                    li.textContent = stock;
                    list.appendChild(li);
                });
            }
        }

        function getCookie(name) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }

        loadTopStocks();
    </script>
</body>

</html>