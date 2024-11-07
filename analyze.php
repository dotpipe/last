<?php
// require_once 'btc.php';
// require_once 'PolygonAnalyzer.php';

// $apiKey = 'yB0m7BdNgmQ_hlU6MQxahE83l1Hlxppy';
// $analyzer = new PolygonAnalyzer($apiKey);

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ticker'])) {
//     $ticker = strtoupper($_POST['ticker']);
//     $result = $analyzer->analyzeTicker($ticker);
    
//     // Process and display the result
//     print_r($result);

require_once 'PolygonAnalyzer.php';

$apiKey = 'yB0m7BdNgmQ_hlU6MQxahE83l1Hlxppy';
$analyzer = new PolygonAnalyzer($apiKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticker = isset($_POST['ticker']) ? strtoupper($_POST['ticker']) : '';
    $removeCount = isset($_POST['remove_count']) ? intval($_POST['remove_count']) : 0;
    $additionalCount = isset($_POST['additional_count']) ? intval($_POST['additional_count']) : 0;

    if (!empty($ticker)) {
        $fullResult = $analyzer->analyzeTicker($ticker, 0, 0);
        $shiftedResult = $analyzer->analyzeTicker($ticker, $removeCount, $additionalCount);
    }
    $css = "<style>
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
</style>

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
    echo $css;
}