<?php
require_once 'biscuit.php';

class PolygonAnalyzer {
    private $apiKey;
    private $baseUrl = 'https://api.polygon.io/v2';
    private $dataDir = './tickers/';
    private $cngn;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        $this->cngn = new CNGN(5);
    }

    public function analyzeTicker($ticker, $removeCount, $additionalCount) {
        $filePath = $this->dataDir . $ticker . '.json';
        $string = "";
        if (!file_exists($filePath) || $this->isFileOld($filePath)) {
            $this->fetchTickerData(str_ireplace('.json','',$ticker));
        }

        $rets_sofar = $this->cngn->bitcoin($filePath, 15, 1, 0, $removeCount, $additionalCount);
        
        $string = "<table style='width:100%;margin-top:45px;'><tr><td style='width:30%;background-color:blue;color:white'>// ".$ticker."</td>";
        $string .= "<td style='width:20%;background-color:purple;color:white'><pipe id='cntr' ajax='counter.php' style='color:white' insert='cntr'></td></table><hr>";
        $string .= "<b style='margin-top:10px;'>". $rets_sofar[2]."</b>";
        $string .= "<br><Br><br>" . $rets_sofar[1] . "% accuracy";
        // $string .= "<table style='z-index:-1'>";
        $string .= $rets_sofar[0];
        // $string .= "</table>";
        return $string;
    }


    private function fetchTickerData($ticker) {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-1 year'));
        $ticker = explode('.', $ticker)[0];
        $url = "curl '{$this->baseUrl}/aggs/ticker/{$ticker}/range/1/day/{$startDate}/{$endDate}?apiKey={$this->apiKey}&format=csv' -o ./tickers/{$ticker}.json";

        $csvData = exec($url);
    }

    private function isFileOld($filePath) {
        $fileAge = time() - filemtime($filePath);
        return $fileAge > (60 * 24 * 60 * 60); // 60 days in seconds
    }
}
?>