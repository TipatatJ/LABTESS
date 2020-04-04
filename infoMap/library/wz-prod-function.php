<?php
require_once 'config.php';

//adapt from itemDat.php

//$result = new array();

/*function doseOptionize(doseOption){
    $eachDose = explode('@',$doseOption);
		
    if(count($eachDose) > 1){ //[1]start
        $mergeDose = array();
        $results['dose_option'] = array();
        foreach($eachDose as $key => $value){
            //$eachDose[$key] = preset($value);

            $rtn = preset($value);
            if(is_array($rtn)){

                foreach($rtn as $value){



                    array_push($results['dose_option'],$value);
                }
            }
            else
            {
                $results['dose_option'] = $rtn;
            }
        }
    }
    else //[1]else
    {

        $results['dose_option'] = preset($doseOption);
    } //[1]end



    //###############################################


    $formerPrice = latestPrice();

    //echo 'formerPrice<br>';
    //echo var_dump($formerPrice).'<br>';

    if($formerPrice['bplus_id'] == 'no record'){
        $results['price'] = $row->selling_price;
        $results['discounted_price'] = 'not defined';
        //$results['dose_option'] = 'as recommend';
    }
    else
    {
        $results['price'] = $row->selling_price;
        $results['discounted_price'] = $formerPrice['discounted_price'];
        $results['dose_option'] = $formerPrice['last_dosage'];
    }
}*/

/*function latestPrice(){
	$rtn = array();
	
	if(!isset($_GET['pt_info'])){
		$rtn['bplus_id'] = 'no record';
		return $rtn; 
	}
	
	if($_GET['pt_info'] !== '?'){
		$pt_info = $_GET["pt_info"];
		
		
		if(isset($pt_info['pt_hn'])){ 
			$pt_hn = ltrim($pt_info['pt_hn'], '0'); 
		}
		else
		{
			$rtn['bplus_id'] = 'no record';
			return $rtn;
		}
		
		if(isset($pt_info['pt_bill_rec'])){
			$pt_bill_rec = $pt_info['pt_bill_rec']; 
		}
		else
		{
			$rtn['bplus_id'] = 'no record';
			return $rtn;
		}
		
		
		
		$db2 = new DB(DB_USER, DB_PASS, 'fin_mx');
		$db2->set_charset('utf8');
		
		$ptOldBill = "SELECT * FROM bill_to_cash WHERE hospital_number='".$pt_hn."' AND record_id='".$pt_bill_rec."' ORDER BY record_id DESC LIMIT 1;";
		
		$oldPrice = $db2->select($ptOldBill);
		
		//echo $ptOldBill.'<br>';
		//echo '$oldPrice ==v<br>';
		//echo var_dump($oldPrice);
		
		if($oldPrice != 'No match record.'){
			foreach ($oldPrice as $key => $row){
				$rtn['bplus_id'] = $row->bplus_id;
				$rtn['discounted_price'] = $row->discounted_price;
				$rtn['last_dosage'] = $row->dosage;
			}
		}
		else
		{
			$rtn['bplus_id'] = 'no record';
			$rtn['last_dosage'] = 'as recommend';
		}
	}
	else
	{
		$pt_info = 'n/a';
		$rtn['bplus_id'] = 'no record';
	}
	
	//echo var_dump($rtn).'<br>';
	
	
	return $rtn;
}*/

function preset($doseOption){
	//$db2 = new DB(DB_USER, DB_PASS, BRANCH_DB);
	//$db2->set_charset('utf8');
    
    //echo var_dump($doseOption).'<br>';
    
    $stdDose = array();
	
	$test1 = explode('[+]',$doseOption);
    
    //echo '$test1 = '.$test1.'<br>';
	
    if (strpos($doseOption, '[+]') !== false) {
	//if(count($test1) > 1){ //[2.1]start

		$test2 = $test1[0];
		$prefer = $test1[1];
        
        //echo 'manage as [+] descriptive<br>';
	} //[2.1]else
	else
	{
		$test2 = $doseOption;
		$prefer = 0;
	} //[2.1]end

	
	$setNum = explode('#',$test2);
    
    //echo 'setNum = '.$setNum.'<br>';

    
	$rtnOption = array();
	if(isset($_GET['pt_info'])){
	  if($_GET['pt_info'] !== '?'){
		  $pt_info = $_GET["pt_info"];
		  if(isset($pt_info['wt_kg'])){ $pt_wt = $pt_info['wt_kg']; }
	  }
	  else
	  {
		  $pt_info = 'n/a';
	  }
	}
	else
	{
		$pt_info = 'n/a';
	}
	//echo '$pt_wt = '.$pt_wt.'<br>';
	
    //try {
        if (strpos($test2, '#') !== false && is_numeric($setNum[1])){
                //throw new Exception($doseOption);

        //else {

            $sql = "SELECT * FROM common_drug_dose WHERE set_number = $setNum[1] ORDER BY set_number, appear_order;";
            $result = dbQuery($sql,'ERROR SETNUM ]'.$setNum[1].'[');

            while ($row = dbFetchAssoc($result)) {
                //echo '-----row-----<br>';
                //echo var_dump($row);
                $stdDose[] = $row;	//นำข้อมูลจากฐานข้อมูลเก็บเข้า array
            }

            $stdDose['prefer'] = $prefer;

            return $stdDose;
        }
        else
        {
            return $doseOption;
        }
	/*}
    catch (Exception $e) {
        $results[0] = $e->getMessage()."[!!!]";
    }*/

	
}

function varPaint($finalDose){

    if (strpos($finalDose, '((VAR') !== false) {
        $finalDose = preg_replace('/\(\(VAR([0-9]+)/', '<var  class="variable" style="width:100px; background-color: yellow;" contenteditable="true">&nbsp;&nbsp;&nbsp;</var>', $finalDose);

        //$finalDose = str_replace("))","",$finalDose);
        //$finalDose = str_replace('[ENTER]',"<br>",$finalDose);

    }
    
    if (strpos($finalDose, '((NTH') !== false) {
        $finalDose = preg_replace('/\(\(NTH([0-9]+)/', '<nth  class="variable input_box" style="width:100px; background-color:LightSeaGreen;" contenteditable="true" nth="nth">&nbsp;ครั้งที่ #</nth>', $finalDose);

        //$finalDose = str_replace("))","",$finalDose);
        //$finalDose = str_replace('[ENTER]',"<br>",$finalDose);

    }
    
    $finalDose = str_replace("))","",$finalDose);
    $finalDose = str_replace('[ENTER]',"<br>",$finalDose);
    
    return $finalDose;
}

?>