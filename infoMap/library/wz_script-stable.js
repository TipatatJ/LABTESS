


//####################################################
//      Declare Name Space PtSearch
//####################################################

var PtSearch = {
AcompInit: function AcompInit(THIS){


    $(THIS).autocomplete(
     {
      appendTo: "#PointedProd",
      source: "library/searchAllParam.php",
      minLength: 1,
      select: function( event, ui ) {

          //console.dir(ui.item)

          PtSearch.UpdatePtData(ui);
          
          //$("#FromDate");

      }}).keydown(function(e){
      var keyCode = e.keyCode || e.which;

      console.log('keyCode = ' + keyCode);

      if (keyCode == 13) {
          e.preventDefault();
          //$('.college').trigger('click');
          $('.acompPt').autocomplete( "close" );

          if($('.acompPt').text() != ''){
            $('.acompPt').autocomplete('option', 'source', "library/searchAllParam.php")
          }
          else
          {
            $('.acompPt').autocomplete('option', 'source', "library/searchTodayPt.php")
            $('.acompPt').autocomplete( "search", "search today orders" );
          }
          //alert('user search');

      }
      else
      {
          $('.acompPt').autocomplete('option', 'source', "library/searchAllParam.php")
      }


      }); //END OF .keydown(function)

      //END OF AcompInit(THIS)
    },

UpdatePtData: function UpdatePtData(ui){
			ui = (typeof ui === 'undefined') ? { item: 
				{ id: "" ,
				  age: "",
				  label: "",
				  name: "",
				  lastname: "",
				  sex: ""
				} 
			} : ui;
		
			var fHN;
			var opName;

			// use this to determine whether opName was passed or not
			if (arguments.length == 1) {
				// b was not passed
				opName = "full name";
			} else {
				opName = arguments[1]; // take second argument
			}
			
			var dPercent = parseFloat($('#discountPercent option:selected').text());
			$('#discountPercent').val(0);
			
            fHN = ui.item.id;
			
            $.post('./library/targetPt.php',fHN,function(data){
               console.log(data); 
            });
			
			
			var PtHTML 
			
			//$('.acompPt').val()
			if(ui.item.id == "New member"){ //[1] start
				var query_val = $('.acompPt').val();
				var namePart = query_val.split(" ");
				ui.item.name = namePart[0];
				ui.item.lastname = namePart[1];
				
				var addPtButt = '';
				
				if($('#staff_id').text() == 2 || $('#staff_id').text() == 3 || $('#staff_id').text() == 94){
					addPtButt = '<td><img src="/ICONS/Images/addUser.gif" width="20" height="20" id="genNewPt"></td>';
					//alert('addPtButt');
				}
				

				PtHTML = $('<div class="clearfix"><br></div>' +
                          '<table id="pt_dat" style="padding: 5px;" class="">' +  
						  '<tr id="PtName"><td><b>Name:</b>&nbsp;</td><td id="pN">' + ui.item.name + '</td></tr>' +
						  '<tr id="PtLastname"><td><b>Last name:</b>&nbsp;</td><td id="pLN">' + ui.item.lastname + '</td></tr>' +
						  '<tr id="PtID"><td><b>HN:</b>&nbsp;</td><td id="HN">' + fHN + '</td>' + addPtButt + '</tr>' +
						  '<tr id="PtAge"><td><b>Age:</b>&nbsp;</td><td>' + ui.item.age + '</td></tr>' +
						  '</table>');
										
				$('#lockedPt').html(PtHTML); 
				$('#genNewPt').button();
				$('#genNewPt').bind('mousedown', function(){
					genNewHN();
				});
				
				
				if(opName == "full name"){
					$('#inpName').val(ui.item.name);
					$('#inpLastname').val(ui.item.lastname);
				}
				
				if(opName == "only name"){ //[2.1]if
					console.log($('#inpName').val());
					return $('#inpName').val();
				} //[2.1]end

				if(opName == "only lastname"){ //[2.2]if
					console.log($('#inpLastname').val());
					return $('#inpLastname').val();
				} //[2.2]end

			}
			else //[1]else
			{
				PtHTML =  '<div class="clearfix"><br></div>' +
                          '<table id="pt_dat" style="padding: 5px;">' +  
						  '<tr id="PtName"><td><b>Name:</b>&nbsp;</td><td id="pN">' + ui.item.name + '</td></tr>' +
						  '<tr id="PtLastname"><td><b>Last name:</b>&nbsp;</td><td id="pLN">' + ui.item.lastname + '</td></tr>' +
						  '<tr id="PtID"><td><b>HN:</b>&nbsp;</td><td id="HN">' + fHN + '</td></tr>' +
						  '<tr id="PtAge"><td><b>Age:</b>&nbsp;</td><td>' + ui.item.age + '</td></tr>' +
						  '</table>';
										
				$('#lockedPt').html(PtHTML); 
				$('#inpName').val(ui.item.name);
				$('#inpLastname').val(ui.item.lastname);
				
				if(opName == "only name"){ //[2.1]if
					return ui.item.name;
				} //[2.1]end

				if(opName == "only lastname"){ //[2.2]if
					return ui.item.lastname;
				} //[2.2]end
				
			} //[1] end
			
			$('.acompPt').attr('hn',fHN);
            $('.acompPt').val(fHN);
    
			PtSearch.UpdatePriorBill(ui);
			
			PtSearch.UpdateBriefHx();
			
            if(typeof(refreshPastHx) == 'function'){ refreshPastHx(); }
            refreshPostedOrder();
			//loadHistory.loadHistory();
	}, //end func UpdatePtData()
    
    UpdatePriorBill: function UpdatePriorBill(ui){
        $("#AlertHeader").text('TARGET HAS CHANGED');
        $("#AlertMsg").text(ui.item.name + ' ' + ui.item.lastname + '(' + ui.item.id + ') selected');
        $("#myModal").modal();
    },
    
    UpdateBriefHx: function UpdateBriefHx(){
        console.log ('func UpdateBriefHx() called');
    }
}

//######################################################
//   DECLARE Utility NAME SPACE
//######################################################

var utility = {
    
    findBootstrapEnvironment: function findBootstrapEnvironment() {
        
        var envs = ['xs', 'sm', 'md', 'lg'];

        var $el = $('<div>');
        $el.appendTo($('body'));

        for (var i = envs.length - 1; i >= 0; i--) {
            var env = envs[i];

            $el.addClass('hidden-'+env);
            if ($el.is(':hidden')) {
                $el.remove();
                return env;
            }
        }
    },
    
    readContentWithBreaks: function readContentWithBreaks(elem){
		if(elem[0].innerText){
		  return elem[0].innerText.replace(/\n/ig,"<br>");
		}else{
		  return elem.html();
		}
    }, //end Func readContentWithBreaks

    findCallee: function findCallee(){
        console.log(arguments.callee.caller.toString());
    }
}

//######################################################
//   DECLARE wzCartFunc NAME SPACE
//######################################################

var wzCartFunc = {
    updateDiscountPrice: function updateDiscountPrice(percent){
        var subTotal = 0;
        var objFieldVal = { };
                
        $('.cartList .iDiscountPrice').each(function(){
            var fullPrice = Number($(this).prev('td').text());
            var discountPrice = (100+percent)/100 * fullPrice;
            $(this).text(discountPrice);
            
            
            var amount = Number($(this).closest('tr').find('.inpAmount').val());
            var sumItem = discountPrice * amount;
            
            subTotal = subTotal + sumItem;
            $(this).closest('tr').find('.iItemSum').text(sumItem);
            
            
            
            objFieldVal[$(this).closest('tr').find('.iProdID').text()] = $(this).closest('tr').find('.iDiscountPrice').text(); 
        });
            
        $.post('library/updateMiniCartPrice.php', objFieldVal,function(data){
            console.dir(data);
        })
        
        $('#cartSubTotal.subTotal').text(subTotal);
        $('#cartGrandTotal.grandTotal').text(subTotal + Number($('#shippingCost').text().substring(1)));
    },
    
   presetDose: function presetDose(doseOption, newRow){

				var productID = $(newRow).attr('id');
                var dose_option;
       
                try{
                    dose_option = $.parseJSON(doseOption);
                    console.log('presetDose: doseOption is JSON type');
                }
                catch (e) {
                    dose_option = doseOption;
                };
       
                var $doseHTML = $('<div>',{
                    html: $(this).html()
                }); 
       
                console.log('<table> length = ' + $doseHTML.find('table').length);

                if($doseHTML.find('table').length){
                   console.log('presetDose: doseOption is <table>'); 
                   
                   var tProdID = $(newRow).closest('tr').find('.iProdID').text();
                   var tSessID = $(newRow).closest('tr').find('.iSessID').text();
                   updateMiniCartDose(tProdID, tSessID, $doseHTML.html());
                
                    
                   $('.prep_method').change(function(event){     
                        trigerUpdateDose($(this).closest('td.iDose'))
                   })
                }
			    else if(typeof(dose_option) != 'string'){ //[1]start
                      console.log('dose type is ' + typeof(dose_option));
                      var array = $.map(dose_option, function(value, index) {
                            var opTxt = value.full_th;

                            //var opTxt = wzCartFunc.varPaintJS(value.full_th);

                            return [opTxt];
                      });

                      if(dose_option.hasOwnProperty('b_name')){
                            
                            var $tDoseOption  = $(newRow).find('.iDose');
                            $tDoseOption.removeClass('doseBox');
                            $tDoseOption.html('');
                            var $blankTable = wzCartFunc.jsonDose($tDoseOption, doseOption);
                           
                            var tProdID = $(newRow).closest('tr').find('.iProdID').text();
                            var tSessID = $(newRow).closest('tr').find('.iSessID').text();

                            updateMiniCartDose(tProdID, tSessID, $blankTable.html());
                      }
                      else
                      {
                          var iDose = $(newRow).find('.iDose').html();
                          var sDose = iDose.split('@');
                          if(sDose.length){
                             console.log('@ split dose option');
                             var tProdID = $(newRow).closest('tr').find('.iProdID').text();
                             var tSessID = $(newRow).closest('tr').find('.iSessID').text();
                             var strDose = sDose[0];

                             console.log('update dose tProdID:' + tProdID + ' tSessID:' + tSessID);
                             updateMiniCartDose(tProdID, tSessID, strDose);
                          }
                          else
                          {
                              console.log('standard dose option');
                          }
                          
                              $(newRow).find('.iDose').autocomplete({ 
                                  source: array,
                                  minLength: 0,
                                  width: 200,
                                  change: function(event, ui){ 
                                     var tProdID = $(this).closest('tr').find('.iProdID').text();
                                     var tSessID = $(this).closest('tr').find('.iSessID').text();
                                     var strDose = $(this).html();


                                     updateMiniCartDose(tProdID, tSessID, strDose);
                                  },
                                  select: function(event, ui){
                                     event.preventDefault(); //**
                                     var tProdID = $(this).closest('tr').find('.iProdID').text();
                                     var tSessID = $(this).closest('tr').find('.iSessID').text();

                                     var strDose = wzCartFunc.varPaintJS(ui.item.value);
                                     $(this).html(strDose);

                                     updateMiniCartDose(tProdID, tSessID, strDose);                         
                                  }
                              }).bind('focus',function(){            
                                      $(this).autocomplete("search");
                              }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                                    var opTxt = wzCartFunc.varQ_JS(item.label);


                                    return $( "<li></li>" )
                                        .data( "item.autocomplete", item )
                                        .append( opTxt ) 
                                        .appendTo( ul );
                              };
                          

                    }
                }
				else //[1]else
				{
                    
                    //console.log('dose is string ->');
					$(newRow).find('.iDose').text(dose_option);
					/*$(newRow).find('.iDose').bind('keyup',function(){
                        //console.log(dose_option);
						//changeDB(newRow,'dosage');
					});*/
					
				} //[1]end
	},
    
    jsonDose: function jsonDose($tDoseOption, jsonDat){
            console.log('(f)jsonDose raw jsonDat ==v ');
            //console.dir(jsonDat)
            var jSonp;
            /*if(isJson(jsonDat)){
                jSonp = jsonDat;
            }
            else
            {*/
                jSonp = $.parseJSON(jsonDat);
            //}
            //console.dir(jSonp);
            
            var $tTD = $tDoseOption.closest('td');
            
            $tTD.find('tablist').remove();
            
            var $tablist = $('<tablist class="JSONdose" contenteditable="false">');
            $tablist.html('');
            var $blankTable = $('<table >').appendTo($tablist);
            var setType = jSonp.b_stock.remain.dose;

            if(typeof(bundName) == 'undefined'){
               bundName = jSonp.b_name;
            }
            
            $blankTable.append('<tr><td><b>' + bundName + '</b></td></tr>');
        
            if(setType == 'personal stock'){
                $blankTable.append('<tr><td colspan="4"><hr></td></tr>');
                $blankTable.append('<tr><td colspan="4" align="right" class="butt1"></td></tr>');
            }
            
            $blankTable.append('<tr><td colspan="4"><hr></td></tr>');
            $blankTable.append('<tr><td colspan="4" align="right" class="butt2"></td></tr>');

        
            $blankTable.append('<tr><td colspan="4"><hr></td></tr>');
            $blankTable.append('<tr><td colspan="4" align="right" class="butt3"></td></tr>');
            
            

            $.each(jSonp.b_compliment,function(bplus_id,objItem){
                    var objDat = { };
                
                    objDat['bplus_id'] = objItem['bplus_id'];
                    objDat['name'] = objItem['name'];
                    objDat['enname'] = objItem['enname'];
                    objDat['dose'] = objItem['dose'];
                    objDat['num'] = objItem['amount'];
                    objDat['cost'] = objItem['cost'];
                
                    
                
                    if(objDat['bplus_id'] != 'remain' && objDat['bplus_id'] != 'n/a'){
                        console.dir(objItem);
                        newCompliment($blankTable, objDat)
                        $tablist.append($blankTable, objDat);
                    }
            });
            
            $.each(jSonp.b_stock,function(bplus_id,objItem){
                
                var objDat = { };

                objDat['bplus_id'] = objItem['bplus_id'];
                objDat['name'] = objItem['name'];
                objDat['enname'] = objItem['enname'];
                objDat['dose'] = objItem['dose'];
                objDat['num'] = objItem['used'];
                objDat['cost'] = objItem['cost'];
                objDat['method'] = objItem['method'];


                

                
                if(objDat['bplus_id'] != 'remain' && objDat['bplus_id'] != 'n/a'){
                    console.dir(objItem);
                    //alert('bplus id = ' + objDat['bplus_id']);
                    newRegItem($blankTable, objDat);
                    $tablist.append($blankTable, objDat);
                }
            });
            
            
            $tTD.append($tablist);  
        
            if(setType == 'TCMdrug'){
                
                var prepMeth = {
                    1: '-----',
                    2: 'ต้มก่อน',
                    3: 'ต้มทีหลัง',
                    4: 'ห่อผ้า ต้มก่อน'
                }
                
                var $selPrepMethod = $('<select>',{
                    class: 'prep_method',
                    cursel: prepMeth[1]
                })
                
                $.each(prepMeth, function(index, optTXT){
                    var $opt = $('<option>',{
                        value: optTXT,
                        text: optTXT
                    })
                    
                    if(index == 1){
                        $opt.prop('selected',true);
                    }
                    
                    $selPrepMethod.append($opt);
                })
            
                
                var $itsDel = $('<img>',{
                    class:"btn-default", 
                    width:"15",
                    height:"15",
                    style:'position:relative; left:0px;',
                    src:"../ICONS/delete.png"
                });

                $itsDel.click(function(){
                    $(this).closest('tr').remove();
                })
                
                
                var $addButt = $('<div>',{
                    class: 'addRegItem btn btn-primary btn-xs',
                    text: 'add ingredient',
                    style: 'font-size:1em'
                });
                
                $addButt.click(function(){
                    var $inpIname = $('<input>',{
                        class: 'not_field setitem_name',
                        style: 'width:150px;',
                        bplus_id: '?',
                        value: '',
                        curval: '',
                        placeholder:'item name'
                    })

                    $inpIname.autocomplete({
                      source: "./library/accompChItemMixName.php",
                      minLength: 1,
                      select: function( event, ui ) {

                          //console.dir(ui.item)
                          var rtnItem = ui.item;

                          var prodName = rtnItem.product_name;
                          var prodChName = rtnItem.ch_name;
                          var prodMixName = rtnItem.mix_name;
                          var prodBplus = rtnItem.bplus_id;
                          var prodDose = rtnItem.dose;
                          var prodCost = rtnItem.cost;

                          var $tRow = $(this).closest('tr');
                          $tRow.attr('bplus_id',prodBplus);
                          $tRow.attr('cost',prodCost);
                          $tRow.find('input').attr('bplus_id',prodBplus);
                          $tRow.find('input.setitem_dose').val(''); //.val(prodDose);
                          $tRow.find('input.setitem_enname').val(prodMixName);
                          
                          $tRow.attr('en_name',prodChName);
                          $tRow.attr('name',prodName);
                          
                          $(this).attr('curval',prodMixName);
                          trigerUpdateDose($(this).closest('td.iDose'));
                          //updateTotalCost();
                      }
                    })
                    
                    $newIngredient = $('<tr>',{
                        html: '<td></td><td class="input_box"></td><td class="mini_box hideborder ing_dose" contenteditable="true">?</td><td class="ing_unit">g</td><td class="ing_method hideborder"></td>',
                        class: 'added_item doseTR'
                    });
                    $newIngredient.find('td:first').append($itsDel.clone(true));
                    $newIngredient.find('td:eq(1)').append($inpIname);
                    
                    var $newSel = $selPrepMethod.clone();
                    $newSel.change(function(){
                        $(this).attr('cursel',$(this).val());
                        trigerUpdateDose($(this).closest('td.iDose'))
                    })
                    
                    $newIngredient.find('td:last').append($newSel);
                    $blankTable.append($newIngredient);
                    //$newIngredient.find('td').addClass('hideborder');
                })

                var $hrAddItem = $('<tr id="addReg">');
                $hrAddItem.append('<td colspan="4"></td><td></td>');
                $hrAddItem.find('td:eq(1)').append($addButt);
                $blankTable.append($hrAddItem);
                
                
                $blankTable.find('tr.item_in_bund').each(function(){
                    
                    var trBplusId = $(this).attr('bplus_id');
                    var trMethod;
                    
                    console.dir(jSonp.b_stock);
                    $(this).find('td:eq(1)').attr('contenteditable',true).addClass('input_box');
                    $(this).find('td:eq(2)').addClass('mini_box ing_dose').attr('contenteditable',true);
                    $(this).find('td:eq(3)').text('g').addClass('ing_unit');
                    $(this).find('td:last').after('<td>');
                  
                    var $newSel = $selPrepMethod.clone();
                    
                    $.each(jSonp.b_stock,function(index,itemDat){
                        if(itemDat.bplus_id == trBplusId){
                            trMethod = itemDat.method;
                        }
                    })
                    
                    $($newSel).find('option').each(function(){
                        console.log($(this).text() + ' Vs ' + trMethod);
                        if($(this).text() == trMethod){
                            
                            $(this).prop('selected','true');
                        }
                    })
                    
                    $newSel.change(function(){
                        $(this).attr('cursel',$(this).val());
                        trigerUpdateDose($(this).closest('td.iDose'))
                    })
                   $(this).find('td:last').append($newSel).addClass('ing_method');
                   $(this).find('td').addClass('hideborder');
                })
            }
        
            //PREVENT $('#submitOrder').click(function(){ ... }) ERROR
            $blankTable.find('tr').each(function(){
                $(this).addClass('doseTR');
            });
            
            //########################################
            // DEFINE LOCAL FUNCTION()
            //########################################
        
            function newCompliment($table, objDat){
                var $inpIname = $('<div>',{
                    class: 'not_field compliment_name',
                    style: 'width:150px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.name,
                    placeholder:'item name'
                })


                var $inpIdose = $('<div>',{
                    class: 'not_field compliment_dose',
                    style: 'width:150px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.dose,
                    placeholder:'dose in set'
                })

                var $inpIamount = $('<div>',{
                    class: 'not_field compliment_amount',
                    style: 'width:50px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.num,
                    placeholder:'amount'
                })
                $inpIamount.keyup(function(){
                    updateTotalCost()
                })

                var $itsDel = $('<img>',{
                    class:"btn-default", 
                    width:"15",
                    height:"15",
                    style:'position:relative; left:0px;',
                    src:"../ICONS/delete.png"
                });

                $itsDel.click(function(){
                    $(this).closest('tr').remove();
                })

                var $newTR = $('<tr>',{
                   class: 'comp_in_bund',
                   bplus_id: objDat.bplus_id,
                   cost: objDat.cost,
                   html: '<td></td><td></td><td></td><td></td>'
                })

                $newTR.find('td:eq(0)').append($itsDel);
                $newTR.find('td:eq(1)').append($inpIname);
                $newTR.find('td:eq(2)').append($inpIdose);
                $newTR.find('td:eq(3)').append($inpIamount);


                $table.find('.butt1').closest('tr').before($newTR);
                $table.find('td').addClass('hideborder');

            }

            function newRegItem($table, objDat){
                var $inpIname = $('<div>',{
                    class: 'not_field setitem_name',
                    style: 'width:150px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.name,
                    placeholder:'item name'
                })

                /*$inpIname.autocomplete({
                  source: "accompItemName.php",
                  minLength: 1,
                  select: function( event, ui ) {

                      //console.dir(ui.item)
                      var rtnItem = ui.item;

                      var prodName = rtnItem.label;
                      var prodBplus = rtnItem.bplus_id;
                      var prodDose = rtnItem.dose;
                      var prodCost = rtnItem.cost;

                      var $tRow = $(this).closest('tr');
                      $tRow.attr('bplus_id',prodBplus);
                      $tRow.attr('cost',prodCost);
                      $tRow.find('input').attr('bplus_id',prodBplus);
                      $tRow.find('input.setitem_dose').val(prodDose);

                      updateTotalCost();
                  }
                })*/

                var $inpIdose = $('<div>',{
                    class: 'not_field setitem_dose',
                    style: 'width:150px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.dose,
                    placeholder:'dose in set'
                })

                var $inpIamount = $('<div>',{
                    class: 'not_field setitem_amount',
                    style: 'width:50px;',
                    bplus_id: objDat.bplus_id,
                    text: objDat.num,
                    placeholder:'amount'
                })
                $inpIamount.keyup(function(){
                    updateTotalCost()
                })

                var $itsDel = $('<img>',{
                    class:"btn-default", 
                    width:"15",
                    height:"15",
                    style:'position:relative; left:0px;',
                    src:"../ICONS/delete.png"
                });

                $itsDel.click(function(){
                    $(this).closest('tr').remove();
                })

                var $newTR = $('<tr>',{
                   class: 'item_in_bund',
                   bplus_id: objDat.bplus_id,
                   cost: objDat.cost,
                   html: '<td></td><td style="min-width:200px"></td><td></td><td></td>'
                })

                var interName = '';
                if(objDat.enname !== null){
                    if(objDat.name != objDat.enname){
                        interName = ' (' + objDat.enname + ')';
                    }
                }
                
                $newTR.find('td:eq(0)').append($itsDel);
                $newTR.find('td:eq(1)').append($inpIname[0].innerHTML + interName);
                $newTR.find('td:eq(2)').append($inpIdose[0].innerHTML);
                $newTR.find('td:eq(3)').append($inpIamount[0].innerHTML);

                

                $table.find('.butt2').closest('tr').before($newTR);
                $table.find('td').addClass('hideborder');
            }
        
        
        
        return $blankTable;
    },
    
    varPaintJS: function varPaintJS(str){
        
        str = str.replace(/\(\(VAR[0-9]|\)\)|\[ENTER\]/gi, function(matched){
            if(matched == '))'){
              return '';
            }
            else if(matched == '[ENTER]'){
              return '<br>';
            }
            else
            {
              return '<var  class="variable input_box" style="width:50px; background-color: yellow;" contenteditable="true">&nbsp;&nbsp;&nbsp;</var>';
            }
        });
        
        return str;
    },
    
    varQ_JS: function varQ_JS(str){
        
        str = str.replace(/\(\(VAR[0-9]|\)\)|\[ENTER\]/gi, function(matched){
            if(matched == '))'){
              return '';
            }
            else if(matched == '[ENTER]'){
              return '<br>';
            }
            else
            {
              return '<font color="red">?</font>';
            }
        });
        
        return str;
    }
}

//####################################################################################
//##
//##      function for Cashier
//##
//##
//####################################################################################
var funcCashier = {
	initTabs: function initTabs(){
		$('#FuncCanvas mainUI').css('height',100);
		//var $tabsBill = $( "#tabsBill" ).tabs().css('width', 330).css('font-size', 12).removeClass('ui-widget-content');
        var $tabsBill = $( "#tabsBill" ).css('font-size', '0.8em');
        
		$('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ').css('text-align','center').css('height',$('#billFiltrated').parent().height()-50).css('overflow-x','hidden');
        
        var refreshButt = $('#refreshBills').click(function(){
            refreshLeftColumn();
            console.log('casher refreshed');
        }).css('style','width:100%;'); //.addClass('btn btn-primary');
        
        
        
        $('#searchPt').addClass('hidden');
        $('#widget2').html($("#tabsBill"))
        $('#widget2').prepend(refreshButt);    
        $('#widget2').removeClass('hidden');
        $('#widget2').removeClass('hidden panel panel-info');
        $('#widget2').css('min-height','600px');
        
		
		refreshLeftColumn = function(){
		
			funcCashier.refreshItem({
				tElem: '#tabs-1',
				tField: {
							0: 'hospital_number',
							1: 'order_date_time',
							2: 'slash_number'
						},
				is_checked: 0,
				dateRange: { 
                            startDate: 'all',
                            endDate: null
                        },
                com_pos: 'null'
			});
			
			funcCashier.refreshItem({
				tElem: '#tabs-2',
				tField: {
							0: 'hospital_number',
							1: 'order_date_time',
							2: 'process_date_time',
							3: 'slash_number'
						},
				is_checked: 1,
				dateRange: { 
                            startDate: 'today',
                            endDate: null
                        },
                com_pos: 'null'
			});
            
            funcCashier.refreshItem({
				tElem: '#tabs-3',
				tField: {
							0: 'hospital_number',
							1: 'order_date_time',
							2: 'process_date_time',
							3: 'slash_number'
						},
				is_checked: 2,
				dateRange: { 
                            startDate: 'today',
                            endDate: null
                        },
                com_pos: 'null'
			});
            
            console.log('3 TABS REFRESH SUCCEEDED');
            
            $('.nav-tabs li').on('click',function(){
                var newTab = $(this).attr('index');

                $('.nav-tabs li').removeClass('active');
                $(this).addClass('active');
                $('.tab-pane').removeClass('in active');
                $($(this).find('a').attr('href')).addClass('in active');
            })

            $('.nav-pills li').on('click',function(){
                $('.nav-pills li').removeClass('active');
                $(this).addClass('active');

                if($(this).find('a').attr('com_pos') != 'n/a'){
                    funcCashierReport.openWindowWithPostRequest($(this).find('a').attr('com_pos'), $('#reportPicker').val())
                }
            })
		}
		
		refreshLeftColumn();
        
        $('#previewBill').click(function(){
            var targetPayer = $('#billFiltrated').attr('payer');
            console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');
            
            if(typeof(targetPayer) != 'undefined'){
                funcCashier.openWindowWithPostRequest({ paidBy: targetPayer, cl_cash_tx: 'preview bills' },'preview');
            }
        });
	},
	
	initFilter: function initFilter(){
		var tElem = $('#billsFilterList');
		var fDate = $('#FuncCanvas #filterDate');
        
        
		
		var stPick = $('#startPicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			showWeek: true,
			yearRange: 'c-50:c+50',
			defaultDate: '-1m',
			selectDefaultDate: true,
			
			dayNamesMin: ['อา','จ','อ','พ','พฤ','ศ','ส'], 
			
			monthNamesShort:['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน',
							 'กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'], 
									 
			changeMonth: true,  
			changeYear: true ,  

			onChangeMonthYear: function(){  
				setTimeout(function(){  
					//var rtn = Th_En_Y();  
					//console.log('CE = ' + rtn.CE + ' Vs BE = ' + rtn.BE);
				},50);        
			},  
			onSelect: function(dateText, inst){   
				dateBefore=$(this).val();  
				var arrayDate=dateText.split("-");  
				//arrayDate[2]=parseInt(arrayDate[2]) + parseInt($('#yearType').val());  
				var dateStr = arrayDate[2]+"-"+arrayDate[1]+"-"+arrayDate[0];
				$(this).hide();	   
				$('#startDate span').text(dateStr);
                
                var opVar = { dateRange: {} }; 
                var tField = {
							0: 'hospital_number',
							1: 'order_date_time',
							2: 'slash_number'
						}
            
                //opVar.tElem = '#tabs-' + ($("#tabsBill").tabs('option', 'active')+1);
                opVar.tElem = '#tabs-1';
			    opVar.tField = tField;
			    opVar.is_checked = 0;
			    opVar.dateRange.startDate = $(this).val();
			    opVar.dateRange.endDate = $('#endPicker').val();
                
				funcCashier.refreshItem(opVar);
			}  
		});
		
		var ndPick = $('#endPicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			showWeek: true,
			yearRange: 'c-50:c+50',
			//defaultDate: '-1m',
			selectDefaultDate: true,
			
			dayNamesMin: ['อา','จ','อ','พ','พฤ','ศ','ส'], 
			
			monthNamesShort:['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน',
							 'กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'], 
									 
			changeMonth: true,  
			changeYear: true ,  

			onChangeMonthYear: function(){  
				setTimeout(function(){  
					//var rtn = Th_En_Y();  
					//console.log('CE = ' + rtn.CE + ' Vs BE = ' + rtn.BE);
				},50);
				
				        
			},  
			onSelect: function(dateText, inst){   
				dateBefore=$(this).val();  
				var arrayDate=dateText.split("-");  
				//arrayDate[2]=parseInt(arrayDate[2]) + parseInt($('#yearType').val());  
				var dateStr = arrayDate[2]+"-"+arrayDate[1]+"-"+arrayDate[0];
				$(this).hide();	   
				$('#endDate span').text(dateStr);
				
                var opVar = { dateRange: {} }; 
                var tField = {
							0: 'hospital_number',
							1: 'order_date_time',
							2: 'slash_number'
						}
            
                //opVar.tElem = '#tabs-' + ($("#tabsBill").tabs('option', 'active')+1);
                opVar.tElem = '#tabs-1';
			    opVar.tField = tField;
			    opVar.is_checked = 0;
			    opVar.dateRange.startDate = $('#startPicker').val();
			    opVar.dateRange.endDate = $(this).val();
                
				funcCashier.refreshItem(opVar);
			}  
		});
		
		tElem.html('');
		fDate.appendTo(tElem);

		var $stButt = $('#startDate').text(now({
				d: 0,
				m: -1,
				y: 0
			})).bind('mouseup',function(){
			stPick.show();
		});
		stPick.appendTo($stButt);
		stPick.hide();
		
		var $ndButt = $('#endDate').text(now({
				d: 0,
				m: 0,
				y: 0
			})).bind('mouseup',function(){
			ndPick.show();
		});
		ndPick.appendTo($ndButt);
		ndPick.hide();
		
	},
	
	refreshItem: function refreshItem(opVar){
        console.log('refreshItem with opVar --v');
        console.dir(opVar);
		
        $(opVar.tElem).attr('style','max-height: ' + (screen.height-300) + 'px; min-height: ' + (screen.height-300) + 'px');
        
		
		var tElem,tField,is_checked, startDate, endDate, com_pos;
		
		
		if(typeof(opVar) == 'undefined'){
			tElem = '#billFiltrated';
			tField = {
						0: '*'
				     }
			is_checked = 0;
			startDate = $('#startPicker').val();
			endDate = $('#endPicker').val();
            com_pos = 'null';
		}
		else
		{
			tElem = opVar.tElem;
			tField = opVar.tField;
			is_checked = opVar.is_checked;
			startDate = opVar.dateRange.startDate;
			endDate = opVar.dateRange.endDate;
            com_pos = opVar.com_pos;
            
            if(is_checked == 2){ console.log(opVar.tElem + ' => show checked bill--v');}
            
		}
		
		var dataFilter = {
            tElem: tElem,
			startDate: startDate,
			endDate: endDate,
			is_checked: is_checked,
			arrFields: tField,
            com_pos: $(tElem).attr('com_pos')
		}
        
        console.log('dataFilter--v');
        console.dir(dataFilter);
        console.log('-------------')
		
        //var promise = jQuery.Deferred();
            
        //promise.then(function(){
            //console.log('first promise');
		
            $.post('./include/refreshCashItem.php', dataFilter ,function(data){
                var $showArea; // = $('#tabs-1');


                if(isJson(data)){
                    var Jsonp = $.parseJSON(data);
                    

                    $showArea = $(Jsonp.tab.name + ' .nav-pills');

                    if(Jsonp == 'No match record.'){ 
                        
                        return; 
                    
                    };


                    $showArea.html('').css('text-align','left');

                    var $rangeDate = $('<div>',{
                                    text: 'query range'
                                })



                    $.each(Jsonp, function(index, value){
                        //var_dump(value, null, $showArea);
                        var numHN;
                        var billName;
                        
                        console.dir(value);

                        if(value.fullname != 'no show'){
                            console.log(value.ODT.substr(0,4));
                            if(value.hospital_number == value.paid_by_whom || value.paid_by_whom == 'same hn'){
                                if($.isNumeric(value.hospital_number)){
                                    numHN = value.hospital_number;
                                    billName = value.fullname;
                                }
                                else{
                                    numHN = 'unknownHN';
                                    billName = value.hospital_number;
                                }

                                
                                var $ptBill = $('<div>',{
                                    hn: value.hospital_number,
                                    text: billName,
                                    paid_by_whom: value.paid_by_whom,
                                    width: '90%',
                                    class: numHN
                                })


                                if($.isNumeric(numHN)){
                                    //console.log('HN ' + value.hospital_number + ' is numeric');

                                    if(!$showArea.find('.' + numHN).length){
                                        $showArea.append($ptBill);
                                        //$ptBill.button();
                                    }
                                }
                                else{
                                    $showArea.append($ptBill);
                                    //$ptBill.button();
                                }
                                $ptBill.addClass('billButt');
                                
                                if(dataFilter.com_pos != 'null'){
                                    //$ptBill.addClass('secondary');
                                    $ptBill.addClass('btn btn-warning');
                                }
                                else
                                {
                                    //$ptBill.addClass('primary');
                                    $ptBill.addClass('btn btn-default');
                                }
                                $ptBill.off('click').on('click',function(e){

                                    var isShift = listenForShiftKey(e);
                                    
                                    //alert($(this).closest('.tab_self').attr('com_pos') + ' isShift=' + isShift);
                                    console.log('ptBill ' + $(this).text() + ' butt click shift(' + isShift + ')');
                                    funcCashier.showHNbill($(this), isShift);

                                    //console.log('isShift = ' + isShift);
                                });
                                
                                
                                //$('#billFiltrated').html('เลือกบิลจากแถบซ้ายมือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ');
                                
                            }; //End show only paid_by_whom in condition
                        }
                        else
                        {


                            if(value.date == 'all'){
                                $rangeDate.append('<br><date>ALL</date>');
                            }
                            else{
                                //console.log(value.date + '-> ' + typeof(value.date));

                                if(typeof(value.date) != 'undefined'){
                                    $rangeDate.append('<br><date>' + value.date + '</date>');
                                }
                            }

                        }
                    });

                    $showArea.append('<br><br><hr>');
                    $showArea.append($rangeDate);
                    
                    
                    
                }
                else
                {
                    var $showArea = $(tElem + ' .nav-pills');
                    $showArea.html('No Bill in this condition').css('text-align','left');	
                }

                /*var activeTab = $('#tabsBill').tabs('option','active');
                $('#tabsBill').tabs('option','active',0);
                $('#tabsBill').tabs('option','active',1);
                $('#tabsBill').tabs('option','active',2);
                $('#tabsBill').tabs('option','active',activeTab);*/
                
                //$('#tabsBill').tabs('load', activeTab);
			    console.log('refreshCashItem with tab swap refreshed');

            });
        //}).then(function(){
            //var activeTab = $('#tabsBill').tabs('option','active');
            //$('#tabsBill').tabs('load', activeTab);
			//console.log('refreshCashItem refreshed');
        //});
	},
    
    showHNbill: function showHNbill($Btn, isShift, com_pos){
        var tElem,tField,is_checked, startDate, endDate, check_type;
		
        var payer;
        var opVar;
        var container;
        var HN;
        var arrfHN = [];
        
        if(typeof($Btn) == 'string'){
            if($Btn == 'refresh'){
                sDate = $Btn;
                
                if($('#billFiltrated bill owner').length > 0){
                    $('#billFiltrated owner').each(function(){
                        //console.log('show batch of ' + $(this).attr('id'));
                        console.log('isShift t_cashier = ' + $(this).attr('t_cashier'));
                        arrfHN.push({ hn:$(this).attr('id'), com_pos:$(this).attr('t_cashier') });
                    });
                }
                else{
                    //if(typeof(compos) == 'undefined'){ 
                        var activeTab = $('.tab_self[aria-hidden="false"]');
                        com_pos = activeTab.attr('com_pos'); 
                    //}
                    console.log('no prior selection set com_pos of active tab = ' + com_pos);
                    arrfHN.push({ hn:HN, com_pos:com_pos });
                }
                
            }
            else
            {
                console.log('unknown command');
                return;    
            }
        }
        else
        {
            //alert($Btn.text());
            com_pos = $Btn.closest('.tab_self').attr('com_pos');
            check_type = $Btn.closest('.tab_self').attr('check_type');
        }
        
        console.log('com_pos = ' + com_pos + ' check_type = ' + check_type);
        
        console.log('initially there are ' + $('#billFiltrated bill owner').length + ' owner(S)');
        
        if(isShift) //user use shift to combine Bills
        {
            if($('#billFiltrated bill owner').length > 0){
                if(check_type == 1){
                    var $showArea = $('#billFiltrated');
                    $showArea.html('').css('text-align','left');
                    alert('CHECK BILL นี้ไปแล้ว ไม่สามารถรวมบิลได้');
                    return;
                }

                if(typeof($Btn) != 'string'){
                    HN = $Btn.attr('hn');

                    //console.log($Btn.attr('hn') + ' Vs ' + HN);

                    container = $Btn.closest('.ui-tabs-panel');

                    if(container.find('date').length){
                    var sDate = container.find('date:eq(0)').text();
                    }

                    var ownerHN;
                    if($.isNumeric(HN)){
                       ownerHN = HN;
                    }
                    else
                    {
                       ownerHN = 'unknownHN';    
                    }

                    var $nameBatch = $('<owner>',{
                        class: ownerHN + ' ' + com_pos + ' owner',
                        t_cashier: com_pos,
                        id: ownerHN,
                        style: "font-size:0.8em; padding:5px; display:inline; white-space:nowrap;",
                        html: $Btn.find('.ui-button-text').text() + '&nbsp&nbsp;',
                    });

                    var $delImg = $('<img>',{
                        style: "position:relative; top:3px;",
                        src: "/ICONS/delete.png"
                    });

                    $delImg.click(function(){
                        $(this).closest('owner').remove();
                        funcCashier.showHNbill('refresh', true, $(this).closest('.tab_self').attr('com_pos'));
                    })

                    $nameBatch.append($delImg);

                    $('#billFiltrated bill').append($nameBatch);
                }

                //console.log('there are ' + $('.owner').length + ' owner(s)');

                $('#billFiltrated owner').each(function(){
                    //console.log('show batch of ' + $(this).attr('id'));
                    console.log($(this).attr('id') + ' isShift t_cashier = ' + $(this).attr('t_cashier'));
                    arrfHN.push({ hn:$(this).attr('id'), com_pos:$(this).attr('t_cashier') });
                });

                console.log('shift gen arrfHN --v');
                console.dir(arrfHN);
            }
            else
            {
                HN = $Btn.attr('hn');
            
                container = $Btn.closest('.ui-tabs-panel');

                if(container.find('date').length){
                var sDate = container.find('date:eq(0)').text();
                }


                arrfHN.push({ hn:HN, com_pos:com_pos });
                payer = HN;
            }
            //console.log('shift add combine bill');
            
        }
        else
        {
            
            HN = $Btn.attr('hn');
            
            container = $Btn.closest('.ui-tabs-panel');
            
            if(container.find('date').length){
            var sDate = container.find('date:eq(0)').text();
            }
            
            
            arrfHN.push({ hn:HN, com_pos:com_pos });
            payer = HN;
        }
        
        
		
		if(sDate == 'ALL'){
			tElem = '#billFiltrated';
			tField = {
						0: '*'
				     }
			is_checked = check_type;
			startDate = 'all';
			endDate = 'all';
		}
        else if(sDate == 'refresh')
		{
			tElem = '#billFiltrated';
			tField = {
						0: '*'
				     }
			is_checked = check_type;
			startDate = $('#startPicker').val();
			endDate = $('#endPicker').val();
            
            
		}
		else
		{
			tElem = '#billFiltrated';
			tField = {
						0: '*'
				     }
			is_checked = check_type;
			startDate = $('#startPicker').val();
			endDate = $('#endPicker').val();
            
		}
		
		var dataFilter = {
            tElem: tElem,
			startDate: startDate,
			endDate: endDate,
			is_checked: is_checked,
			arrFields: tField,
            arrfHN: arrfHN,
            com_pos: com_pos
		}
		
        console.log('send refreshHNbills data --v');
        console.log(dataFilter);
		
		$.post('./include/refreshHNbills.php', dataFilter ,function(data){
			var $showArea = $('#billFiltrated');
            $showArea.attr('style','padding:25px;');
            $showArea.attr('payer',payer);
            var as_cashier;
            
            

			if(isJson(data)){
                
                
				var Jsonp = $.parseJSON(data);
                $showArea = $(Jsonp.tab.name);
                as_cashier = Jsonp.tab.as_cashier;
                
                console.log('data return --v');
                console.dir(Jsonp);

                if(Jsonp == 'No match record.'){ return; };
				

                if($('#billFiltrated owner').length == 0 && typeof($Btn) != 'object'){
                    $showArea.html('').css('text-align','left');
                    return;
                }
                $showArea.html('').css('text-align','left');
                
                var $billName = $('<bill>',{
                            text: '',
                            style: 'top: -10px; position:relative; width:800px;'
                        })
                $showArea.append($billName);

                
                
                var $listBill = new Array();
                
                
                //LIST NAME BATCH
                $.each(Jsonp, function(index, value){
                    var ownerHN;
                    
                    if(value.fullname != 'no show'){
                        if($.isNumeric(value.hospital_number)){
                           ownerHN = value.hospital_number;
                        }
                        else
                        {
                           ownerHN = 'unknownHN';    
                        }


                        if($("[id^='lb" + ownerHN + "-" + as_cashier + "']").length == 0){
                            var mathTrunc = Math.trunc(index / 10)*10;
                            
                            $listBill = $('<table>',{
                                  id: 'lb' + ownerHN + "-" + as_cashier + '-' + mathTrunc,
                                  class: 'listBill',
                                  height: 350,
                                  style: 'position:relative; overflow:hidden; display: block; page-break-inside: avoid; font-size:0.5em; border:1px solid black;'
                                });
                            $listBill.append('<tr class="tabHead"><th colspan="100%">รายการของ ' + value.fullname + ':HN' + ownerHN + '</th></tr>');
                            $showArea.append($listBill);

                        }
                        else
                        {

                            if((index % 10) === 0){

                                $listBill = $('<table>',{
                                      id: 'lb' + ownerHN + "-" + as_cashier + '-' + index,
                                      class: 'listBill',
                                      height: 350,
                                      style: 'position:relative; overflow:hidden; display: block; page-break-inside: avoid; font-size:0.5em; border:1px solid black;'
                                    });
                                $listBill.append('<tr class="tabHead"><th colspan="100%">รายการของ ' + value.fullname + ':HN' + ownerHN + '</th></tr>');
                                $showArea.append($listBill);
                            }
                        }
                        
                        var order_to_com = value.order_to_com;
                        
                        /*var $existOwner = $showArea.find('owner.' + ownerHN + '.' + as_cashier);
                        var $matchOwner = $existOwner.hasClass(as_cashier);*/
                        var $existOwner = $showArea.find('owner.' + ownerHN + '.' + order_to_com);
                        var $matchOwner = $existOwner.hasClass(order_to_com);
                        
                        //console.log('selector match (owner.' + ownerHN + '.' + order_to_com + ') owner');
                        //console.log(value.product_name + '->there are ' + $existOwner.length + ' owner');
                            
                        if($existOwner.length == 0){
                            
                            var $nameBatch = $('<owner>',{
                                class: ownerHN + ' ' + order_to_com + ' owner',
                                t_cashier: com_pos,
                                id: ownerHN,
                                style: "font-size:0.8em; padding:5px; display:inline; white-space:nowrap;",
                                html: value.fullname + '&nbsp&nbsp;',
                            });

                            var $delImg = $('<img>',{
                                style: "position:relative; top:3px;",
                                src: "/ICONS/delete.png"
                            });

                            $delImg.click(function(){
                                $(this).closest('owner').remove();
                                funcCashier.showHNbill('refresh', true, $(this).closest('.tab_self').attr('com_pos'));
                            })

                            $nameBatch.append($delImg);

                            $showArea.find('bill').append($nameBatch);
                        }; //End of find('owner.ownerHn)

                    }
                    
                });
                
                var fullTotal = 0;
                var dcTotal = 0;
                var $sumDiv = $('<div>', {
                    id: 'sumDiv',
                    class: 'brown_round_rect',
                    text: '',
                    style: 'top:0px; position:relative; display: block; font-size:0.8em;',
                    align: 'right'
                });
                
				
				$.each(Jsonp, function(index, value){

                    var numHN;
                    var billName;
                    
                    console.log('refreshHNbill rtn---v');
                    console.dir(value);
                    
                    if(value.fullname != 'no show'){
                        if($.isNumeric(value.hospital_number)){
                            numHN = value.hospital_number;
                            billName = value.fullname;
                        }
                        else{
                            numHN = 'unknownHN';
                            billName = value.hospital_number;
                        }
                        
                        var pColor;
                        if(value.full_price != value.discounted_price){
                            pColor = ' style="color:blue;"'
                        }
                        else{
                            pColor = '';
                        }
                        
                        var isCheck;
                        
                    
                        
                        if(value.is_confirm == '1'){
                           isCheck = ' checked';
                        }
                        else
                        {
                            isCheck = '';
                        }
                    
                    
                        var item = '<td>' + (parseInt(index)+1) +'</td><td><input type="checkbox" class="confirm" value="check"' + isCheck + '></td><td width="300px">' + value.product_name + '<br><font style="font-size:0.8em;" color="gray">' + numHN + '/' + value.ODT + '/' + value.slash_number + '</font></td><td>x <amount>' + value.amount + '</amount></td><td  width="80px">' + value.unit + '</td><td style="width:200px; overflow:hidden; max-width:200px;">' + value.dosage + '</td><td class="fullprice hidden">' + value.full_price + '</td><td' + pColor + ' class="dprice" width="100px">' + value.discounted_price + '</td><td>' + $('#selectCurrency option:selected').attr('show') + '</td><td width="100px" class="sumItem">' + sumItem(value.discounted_price, value.amount) + '</td>';
                        
                        if(isCheck){

                            fullTotal = fullTotal + sumItem(value.full_price, value.amount)

                            
                            dcTotal = dcTotal + sumItem(value.discounted_price, value.amount);
                            
                        }
                    
                        var rowColor;
                        if(index % 2 == 0){
                           rowColor = 'ui-state-highlight';
                           rowColor = '';
                        }
                        else
                        {
                           rowColor = '';
                        }
                        
                        console.log('sale_acc= ' + value.sale_acc);
                        if(value.sale_acc == null){
                            value.sale_acc = 'null';
                        }

                        var $ptBill = $('<tr>',{
                            record_id: value.record_id,
                            old_id: value.oldplat_id,
                            pd_id: value.pd_id,
                            sale_acc: value.sale_acc,
                            html: item,
                            class: 'billTR ' + rowColor,
                            row_index: (parseInt(index)+1)
                        })


                        //$listBill = $('#lb' + numHN + '.0');  
                        var mathTrunc = Math.trunc(index / 10)*10;
                        
                        //console.log('index ' + index + ' -> trunc = ' + mathTrunc);
                        //console.log('add to (#lb' + numHN + '-' + mathTrunc + ') => ' + $('#lb' + numHN + '-' + (mathTrunc)).length);
                        
                        $listBill = $('#lb' + numHN + "-" + as_cashier + '-' + (mathTrunc));
                        $listBill.append($ptBill);
                        
                        
                        
                    
                    }
                    else
                    {
                     
                        if(typeof(value.date) != 'undefined'){
                            
                            $sumDiv.append('<date>' + value.date + '</date><br>');
                            //alert('dateRange' + dateRange);
                        } 
                        else
                        {
                            //$sumDiv.append('query all unpaid bill of all date.');  
                        }
                        
                        
                    
                    }
                    
				});
                
                if(!$sumDiv.find('date').length){
                   $sumDiv.append('query all unpaid bill of all date.<br>');  
                }
                else
                {
                   $sumDiv.prepend('query range<br>');  
                   
                }
                
                dcTotal = roundToTwo(dcTotal);
                
                
                $showArea.append($listBill);
                $showArea.attr('height','300px').attr('overflow-y','hidden');
                
                $sumDiv.append('<br>รวมทุกบิล <fulltotal>' + fullTotal + '</fulltotal> ' + $('#selectCurrency option:selected').attr('show'));
                
                if(dcTotal - fullTotal < 0){
                    //$sumDiv.append('<br><font align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</font>');
                    $sumDiv.append('<br>ส่วนลด <discount>' + roundToTwo((fullTotal - dcTotal)) + '</discount> ' + $('#selectCurrency option:selected').attr('show'));
                }
                
                $sumDiv.append('<br><hr>');
                
                $sumDiv.append('<br>รวมยอดชำระ <dctotal>' + dcTotal + '</dctotal>' + $('#selectCurrency option:selected').attr('show'));
                
                $showArea.append($sumDiv);

				
			}
			else
			{
                //alert(data);
				//$showArea.html(data).css('text-align','left');	
			}
            

            
            $showArea.find('.confirm').each(function(event){
                
                $(this).change(function(){
                    fullTotal = 0;
                    dcTotal = 0;
                    
                    var isCheck = this.checked;
                    
                    if(isCheck){
                        isCheck = 1;
                    }
                    else
                    {
                        isCheck = 0;    
                    }
                    
                    
                    
                    $('.listBill').each(function(){
                        $(this).find('tr').each(function(){
                            var i_fPrice = $(this).find('.fullprice').text();
                            var i_dPrice = $(this).find('.dprice').text();
                            var i_amount = $(this).find('amount').text();
                            var isCheck = $(this).find('.confirm').is(':checked');


                            if(isCheck){
                                fullTotal = fullTotal + sumItem(i_fPrice, i_amount);

                                dcTotal = dcTotal + sumItem(i_dPrice, i_amount);

                            }
                        
                        });
                    });
                    
                    //fullTotal = roundToTwo(fullTotal);
                    //dcTotal = roundToTwo(dcTotal);
                    
                    var discount = (fullTotal - dcTotal);
                    
                    var $sumDiv = $('#sumDiv');
                    $sumDiv.find('fulltotal').text(fullTotal);
                    $sumDiv.find('discount').text(discount);
                    $sumDiv.find('dctotal').text(dcTotal);
                    
                    //console.log('mod confirm record_id = ' + $(this).closest('tr').attr('record_id'));
                    //console.log('mod confirm old_id = ' + $(this).closest('tr').attr('old_id'));
                    
                    
                    var record_id = $(this).closest('tr').attr('record_id');
                    var old_id = $(this).closest('tr').attr('old_id');
                    var pd_id = $(this).closest('tr').attr('pd_id');
                    
                    //console.log('record_id -> isCheck = ' + isCheck);
                    
                    $.get('modDat.php', //[2]start
                      {
                        record_id: record_id,
                        old_id: old_id,
                        pd_id: pd_id,
                        field: 'is_confirm',
                        newVar: isCheck,
                        staff_name: 'staff_fullname'
                      },
                      function rtn(data){
                          //console.dir(data);
                          //alert('data ' + isJson(data));
                        
                          var pData = $.parseJSON(data)
                          
                          //console.dir(pData);
                          
                          /*if (pData[0].hasOwnProperty('alert_th')) {
                            console.log('alert!!!');
                            alertTimerBox(pData[0].alert_th,elem);
                            console.dir(pData[1]);

                            if(elem.hasClass('ui-autocomplete-input')){
                                elem.find('td[id^="iDoseOption"]').html(pData[1]); //elem.val();
                            }
                            else
                            {
                                elem.find('td[id^="iDoseOption"]').text(pData[1]);
                            }
                          }
                          else
                          {
                            console.dir(pData);
                          }*/
                      }); //[2]end
                    
                    $('#payMethod').remove();
                    
                    var $payMethod = new funcCashier.payMethod();
                    
                    $('#insertDetailHere').after($payMethod);
                })
            })
			
            var totalHeight = 0;
            
            $('.listBill').each(function(){
                totalHeight = totalHeight + parseFloat($(this).css('height'));
            });
            
            if(totalHeight< 650){
               totalHeight = 650;
            }
            
            //console.log('totalHeight = ' + totalHeight);
            $('#billArea').css('height', totalHeight);
            
            if(parseFloat($('bill').css('width')) > 800){
                $('bill').append('<br><br>');
                //alert('next bill new line');
            }
            else
            {
               //console.log("$('bill').css('width') = " + $('bill').css('width'));
               //console.log('next bill same line');        
            }
            //console.log("$('bill').css('width') = " + $('bill').css('width'));
            
            
            
            
            var $btnSubmit
            
            console.log('check bill condition ---v');
            console.dir(']' + dataFilter.is_checked + '[');
            
            if(dataFilter.is_checked != '1'){
                $btnSubmit = $('<div>',{
                    class:"btn btn-success",
                    html:"PAY BILL" + '<form id="showMode"></form>',
                    style:"alignment:center; padding:5px; border-radius: 5px; width:100%;"
                }).click(function(){
                    funcCashier.doPaidBill();
                })
                
                
            
                var $payMethod = new funcCashier.payMethod();
                
                $(tElem).append('<div id="insertDetailHere"><br></div>');
                $(tElem).append($payMethod);
                $(tElem).append('<br>');
            }
            else{
                $('#billFiltrated').html('เลือกบิลจากแถบซ้ายมือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ');
                
                $btnSubmit = $('<div>',{
                    class:"btn btn-success",
                    html:"SHOW BILL PAID BY " + $Btn.text() + '<form id="showMode"></form>',
                    paid_by: $Btn.attr('hn'),
                    style:"alignment:center; padding:5px; border-radius: 5px; width:100%"
                }).click(function(){
                    var arrPaidBill = { paidBy: $Btn.attr('hn'), cl_cash_tx: 'current bills' }
                    
                    console.log('paid bill dat --v');
                    console.dir(arrPaidBill);
                    
                    funcCashier.showPaidBill(arrPaidBill);
                })
                
                
            }
            
            /*$btnSubmit.bind('mouseover',function(){
                $(this).addClass('ui-state-hover');
            }).bind('mouseout',function(){
                $(this).removeClass('ui-state-hover');
            });*/
            
            var $optBill1 = $('<input>',{
                class:"show mode",
                type:'radio',
                value:'group',
                name:'mode',
                checked:false
            }).click(function(){
                event.stopPropagation();
            });
            
            var $optBill2 = $('<input>',{
                class:"show mode",
                type:'radio',
                value:'items',
                name:'mode',
                checked:true
            }).click(function(){
                event.stopPropagation();
            });
            
            $btnSubmit.find('form').append($optBill1);
            $btnSubmit.find('form').append(' แสดงสินค้าเป็นหมวด');
            $btnSubmit.find('form').append($optBill2);
            $btnSubmit.find('form').append(' แสดงสินค้าเป็นรายการ');
            
            $(tElem).append($btnSubmit);
            
            var $formVat = $('<form>',{
                id:'is_vat',
                text: 'ใบกำกับภาษี ไม่ขอ '
            });
            
            var $optVat1 = $('<input>',{
                class:"is_vat",
                type:'radio',
                value:'0',
                name:'vat',
                checked:true
            }).click(function(){
                event.stopPropagation();
            });
            
            
            
            $formVat.append($optVat1);
            $formVat.append(' &nbsp;&nbsp;&nbsp;ขอใบกำกับภาษี ')
            
            var $optVat2 = $('<input>',{
                class:"is_vat",
                type:'radio',
                value:'1',
                name:'vat',
                checked:false
            }).click(function(){
                event.stopPropagation();
            });
            
            $formVat.append($optVat2);
            
            $(tElem).append($formVat);
			//console.log('HN bill refreshed');
		});
    }, //End of $.post('refreshHNbills.php'...
    
    payMethod: function(){
        
        
        var payComp = { };
            $('#billFiltrated tr:not(.tabHead)').each(function(){
                var saleAcc = $(this).attr('sale_acc');
                var isCheck = $(this).find('.confirm').is(':checked');
                
                console.log($(this).text());
                
                if(saleAcc == 'null'){
                    saleAcc = 'บจ.โอเรียนทอล เฮลท์ บิส';
                }
                
                if(typeof(payComp[saleAcc]) == 'undefined'){
                    if(isCheck){
                        payComp[saleAcc] = parseFloat($(this).find('.sumItem').text());
                    }
                }
                else
                {
                    if(isCheck){
                        payComp[saleAcc] = payComp[saleAcc] + parseFloat($(this).find('.sumItem').text());
                    }
                }
            });
            console.log('pay comp list --v');
            console.dir(payComp);
            
            var $payMethod = $('<div>', {
                id:'payMethod',
                class:'alert alert-warning',
                style:'padding: 10px; border-radius: 5px;',
                html: 'ระบุวิธีการจ่ายเงิน<hr><br><table id="payDetail"><tr class="tabHead"><th></th><th style="font-style: italic;">&nbsp;&nbsp;จ่ายด้วยเงินสด</th><th></th><th style="font-style: italic;">&nbsp;&nbsp;จ่ายด้วยบัตรเครดิต</th><th></th><th style="font-style: italic;">&nbsp;&nbsp;จ่ายด้วยการโอนผ่าน บช</th><th></th><th>รวมต้องเท่ากับ</th><th id="cl_cash_tx" class="hidden"></th></tr>'
            })
            
            var sumCash = 0;
            
            for (var k in payComp) {
                if (payComp.hasOwnProperty(k)) {
                   
                   var addMethod =  '<tr class="payMethod"><td><acc amount="' + payComp[k] + '">' + k + '</acc></td><td><img src="../ICONS/PayCash.ico"><input type="cash" class="cash pay" value="'+ payComp[k] + '" style="width:110px; text-align: right;"></td><td> + </td><td><img src="../ICONS/PayBankCredit.ico"><input type="card" class="card pay" value="0" style="width:150px; text-align: right;"></td>' + ifaccount + '</td><td></td><td> = <eq style="color:green" grant="true">' + payComp[k] + '</eq></tr>';
                   console.log('addMethod ' + addMethod); 
                   $payMethod.find('table').append(addMethod);
                
                    
                   sumCash = sumCash + payComp[k];
                }
            }
        
            $payMethod.find('table').append('<tr><td></td><td style="border-bottom: 2px double black;"></td></tr>');
            $payMethod.find('table').append('<tr><td>เก็บเงินสดเข้าเก๊ะ</td><td style="border-bottom: 2px double black; text-align: right;" id="sumCash">' + sumCash + '</td></tr>');
        
            $payMethod.append('</table>');
            
        
            $payMethod.find('tr.payMethod').each(function(){
                $(this).find('input.pay').bind('keyup',function(){
                    var sumAcc = 0;
                    
                    var $tr = $(this).closest('tr.payMethod')
                    
                    $tr.find('input.pay').each(function(){
                        sumAcc = sumAcc + parseFloat($(this).val());
                    });
                    
                    if(sumAcc != $tr.find('eq').text()){
                        $tr.find('eq').attr('style','color:red;');
                        $tr.find('eq').attr('grant',false);
                    }
                    else{
                        $tr.find('eq').attr('style','color:green;');
                        $tr.find('eq').attr('grant',true);
                    }
                    
                    var sumCash = 0
                    
                    $('#payDetail tr .cash').each(function(){
                        sumCash = sumCash + parseFloat($(this).val());
                    })
                    
                    $('#sumCash').text(sumCash);
                }) //End keyup
            }) //End tr:not
        
        return $payMethod;
    },
    
    validatePayDetail: function(){
        var payDetail = { }
        
        //console.log('found ' + $('#payDetail tr').length + ' pay method');
        
        $('#payDetail tr').each(function(index, value){
            var $TR = $(this);
            
            if($(this).find('eq').attr('grant') == 'false'){
                console.log('validate fail');
                payDetail = false;
                return false;
            }
            else
            {
                var acc = $(this).find('acc').text();
                
                if(acc == ''){
                    acc = 'บิลเงินสด';
                }
                
                payDetail[acc] = { };
                
                //console.log('acc = ' + acc)
                
                $(this).find('.pay').each(function(){
                   payDetail[acc][$(this).attr("type")] = $(this).val(); 
                   //console.log(acc + '-> pay by ' + $(this).attr("type") + ':' + $(this).val())
                });
                
                console.dir(payDetail[acc]);
            }
            
        })
        
        console.log('validate pay --v');
        console.dir(payDetail);

        
        return payDetail;
    },
	
	updateLastSum: function updateLastSum(){
				   var firstSum = parseFloat($('#firstSum').text());
				   var vat = parseFloat($('#vat').text());
				   var discount = parseFloat($('#discount').text());
				   var lastSum = firstSum + vat - discount;
				   
				   $('#lastSum').text(lastSum);
				   $('#literalSum').text(BAHTTEXT(lastSum));
				   
	}, //End of function updateLastSum();
    
    doPaidBill(){
        
        var payDetail = funcCashier.validatePayDetail();
        
        
        if(!payDetail){
            alert('ผลรวมของแต่ละการจ่ายเงิน ควรเท่ากับจำนวนเงินสีแดงด้านขวา');
            return;
        }
        
        var assoTx = [];
        $('#cl_cash_tx tx').each(function(index, elem){
            assoTx.push($(this).text());
        })
        
        console.log('assoTx --v');
        console.dir(assoTx);
        
        var paidBy = $('#billFiltrated').attr('payer');
        var key = 0;
        var arrRecord = { 
            staff_name:'staff_fullname',
            paid_by: paidBy,
            pay_detail: payDetail,
            cl_cash_tx: assoTx,
            is_vat: $('input[name=vat]:checked').val()
        };
        
        console.dir(arrRecord);
        
        $('table.listBill').each(function(index,tTable){
            
            $(tTable).find("tr:not('.tabHead')").each(function(index, row){
                var record_id = $(this).attr('record_id');
                var old_id = $(this).attr('old_id');
                var pd_id = $(this).attr('pd_id');
                
                if(typeof(old_id) == 'undefined'){ old_id = 'null'; }
                
                var isCheck = $(this).find('.confirm').is(':checked');

                //console.log($(this).text() + ' isCheck = ' + isCheck);
                
                arrRecord[key] = {
                    record_id: record_id,
                    pd_id: pd_id,
                    old_id: old_id
                  }

                key = key + 1;
                
             }); //END of for each index,row
            
            
        }); //End of each index,tTable
        
        //console.log('arrRecord --v');
        //console.dir(arrRecord);

        $.post('./include/paidBill.php', //[2]start
          arrRecord,
          function rtn(data){
              console.dir(data);
              //alert('data ' + isJson(data));

              var pData = $.parseJSON(data)
              var updateIDs = pData.update_id;
              var cl_cash_tx = pData.cl_cash_tx;
            
              $('th#cl_cash_tx').html('');
              $.each(cl_cash_tx,function(index,value){
                $('th#cl_cash_tx').append('<tx>' + value + '</tx>');
              });
            
              $.each(updateIDs,function(index,record_id){
                  
                  var tRow = $("tr[record_id='" + record_id + "']")
                  
                  tRow.removeClass('ui-state-highlight');
                  tRow.attr('style','background-color:green;');
                  tRow.find('.confirm').attr('disabled','disabled');
                  
                  
                  
              });
                        
              refreshLeftColumn();
              funcCashier.openWindowWithPostRequest({ paidBy: paidBy, cl_cash_tx: cl_cash_tx });
            
          }); //End of $.get('multiModdat.php')
    }, //End of doPaidBill
    
    showPaidBill: function showPaidBill(arr_paidby_cash_tx){
        funcCashier.openWindowWithPostRequest(arr_paidby_cash_tx);
    },
    
    openWindowWithPostRequest: function openWindowWithPostRequest(arr_paidby_cash_tx, forceShowMode) {
      var winName='PRINT RECEIPT';
      var winURL='include/showPaidBill.php';
      var windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
        
      var showMode = $('.show.mode:checked').val();
      console.log('show mode = ' + showMode);
        
      if(typeof(forceShowMode) != 'undefined'){
          showMode = forceShowMode;
      }
        
      console.dir(arr_paidby_cash_tx);
      
      var params = { paid_by_whom: arr_paidby_cash_tx['paidBy'], show_mode:showMode };
      
        
      var form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", winURL);
      form.setAttribute("target",winName);  
      for (var i in params) {
        if (params.hasOwnProperty(i)) {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = i;
          input.value = params[i];
          form.appendChild(input);
        }
      }
    
      var cl_cash_tx = arr_paidby_cash_tx['cl_cash_tx'];
        
      console.log('receiv e cl_cash_tx as --v');
      console.dir(cl_cash_tx);
      
      if(typeof(cl_cash_tx) == 'object'){
          for (var i in cl_cash_tx) {
            if (cl_cash_tx.hasOwnProperty(i)) {
              var input = document.createElement('input');
              input.type = 'hidden';
              input.name = 'tx' + i;
              input.value = cl_cash_tx[i];
              form.appendChild(input);
            }
          }
      }
      else if(typeof(cl_cash_tx) == 'string'){
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'tx0';
          input.value = cl_cash_tx;
          form.appendChild(input);
      }
        
      document.body.appendChild(form);                       
      //window.open('', winName,windowoption);
      window.open('', winName);
      form.target = winName;
      form.submit();                 
      document.body.removeChild(form);           
    }
} //End of funcCashier namespace

/*function isJson(str) {
  try {
      JSON.parse(str);
      console.log('isJson true');
  } catch (e) {
      console.log('isJson false');
      return false;
  }
  return true;
}*/

function isJson (something) {
    if (typeof something != 'string')
        something = JSON.stringify(something);

    try {
        JSON.parse(something);
        return true;
    } catch (e) {
        return false;
    }
}

function var_dump(obj, lead, tElem ) {
  var append;

  if(lead === null){
      lead = '';
  }

  if(typeof(tElem) == 'undefined'){
      append = false;
  }
  else
  {
      append = true;
      lead = '';
  }

  var out = '';
  var last_i = 'n/a';

  for (var i in obj) {
      var objManifest;



      if(typeof(obj[i]) != 'object'){
          objectManifest = obj[i]
      }
      else
      {
          objectManifest = '\n';
          objectManifest += var_dump(obj[i], (lead + '>>'));
      }

      out += lead + '<font color="black">' + i + ": </font><font color=\"red\">" + objectManifest + "</font>\n";

  }

  //alert(out);

  // or, if you wanted to avoid alerts...
  if(append){
    var pre = document.createElement('pre');
    pre.innerHTML = out;
    tElem.append(pre);
  }
  else
  {
      return out;
  }
}

function now(param){
  if(typeof(param) == 'undefined'){
      param = false;
  }

  var today = new Date();

  if(param){
      today.setFullYear(today.getFullYear() + param.y);
      today.setMonth(today.getMonth() + param.m);
      today.setDate(today.getDate() + param.d);
  }


  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd<10) {
      dd='0'+dd
  } 

  if(mm<10) {
      mm='0'+mm
  } 

  today = dd+'-'+mm+'-'+yyyy;

  return today;
} //end Func now()


function listenForShiftKey(e){
    var evt = e || window.event;
    if (evt.shiftKey) {
      shiftKeyDown = true;
    } else {
      shiftKeyDown = false;
    }

    return shiftKeyDown;
}

function sumItem(dPrice, amount){
    var fPrice = 0;

    //console.log('** ]' + dPrice.substring(0,5) + '[');

    if(dPrice.substring(0,5) == 'Total'){
        fPrice = parseFloat(dPrice.substring(6));
        //sconsole.log('dPrice = ' + dPrice.substring(6));
        amount = 1;
    }
    else
    {
        //console.log('dPrice convert => ' + dPrice + ' -> ' + parseFloat(dPrice))
        //console.log('amount = ' + amount);
        fPrice = parseFloat(dPrice);
        //console.log('fPrice = ' + fPrice);
    }

    //console.log('dPrice = ' + dPrice.substring(6));

    //console.log('sum * = ' + (fPrice * amount) + '(' + typeof((fPrice * amount)) + ')');
    return roundToTwo((fPrice * amount));
}

function roundToTwo(num) { 
    var rtn = num;
    //console.log(num + 'typeof num = ' + typeof(num));
    var rtn = +(Math.round(num + "e+2")  + "e-2");
    //var rtn = Math.round(num * 100) / 100
    //var rtn = num.toFixed(2);
    if(rtn == 'NaN'){
        //console.log(num);
        console.log('NAN alert');

    }
    return Math.round(rtn);
}