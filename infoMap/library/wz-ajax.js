

$('.iDose').keyup(function(event){
        console.log($(this).text());
        
        var keeptag = $(this).find('keeptag').length;
    
        if(keeptag == 0){
            var strDose = utility.readContentWithBreaks($(this));
            console.log('get rid break of ' + strDose)
        }
        else{
            var strDose = $(this).html();
            console.log('keep tag strDose ' + strDose)
        }
        //$("#AlertMsg").text(strDose);
        //$("#myModal").modal();
        
        
        updateMiniCartDose($(this).closest('tr').find('.iProdID').html(),strDose);
    })




$(document).on('click','.dropdown-toggle',function(){
    $(this).dropdown();
});

function updateMiniCartAmount(productId,sessID, newAmount){
        
    var dataSend = {
        prodID:productId,
        sessID:sessID,
        newAmount:newAmount
    };
    
    console.log('update miniCartAmount --v');
    console.dir(dataSend);
    
    $.ajax({
  		url:'library/updateMiniCartAmount.php',	//ร้องขอไปยังไฟล์ updateMiniCartDose.php
  		data: dataSend,	//ส่งรหัสสินค้าไป
  		type:'post',			//เลือก type เป็นแบบ get
        //processData: false,
        //dataType: 'json',
  		success:function(data){
			
            //$("#AlertMsg").text(data);
            //$("#myModal").modal();
    		
  		},
        error: function(xhr, textStatus, errorThrown){
           $("#AlertMsg").text(textStatus);
           $("#myModal").modal();
        }
  	});
}

function updateMiniCartDose(productId,sessID, newDose){
    
    //#########################################################
    // IF JSON DOSE - change input, select val to text
    //#########################################################
    //console.clear();
    console.log('updateMiniCartDose');
    
    var $thisDose = $('<div>',{
          html: newDose
      })
    
      var keeptag = $thisDose.find('keeptag').length;
      //var $isJSONtable = $($thisDose).find('tablist.JSONdose');
      var $isJSONtable = $($thisDose).find('tbody');
      var dosePost = '';

      //console.log('is json length ' + $isJSONtable.length);
      console.log('updateMiniCartDose newDose--v');
      console.dir(newDose);

      if($isJSONtable.length > 0){
           $isJSONtable.find('input').each(function(index,elem){
               var varInp = $(this).attr('curval');
               console.log('<input>(' + index + ') = ' + varInp);
               $(this).closest('td').html(varInp).addClass('hideborder');
           })

           $isJSONtable.find('select.prep_method').each(function(index,elem){
               var selTXT = $(this).attr('cursel');
               console.log('<select>(' + index + ') = ' + selTXT);
               $(this).closest('td').html('&nbsp;' + selTXT).addClass('hideborder');
           })

          $isJSONtable.find('.input_box').removeClass('input_box');
          $isJSONtable.find('.mini_box').removeClass('mini_box');
          $isJSONtable.find('#addReg').remove();

           dosePost = '<table>' + $isJSONtable.html() + '</table>';
           $thisDose.removeClass('doseBox');

           //console.log('mod Dose for dosePost --v');
           //console.log(dosePost);

      }
      else if(keeptag > 0)
      {
          
           console.log('keeptag, so process as HTML string');
           console.log(newDose);
           dosePost = newDose;
      }
      else
      {
           console.log('process as TEXT string');
           console.log($thisDose);
           dosePost = utility.readContentWithBreaks($thisDose);
      }
    //################################################################
    
    var dataSend = {
        prodID:productId,
        sessID:sessID,
        newDose:dosePost 
    };
    
    console.log('update miniCartDose --v');
    console.dir(dataSend);
    
    
  	$.ajax({
  		url:'library/updateMiniCartDose.php',	//ร้องขอไปยังไฟล์ updateMiniCartDose.php
  		data: dataSend,	//ส่งรหัสสินค้าไป
  		type:'post',			//เลือก type เป็นแบบ get
        //processData: false,
        //dataType: 'json',
  		success:function(data){
			
            //$("#AlertMsg").text(data);
            //$("#myModal").modal();
    		
  		},
        error: function(xhr, textStatus, errorThrown){
           $("#AlertMsg").text(textStatus);
           $("#myModal").modal();
        }
  	});
    

}

