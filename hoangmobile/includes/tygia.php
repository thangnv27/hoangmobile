<?php

class Rate {

    var $source;
    var $mydate;

    function getXML() {
        return file_get_contents($this->source);
    }

    function getRate() {
        $xmlData = NULL;
        $p = xml_parser_create();
        xml_parse_into_struct($p, $this->getXML(), $xmlData);
        xml_parser_free($p);
        $this->mydate = $xmlData['1']['value'];
        $data = array();
        if ($xmlData) {
            foreach ($xmlData as $v)
                if (isset($v['attributes'])) {
                    $data[] = $v['attributes'];
                }
            return $data;
        }
        return false;
    }

    /**
     * Show rate
     * 
     * @param string $show_type 'full' or 'short'
     */
    function show($show_type = "full", $maNT = array()) {
        $show_type = strtolower($show_type);
        $data = $this->getRate();
        
        if($show_type == "full"){
            echo <<<HTML
            <p>Tỷ giá ngoại tệ Vietcombank ngày: {$this->mydate}</p>
            <table width=435  class=tbl-01 cellpadding=0 cellspacing=0>
            <tr>
                <th align=center width=35>Mã NT</th>
                <th align="center" width="175">Tên ngoại tệ</th>
                <th align="center">Mua tiền mặt</th>
                <th align="center">Chuyển khoản</th>
                <th align="center">Bán</th>
            </tr>
HTML;
            foreach ($data as $k => $v) {
                $buy = ($v['BUY'] != 0) ? number_format($v['BUY'],2,'.',',') : "";
                $transfer = ($v['TRANSFER'] != 0) ? number_format($v['TRANSFER'],2,'.',',') : "";
                $sell = ($v['SELL'] != 0) ? number_format($v['SELL'],2,'.',',') : "";
                print '';
                echo <<<HTML
                <tr>
                    <td align="left">{$v['CURRENCYCODE']}</td>
                    <td align="right">{$v['CURRENCYNAME']}</td>
                    <td align="right">{$buy}</td>
                    <td align="right">{$transfer}</td>
                    <td align="right">{$sell}</td>
                </tr>
HTML;
            }
            print '</table>';
        }elseif ($show_type == "short") {
            echo <<<HTML
            <p>Cập nhật: {$this->mydate}</p>
            <table cellpadding=0 cellspacing=0 width="100%">
                <tr>
                    <th align="left">Mã NT</th>
                    <th align="left">Mua</th>
                    <th align="left">Bán</th>
                </tr>
HTML;
            if(empty($maNT)){
                foreach ($data as $k => $v) {
                    $buy = ($v['BUY'] != 0) ? number_format($v['BUY'],2,'.',',') : "";
                    $sell = ($v['SELL'] != 0) ? number_format($v['SELL'],2,'.',',') : "";
                    echo <<<HTML
                    <tr>
                        <td>{$v['CURRENCYCODE']}</td>
                        <td>{$buy}</td>
                        <td>{$sell}</td>
                    </tr>
HTML;
                }
            }else{
                foreach ($data as $k => $v) {
                    $buy = ($v['BUY'] != 0) ? number_format($v['BUY'],2,'.',',') : "";
                    $sell = ($v['SELL'] != 0) ? number_format($v['SELL'],2,'.',',') : "";
                    if(in_array($v['CURRENCYCODE'], $maNT)){
                        echo <<<HTML
                        <tr>
                            <td>{$v['CURRENCYCODE']}</td>
                            <td>{$buy}</td>
                            <td>{$sell}</td>
                        </tr>
HTML;
                    }
                }
            }
            
            print '</table>';
            echo '<p>Nguồn: Vietcombank</p>';
        }
    }

}