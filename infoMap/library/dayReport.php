<?php
ob_start(); // ensures anything dumped out will be caught  
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//echo var_dump($_SESSION);

if(!isset($_POST)){
    echo 'no post value';
    die; 
}

$arrRecord = $_POST;


$comPos = $arrRecord['com_pos'];
$paidDate = $arrRecord['paid_date'];
$showMode = $arrRecord['show_mode'];

//echo var_dump($arrRecord);

unset($arrRecord['paid_by']);
unset($arrRecord['staff_name']);

$process_date_time = date('Y-m-d');
$cashierName = 'cashier';

include_once($_SERVER['DOCUMENT_ROOT'].'/app/ArrayJob.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/app/StringManipulation.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/app/MyDebug.php'); 
include_once($_SERVER['DOCUMENT_ROOT']."/CloudConsultant/mydbclass.php");

/*if(SEARCH_OLD_PLAT_DB == 'true'){
  $searchOldPlatform = true;
}
else
{
  $searchOldPlatform = false;
}*/

?>

<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--NEWER VERSION OF jQuery-->
<link rel="stylesheet" type="text/css" href="<?PHP echo BRANCH_CSS."CSS"; ?>/jquery-ui-1.11.4.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?PHP echo BRANCH_CSS."CSS"; ?>/This.css" />
<script src="<?PHP echo BRANCH_CSS."CSS"; ?>/jquery-1.11.1.min.js"></script>
<script src="<?PHP echo BRANCH_CSS."CSS"; ?>/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script>
    var showMode = '<?php echo $showMode; ?>';
    
    var printBill = {
        decorateBill: function(){
            $('table.compbill').each(function(){
                if(showMode == 'group'){
                    //$('tr.items').remove();
                    //$('tr.group').attr('style','backgroud-color:gray');
                    $('tr.group').each(function(index, elem){
                       var sumCat = 0;
                       var sumDcat = 0;
                       var tableID = $(this).closest('table').attr('id');
                        
                        //console.log('check tr.' + $(this).attr('cat'));
                        $("table#" + tableID + " tr." + $(this).attr('cat')).each(function(index,elem){
                            sumCat = sumCat + parseFloat($(elem).find('.sumitem').text());
                            sumDcat = sumDcat + parseFloat($(elem).find('.sum_d_item').text());
                        })
                       
                       //$(this).find('.sumCat').text(sumCat);
                       //$(this).find('.sumDcat').text(sumDcat);
                       //$(elem).css('bacground-color:gray');
                    });
                    
                    
                    $('tr.group').attr('style','background-color:white;');
                    $('tr.items').addClass('hidden');
                    $('td.dateHeader').text('');
                }
                else
                {
                    
                    $('tr.group').remove();
                }
            })
            
            
        },
        
        nextTR: function($curTR , curSum){
            var $nextElem = $curTR.next('tr');
            var rtnSum;
            
            if(typeof($nextElem) != 'undefined'){
                return parseFloat($curTR.find('.sumItem').text());   
            }
            else if($nextElem.hasClass('.group')){
                return parseFloat($curTR.find('.sumItem').text());  
            }
            else
            {
                
                return parseFloat($(this)) + parseFloat(printBill.nextTR($(this)));
            }
        }
    } //End of NameSpace printBill
</script>
<style type="text/css">
@page {
    size: A5;
    margin: 0;
}    
    
@media print {
    .wizard-content{
        min-height: 0;
    }
    
    /*table {
        width: 210mm;
        height: 297mm;
        border-bottom: 1px solid black;
    }*/
    page[size="A4"] {
        page-break-inside: avoid;
        width: 10.5cm;
        height: 14.8cm;
    }
    
    tr:nth-child(even) {
        background-color:darkgrey;
        -webkit-print-color-adjust: exact; 
    }
    
    
    th {
        background-color: #A6D0CE;
        text-align: center;
        -webkit-print-color-adjust: exact; 
    }
}

body {
  background: rgb(204,204,204); 
  margin: 10px;
  width: 90%;
}
    
/*page[size="A4"] {
  background: white;
  width: 21cm;
  height: 29.7cm;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}*/
    
page[size="A4"] {
  background: white;
  width: 21cm;

  page-break-inside: avoid;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  
}
    
/*@media print {
  body, page[size="A4"] {
    box-shadow: 0;
    margin: 1cm auto;
  }
}*/

    

    
.ui-tabs-panel {
    height: 480;
    overflow-y: auto;
}

table {
	
    page-break-inside: avoid;
    width: 100%;
}
    
/*.page_break {
    page-break-before: always;
    page-break-inside: avoid;
    -webkit-region-break-inside: avoid;
    top: 0px;
}*/


tr:nth-child(even) {
    background-color: #f2f2f2;
}

th {
    background-color: #A6D0CE;
    text-align: center;
}
    
.comp1 {
    background-color: #A6D0CE;
}
    
.comp2 {
    background-color:darkorange;
}
    
.primary {
    background-color:darkorange;
    border-radius: 5px;
    margin: 2px;
}
    
.secondary {
    background-color:darkturquoise;
    border-radius: 5px;
    margin: 2px;
}
    
</style>
<title>Paid Bill by <?php echo $paidBy;  ?> ON <?php echo $process_date_time; ?></title>

</head>
<body style="font-size:12px:">

<?php
    
//echo 'MODE = '.$showMode.'<br>';

$db = new DB(DB_USER, DB_PASS, BRANCH_DB); 
$db->set_charset('utf8');

/* check connection */
if (mysqli_connect_errno()) {
	printf("Fail server connection, please try again");
	die();
}





$db = new DB(DB_USER, DB_PASS, BRANCH_DB);

//echo 'show paid bill on date '.$process_date_time.'<br><br>';

if($showMode == 'items'){
    /*$sql = "SELECT * FROM ".FIN_MX_TB." WHERE is_cancel=0 AND process_date_time LIKE '".$paidDate."%' AND order_to_com LIKE '%".$comPos."%' AND is_confirm=1 ORDER BY price_group, hospital_number, product_cat, pay_method, order_date_time, record_id";*/
    
    $sql = "SELECT * FROM ".FIN_MX_TB." WHERE is_cancel=0 AND process_date_time LIKE '".$paidDate."%' AND order_to_com LIKE '%".$comPos."%' AND is_confirm=1 ORDER BY price_group, hospital_number, slash_number, product_cat, pay_method, order_date_time, record_id";
}
else
{
    $sql = "SELECT *, SUM(amount * full_price) AS sum_cat, SUM(amount * discounted_price) AS sum_dcat FROM ".FIN_MX_TB." WHERE is_cancel=0 AND process_date_time LIKE '".$process_date_time."%' AND process_date_time LIKE '".$paidBy."%' AND order_to_com LIKE '%".$comPos."%' AND is_confirm=1 GROUP BY pay_method ORDER BY price_group, hospital_number, pay_method, product_cat, order_date_time, record_id";
}


//echo '$sql = '.$sql.'<br>';

$results = $db->select($sql);
$arrClCashTx = array();
$totalCash = array('value'=>0);
$totalCard = array('value'=>0);
$totalTransfer = array('value'=>0);

    
if($results == 'No match record.'){
    echo '<div class="mustard_round_rect">NO BILL PROCESSED ON '.$paidDate.'</div>';
    die;
}
    
echo '<page size="A4" class="mustard_round_rect" style="background-color:#ffaf00;"><div>END OF DAY CASHIER REPORT<br>ของเครื่อง CASHIER '.$comPos.'<br>ประจำวันที่ '.$paidDate.'</div></page>';

//echo 'there are '.count($results).' count<br>';
    
$curComp = $results[0]->price_group;
$curSlash = $results[0]->slash_number;

$tableCount = 1;
$tableKey = 1;
$curHN = $results[0]->hospital_number;
$curCat = '';


$curPayMethod = $results[0]->pay_method;
$arrClCashTx[0]=$results[0]->pay_method;  
$sumPrice = 0;
$sumDprice = 0;
$payHN = array();

$address = array('บจ.โอเรียนทอล เฮลท์ บิส'=>'1/18 ซ.วัชรพล 2/7 ถ.วัชรพล แขวง ท่าแร้ง เขต บางเขน กทม 10220','บจ.เมดิญา เอเซีย'=>'410 ซ.โชติวัฒน์ ซอย 3 ถ.ประชาชื่น แขวง บางซื่อ เขต บางซื่อ กทม. 10800',''=>'บิลเงินสด');
    
$color = array('บจ.โอเรียนทอล เฮลท์ บิส'=>'comp1','บจ.เมดิญา เอเซีย'=>'comp2',''=>'comp1');
    
$income = array('บจ.โอเรียนทอล เฮลท์ บิส'=>array('cash'=>0,'card'=>0,'transfer'=>0),'บจ.เมดิญา เอเซีย'=>array('cash'=>0,'card'=>0,'transfer'=>0),''=>array('cash'=>0,'card'=>0,'transfer'=>0));
    

$vatNum = array('บจ.โอเรียนทอล เฮลท์ บิส'=>'0105553105021','บจ.เมดิญา เอเซีย'=>'015558026161',''=>'');
    
$poLead = array('บจ.โอเรียนทอล เฮลท์ บิส'=>'OHB','บจ.เมดิญา เอเซีย'=>'MDY',''=>'PO');

$billPOnum = $poLead[$results[0]->price_group].$results[0]->hospital_number.'/'.$results[0]->slash_number;
//$billNum[$results[0]->pay_method][$billPOnum] = $billPOnum;
foreach(json_decode($results[0]->pay_method) as $key=>$pm){
    $billNum[$pm][$billPOnum] = $billPOnum;
}
    
$category = array('1'=>'ค่าตรวจ Screening', 
                  '2'=>'ค่าปรึกษาทางการแพทย์ / Consultation Fee',
                  '3'=>'ค่าน้ำเกลือและยาฉีด / IV fluid & Injection med.',
                  '4'=>'ค่าตรวจวินิจฉัยทางห้องปฏิบัติการ / Lab',
                  '5'=>'ค่าปฏิบัติการพยาบาล / Nursing',
                  '6'=>'ยากินและยากลับบ้าน / Home Medication',
                  '7'=>'การบำบัดโดย Therapist',
                  '8'=>'หมวดอื่นๆ',
                  '9'=>'SET TREATMENT'
                 );
    
foreach($results as $key=>$row){
    
    
    //if curComp changed
    if($curComp != $row->price_group || $tableKey % 25 == 1 || $curHN != $row->hospital_number || $curSlash != $row->slash_number ){
        $arrClCashTx[]=$row->pay_method; 
        
        $billPOnum = $poLead[$row->price_group].$row->hospital_number.'/'.$row->slash_number;
        //$billNum[$row->pay_method][$billPOnum] = $billPOnum;
        foreach(json_decode($row->pay_method) as $key=>$pm){
            $billNum[$pm][$billPOnum] = $billPOnum;
        }
        
        //echo ARR_PRINT($billNum).'<br>';
        
        
        ifEndBill($curComp,$row->price_group, $curHN,$row->hospital_number, $sumPrice, $sumDprice,$curSlash,$row->slash_number);
        
        
        echo '</table>';
        echo '</page>';

        if($tableKey % 25 == 1 && $key > 0){
            $ext = ' (cont.)';
        }
        else
        {
            $ext = '';
        }
        
        echo '<page size="A4">';
        echo '<table id="'.$tableKey.'" class="'.$row->price_group.' page_break compbill" count="'.$tableCount.'">';
        echo '<tr><th colspan="6" class="header '.$GLOBALS['color'][$row->price_group].'" style="text-align:left; width:90%; padding:10px;">'.$row->price_group.'<br>'.$address[$row->price_group].$vatTxt.$ext.'</th><th style="width:10%;" class="header '.$GLOBALS['color'][$row->price_group].'">'.$billPOnum.'</th></tr>';
        
        HN2DB(
        array('DB' => BRANCH_DB, 
              'TB' => CUSTOMER_TB, 
              'HN' => $row->hospital_number, 
              'fHN' => str_pad($row->hospital_number,6,'0',STR_PAD_LEFT), 
              'searchPlat' => $searchOldPlatform),
        $PtFullName);
        //echo 'fin1{'.$PtFullName.'}<br>';
        
        
        
        echo '<tr><th colspan="99" class="" style="text-align:left; width:30%; padding:10px; background-color:gray;">ค่าใช้จ่ายสำหรับ คุณ '.$PtFullName.'<br>HN'.$row->hospital_number.'<br>';
        echo '</th></tr>';
        
        echo '<tr><td colspan="1" style="text-align:left; padding:10px;">ลำดับ<br>การจ่ายที่</td><td class="dateHeader" style="text-align:left; padding:10px;">วันที่สั่ง</td><td></td>'.'</td><td>จำนวน</td><td>หน่วย</td><td>ราคาต่อหน่วย</td></tr>';
        
        
        
        $tableCount = $tableCount + 1;
        $tableKey = 1;
        $curHN = $row->hospital_number;
        $curSlash = $row->slash_number;
        $curCat = '';
        
        
    }
    
    
    
    if($curCat != $row->product_cat && $showMode == 'group'){
        
        
        if(!isset($category[$row->product_cat])){
            //echo 'undefine curText -> set [8]';
            $catText = $category['8'].' '.$row->product_cat;
        }
        else
        {
            $catText = $category[$row->product_cat];
        }
        
        echo '<tr class="group" cat="cat'.$row->product_cat.'" " style="color:white; background-color:black;"><td colspan="5">'.$catText.'</td><td class="sumCat" style="font-style: italic;">'.$row->sum_cat.'</td><td class="sumDcat hidden" style="font-style: italic;">'.$row->sum_dcat.'</td></tr>';
        $curCat = $row->product_cat;
    }
    else
    {
        //echo '<tr class="yellow_canvas"><td></td><td>curCat = '.$curCat.' row->prodcut_cat='.$row->product_cat.'</td><td>**'.$catText.'<td></tr>';
    }
    
    
    
    echo '<tr class="items cat'.$row->product_cat.'"><td style="color:#A6D0CE; width:80px;">'.$row->pay_method.'</td><td class="hidden price_group">'.$row->price_group.'</td><td class="hidden cl_cash_tx">'.$row->pay_method.'</td><td class="hidden product_cat">'.$row->product_cat.'</td><td class="hidden hn">'.$row->hospital_number.'</td><td>'.substr($row->order_date_time,0,10).'</td><td>'.$row->product_name.'</td><td>x '.$row->amount.'</td><td>'.$row->unit.'</td><td>'.$row->full_price.'</td><td class="hidden d_price">'.$row->discounted_price.'</td><td class="sumitem hidden">'.($row->amount * $row->full_price).'</td><td class="sum_d_item hidden">'.($row->amount * $row->discounted_price).'</td></tr>';
    
    //if($curPayMethod != $row->pay_method){
        //$arrClCashTx = array();
        $arrClCashTx[$row->pay_method] = $row->pay_method;
        $curPayMethod = $row->pay_method;
        
        //echo 'gonna find HN name in this row HN'.$row->hospital_number.'(2)<br>';
        HN2DB(
        array('DB' => BRANCH_DB, 
              'TB' => CUSTOMER_TB, 
              'HN' => $row->hospital_number, 
              'fHN' => str_pad($row->hospital_number,6,'0',STR_PAD_LEFT), 
              'searchPlat' => $searchOldPlatform),
        $PtFullName);
        //echo 'fin2<br>';
        
        //$GLOBALS['payHN']['tx'.substr($curPayMethod,1,strlen($curPayMethod)-2)][] = $PtFullName;
        
    //}
    
    $GLOBALS['payHN']['tx'.substr($curPayMethod,1,strlen($curPayMethod)-2)][$ptFullName] = $PtFullName;
    
    if($showMode == 'items'){
        $sumPrice = $sumPrice + ($row->amount * $row->full_price);
        $sumDprice = $sumDprice + ($row->amount * $row->discounted_price);
    }
    else
    {
        $sumPrice = $sumPrice + ($row->sum_cat);
        $sumDprice = $sumDprice + ($row->sum_dcat);
    }
    $curComp = $row->price_group;
    $tableKey = $tableKey + 1;
    
}

ifEndBill($curComp,'last sale acc', $curHN,'last row', $sumPrice, $sumDprice);
    
//echo var_dump($totalCash).'<br>';

echo '</td></tr></table></page><br><br><br>';
echo '<page size="A4"><table ><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>สรุปยอดส่งเงิน<br>ณ วันที่<br>'.$paidDate.'</td><td class="brown_round_rect" style="width:50%;">';
echo 'TOTAL CASH PAY: '.$totalCash['value'].'<br>';
echo 'TOTAL CARD PAY: '.$totalCard['value'].'<br>';
echo 'TOTAL TRANSFER: '.$totalTransfer['value'].'<br>';
echo '<hr><br>';
echo 'SUM INCOME: '.($totalCash['value'] + $totalCard['value'] + $totalTransfer['value']).' บาท';
echo '</td><td>&nbsp;</td><td>Cashier __________________<br>ผู้รับเงิน __________________<br>ณ วันที่ __________________</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table></page>';
//echo 'arrClCashTx ---v<br>';
//echo var_dump($arrClCashTx).'<br>';
//concludePayMethod($arrClCashTx);
    
function ifEndBill($curComp,$rowprice_group,$curHN,$rowhospital_number, &$sumPrice, &$sumDprice,&$curSlash,&$rowslash_number){
    //echo 'ifEndBill $rowhospital_number = '.$rowhospital_number.'<br>';
    
    
    
    if($curHN != $rowhospital_number || $curComp != $rowprice_group || $curSlash != $rowslash_number){
        echo '<tr><th colspan="6" class="" style="text-align:right; width:30%; padding:10px; background-color:gray;">รวมค่าใช้จ่ายสำหรับใบเสร็จนี้</th><th style="text-align:right; padding:10px; background-color:gray;">&nbsp;</th></tr>';

        if($sumPrice == $sumDprice){
            $sumText = 'รวมทั้งบิล';
            //echo 'row price_group = ]'.$row->price_group.'[<br>';
            //echo ']'.$GLOBALS['color'][(string)$row->price_group].'[<hr>';
            
            echo '<tr><th colspan="6" style="text-align:right; width:30%; padding:10px; background-color:#dddddd;">'.$sumText.' '.$sumPrice.' บาท'.'</th><th style="text-align:right; padding:10px; background-color:#dddddd;">&nbsp;</th></tr>';
        }
        else
        {
            $sumText = 'ค่าใช้จ่ายเบื้องต้น';
            echo '<tr><th colspan="6" class="" style="text-align:right; width:30%; padding:10px; background-color:white;">'.$sumText.' '.$sumPrice.' บาท'.'</th><th style="text-align:right; padding:10px; background-color:white;">&nbsp;</th></tr>';
            $sumText = 'ส่วนลด';
            echo '<tr><th colspan="6" class="" style="text-align:right; width:30%; padding:10px; background-color:white;">'.$sumText.' '.($sumPrice - $sumDprice).' บาท'.'</th><th style="text-align:right; padding:10px; background-color:white;">&nbsp;</th></tr>';
            $sumText = 'รวมทั้งบิล';
            echo '<tr><th colspan="6" class="" style="text-align:right; width:30%; padding:10px; background-color:#dddddd;">'.$sumText.' '.$sumDprice.' บาท'.'</th><th style="text-align:right; padding:10px; background-color:#dddddd;">&nbsp;</th></tr>';
        }
        
        if($GLOBALS['showMode'] != 'preview'){
            if($rowprice_group == 'last sale acc'){
                echo '<tr><td colspan="4" style="background-color:#ffffff;"></td><td colspan="2" style="padding:10px;  background-color:#ffffff;" align="right"><br>ได้รับชำระแล้ว<br>เมื่อวันที่ '.$GLOBALS['process_date_time'].'<br><br><br>Cashier ________________<br></td></tr>';
                
                concludePayMethod($GLOBALS['arrClCashTx']);
            }
            else{
                echo '<tr><td colspan="4" style="background-color:#ffffff;">'.'</td><td colspan="2" style="padding:10px; background-color:#ffffff;" align="right"><br>ได้รับชำระแล้ว<br>เมื่อวันที่ '.$GLOBALS['process_date_time'].'<br><br><br>Cashier ________________<br></td></tr>';
            }
        }
        else
        {
            echo '<br>';
            echo '<font style="font-size:2em;">ใบแจ้งค่าใช้บริการ (INVOICE)</font>';
            echo '<br>';
        }

        $sumPrice = 0;
        $sumDprice = 0;
    }
    else
    {
        //echo '<tr><td>sameHN '.$curHN.'</td></tr>';
    }
}

function vatTxt($arrClCashTx){
    if($GLOBALS['showMode'] == 'preview'){
        return false;
    }
    
    $db = new DB(DB_USER, DB_PASS, BRANCH_DB);
    $db->set_charset('utf8');
    
    $arrTx = array();

    
    $txCount = 0;
    {
        $where = '';
        $txClass = '';
        foreach($arrClCashTx as $recID){
            //echo 'recID = '.$recID.'<br>';
            $fTx = $recID;//substr($recID,1,strlen($recID)-2);
            
            $eachTx = explode(',',$fTx);
            $txCount = count($eachTx);
            
            foreach($eachTx as $txNum){
                $where = $where." OR record_id = '".$txNum."'";
                $txClass = $txClass." tx".$txNum;
            }
            
        }
        $where = '('.substr($where,4).')';
        

        $table = CL_CASH_TX;
    }

    

    $sql = 'SELECT * FROM '.$table.' WHERE '.$where;

    //echo 'sql = '.$sql.'<hr>';

    $records = $db->select($sql);
    foreach($records as $key=>$row){
        if($row->is_vat == 1){
            return false;
        }
    }
    
    return false;
}
    
function concludePayMethod($arrClCashTx){
    $db = new DB(DB_USER, DB_PASS, BRANCH_DB);
    $db->set_charset('utf8');
    
    $arrTx = array();

    
    $txCount = 0;
    {
        $where = '';
        $txClass = '';
        foreach($arrClCashTx as $recID){
            //echo 'recID = '.$recID.'<br>';
            $fTx = substr($recID,1,strlen($recID)-2);
            
            $eachTx = explode(',',$fTx);
            $txCount = count($eachTx);
            
            foreach($eachTx as $txNum){
                $where = $where." OR record_id = '".$txNum."'";
                $txClass = $txClass." tx".$txNum;
            }
            
        }
        $where = '('.substr($where,4).')';
        

        $table = CL_CASH_TX;
    }

    

    $sql = 'SELECT * FROM '.$table.' WHERE '.$where;



    $records = $db->select($sql);
    
    echo '</table></page><page size="A4"><table><tr><td class="mustard_round_rect"  style="background-color:#ffaf00;" colspan="99">';
    if($txCount > 1){
        echo 'NOTE จ่ายหรือรูดบัตรรวมกันหลายบิล<br>';
    }
    echo '<paidfor class="'.$txClass.'">';
    

    
    $arrBundleName = $GLOBALS['payHN'];
    
    foreach($GLOBALS['payHN'] as $key=>$bundleName){
        //echo '$bundleName = '.var_dump($bundleName).'<br>';
        if(!isset($arrBundleName[$bundleName])){
            $arrBundleName[$bundleName] = $bundleName;
        }
    }
    

    echo '</paidfor><br>';
    
    

    
    
    foreach($records as $key=>$row){
        echo 'transaction id '.$row->record_id.'<br>';
        echo 'pay with<br>';
        //echo var_dump($GLOBALS['billNum']).'<hr>';
        if($row->cash != 0 || 
           ($row->cash == 0 && $row->card == 0 && $row->transfer == 0)){ 
            echo '- cash '.$row->cash.' บาท<br><br><bundle id="'.$key.'" class="tx">';
            foreach($GLOBALS['billNum'][''.$row->record_id.''] as $key2=>$billPOnum){
                echo '&nbsp;&nbsp;&nbsp;'.$billPOnum.'<br>';
            };
            echo '</bundle>';
        };
        if($row->card != 0){ 
            echo '- card '.$row->card.' บาท<br><br><bundle id="'.$key.'" class="tx">'; 
            foreach($GLOBALS['billNum'][''.$row->record_id.''] as $key2=>$billPOnum){
                echo '&nbsp;&nbsp;&nbsp;'.$billPOnum.'<br>';
            };
            echo '</bundle>';
        };
        if($row->transfer != 0){ 
            echo '- transfer '.$row->transfer.' บาท<br><br><bundle id="'.$key.'" class="tx">'; 
            foreach($GLOBALS['billNum'][''.$row->record_id.''] as $key2=>$billPOnum){
                echo '&nbsp;&nbsp;&nbsp;'.$billPOnum.'<br>';
            };
            echo '</bundle>';
        };
        if($row->is_vat != 0){ echo '<font color="red">REMARK : ขอทำบิลเบิก / ใบกำกับภาษี</font><br>'; };
        
        if(!isset($GLOBALS['totalCash'][$row->record_id])){
            if(isset($GLOBALS['totalCash'])){ $GLOBALS['totalCash']['value'] = $GLOBALS['totalCash']['value'] + $row->cash; }
            if(isset($GLOBALS['totalCard'])){ $GLOBALS['totalCard']['value'] = $GLOBALS['totalCard']['value'] + $row->card; }
            if(isset($GLOBALS['totalTransfer'])){ $GLOBALS['totalTransfer']['value'] = $GLOBALS['totalTransfer']['value'] + $row->transfer; }
            $GLOBALS['totalCash'][$row->record_id] = $row->record_id;
        }
        echo '<div class="txhr" id="'.$key.'"><hr></div><br>';
    }
    //echo '</td><td colspan="2" style="padding:10px;">ได้รับชำระค่าใช้จ่ายเป็นที่เรียบร้อยแล้ว<br><br><br>Cashier ________________<br></td></tr>';
    echo '</td></tr></table></page>';
}

//echo json_encode($results, JSON_FORCE_OBJECT);
function HN2DB($params, &$PtFullName){
    $db = new DB(DB_USER, DB_PASS, $params['DB']);
    $db->set_charset('utf8');

    //echo '$params["searchPlat"] = '.$params['searchPlat'].'<br>';

    /*if($params['searchPlat']){
        $where = 'myID = "'.$params['fHN'].'"';
        $table = 'historical_customer_table';
    }
    else*/
    {
        $where = 'serverID = "'.$params['HN'].'" OR siteID = "'.$params['fHN'].'"';
        $table = $params['TB'];
    }



    $qHN = 'SELECT * FROM '.$table.' WHERE '.$where;


    $records = $db->select($qHN);


    if($records === false){
        $PtFullName = "error";
        return;
        //return $PtFullName;
    }


    if($records != "No match record."){
        $PtFullName = $records[0]->Name.' '.$records[0]->Surname;
        return;
        //return $PtFullName;
    }
    else if($GLOBALS['searchOldPlatform'])
    {
        //echo 'search oldPlat if No match record.';
        $db = new DB(DB_USER, DB_PASS, 'historical_customer');
        $db->set_charset('utf8');


        if($params['searchPlat']){
            $where = 'myID = "'.$params['fHN'].'"';
        }
        else
        {
            $where = 'serverID = "'.$params['HN'].'" OR siteID = "'.$params['fHN'].'"';
        }



        $qHN = 'SELECT * FROM historical_customer_table WHERE '.$where;
        //echo 'qHN = '.$qHN.'<br>';

        $records = $db->select($qHN);
        //echo var_dump($records);

        if($records === false){
            $PtFullName = "error";
            return;
            //return $PtFullName;
        }


        if($records != "No match record."){
            $PtFullName = $records[0]->Name.' '.$records[0]->Surname;
            return;
            //return $PtFullName;
        }
    }
}


?>
</table>   

<script>
$(document).ready(function(){
    printBill.decorateBill();
})
</script>
</body>