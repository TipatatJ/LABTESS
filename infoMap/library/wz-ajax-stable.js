

$('.iDose').keyup(function(event){
        console.log($(this).text());
        
        var strDose = utility.readContentWithBreaks($(this));
        
        //$("#AlertMsg").text(strDose);
        //$("#myModal").modal();
        
        
        updateMiniCartDose($(this).closest('tr').find('.iProdID').html(),strDose);
    })

function updateMiniCartDose(productId,sessID, newDose){
    
    //#########################################################
    // IF JSON DOSE - change input, select val to text
    //#########################################################
    var $thisDose = $('<div>',{
          html: newDose
      })
      //var $isJSONtable = $($thisDose).find('tablist.JSONdose');
      var $isJSONtable = $($thisDose).find('tbody');
      var dosePost = '';

      console.log('is json length ' + $isJSONtable.length);

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