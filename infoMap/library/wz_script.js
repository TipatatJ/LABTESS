


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
          //$('#checkSession').trigger('checkSession');

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
            $(document).trigger('changePt');
            //$('#checkSession').trigger('checkSession');
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
    
   presetDose: function presetDose(doseOption, newRow, update){
        if(typeof(update) == 'undefined'){
            update = true;
        }
        
        console.log('presetDose(' + doseOption + ')')
        console.log('presetDose with update ' + update)
        
       
        if(!update){
            return;
        }

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
       
                console.log('DOSE HTML >>> ' + doseOption);
       
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
                      array.push('<font color="green">Copy last patient\'s order</font>');

                      if(dose_option.hasOwnProperty('b_name')){
                            
                            var $tDoseOption  = $(newRow).find('.iDose');
                            $tDoseOption.removeClass('doseBox');
                            $tDoseOption.html('');
                            var $blankTable = wzCartFunc.jsonDose($tDoseOption, doseOption);
                            
                          
                           
                            var tProdID = $(newRow).closest('tr').find('.iProdID').text();
                            var tSessID = $(newRow).closest('tr').find('.iSessID').text();

                            updateMiniCartDose(tProdID, tSessID, $blankTable.html());
                          
                            //alert($('input[type="date"]').length)

                            //USE miniCartAjax $(document).on('change' DECLARATION 
                            /*$(document).on('change','input.calendar', function(){
                                //alert(this.value);         //Date in full format alert(new Date(this.value));
                                var inputDate = new Date(this.value).toISOString().slice(0, 10)
                                
                                $(this).attr('value',inputDate)
                                $(this).attr('style','border: none !important; box-shadow: none !important; outline: none !important; font-size:1.2em;');
                                
                                //$(this).closest('i').prepend('<appoint style="color:blue;">' + inputDate + '</appoint>');
                                
                                
                                var tProdID = $(this).closest('.cartList').find('.iProdID').text();
                                var tSessID = $(this).closest('.cartList').find('.iSessID').text();
                                
                                //alert($(this).closest('.cartList').find('.iProdID').length + ':' + tProdID + ':' + tSessID)

                                updateMiniCartDose(tProdID, tSessID, $(this).closest('table').html());
                                //alert('update minicart dose')
                                
                                //$(this).remove();
                            });*/
                          
                            //$(this).attr('disabled', true)
                                
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
                                      
                                     var $THIS = $(this);
                                      
                                     //$('nth').each(function(){
                                    if($(this).html() == '<font color="green">Copy last patient\'s order</font>'){
                                        $.post('include/lastNTH.php', {
                                            pd_id: tProdID,
                                            strDose: $(this).html()
                                        }, function(data){
                                            var jSonp = $.parseJSON(data)
                                            
                                            $THIS.html(jSonp[0].nth);
                                            console.dir(jSonp[0].nth)
                                        })
                                    }
                                     //})

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
                    
                    console.log('dose is string ->');
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
            
            var $tablist = $('<tablist class="JSONdose tablist" contenteditable="false" tProdID="0" tSessID="0">');
            $tablist.html('');
        
            //alert($tTD.closest('.cartList').find('.iSessID').text())
        
            var tSessID = $tTD.closest('.cartList').find('.iSessID').text();
            var tProdID = $tTD.closest('.cartList').find('.iProdID').text();
            $tablist.attr('tSessID',tSessID);
            $tablist.attr('tProdID',tProdID);
        
            var $blankTable = $('<table >',{
                tProdID: tProdID,
                tSessID: tSessID
            }).appendTo($tablist);
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
            
            var objDat = { };

            $.each(jSonp.b_compliment,function(bplus_id,objItem){
                    
                
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
                
                //var objDat = { };

                objDat['bplus_id'] = objItem['bplus_id'];
                objDat['name'] = objItem['name'];
                objDat['enname'] = objItem['enname'];
                objDat['dose'] = objItem['dose'];
                objDat['num'] = objItem['used'];
                objDat['cost'] = objItem['cost'];
                objDat['method'] = objItem['method'];
                objDat['opt_link'] = objItem['opt_link'];

                //console.clear()
                //console.dir(objItem);
                console.log('-----')
                

                
                if(objDat['bplus_id'] != 'remain' && objDat['bplus_id'] != 'n/a'){
                    console.dir(objItem);
                    //alert('bplus id = ' + objDat['bplus_id']);
                    newRegItem($blankTable, objDat);
                    $tablist.append($blankTable, objDat);
                }
            });
            
            //console.dir(objDat);
            
            
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
                    cursel: prepMeth[1],
                    opt_link: 'none'
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
                        html: '<td></td><td class="input_box"></td><td class="mini_box hideborder ing_dose" contenteditable="true">?</td><td class="ing_unit"></td><td class="ing_method hideborder"></td>',
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
        
            if(setType == 'Mistletoe'){
                
                var prepMeth = {
                    1: '',
                    
                    2: 'Helixor A',
                    3: 'Helixor M',
                    4: 'Helixor P',
                    5: '----',
                    6: '',
                    7: 'Iscador Qu',
                    8: 'Iscador M',
                    9: 'Iscador P',
                    10: '',
                    11: '-- Strong effect --',
                    12: 'Abnobar',
                    13: 'Abnobar fraxini',
                    14: '',
                    15: '-- Weak patient--',
                    16: 'Iscador P',
                    17: 'Helixor A',
                    18: '',
                    19: '-- Male --',
                    20: 'Iscador Qu c Ag',
                    21: 'Iscador Qu c Cu',
                    22: 'Iscador Qu c Hg',
                    23: 'Iscador Qu c Au',
                    24: 'Iscador Qu c Fe',
                    25: 'Iscador Qu c Sn',
                    26: 'Iscador Qu c Pb',
                    27: '',
                    28: '-- Fertility Female --',
                    29: 'Iscador M c Ag',
                    30: 'Iscador M c Cu',
                    31: 'Iscador M c Hg',
                    32: 'Iscador M c Au',
                    33: 'Iscador M c Fe',
                    34: 'Iscador M c Sn',
                    35: 'Iscador M c Pb',
                    36: '',
                    37: '-- Menopause --',
                    38: 'Iscador P c Ag',
                    39: 'Iscador P c Cu',
                    40: 'Iscador P c Hg',
                    41: 'Iscador P c Au',
                    42: 'Iscador P c Fe',
                    43: 'Iscador P c Sn',
                    44: 'Iscador P c Pb',
                }
                
                var $selPrepMethod = $('<select>',{
                    class: 'prep_method',
                    cursel: prepMeth[1],
                    opt_link: objDat['opt_link']
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
                
                /*var optLink = $selPrepMethod.closest(".item_in_bund").attr('opt_link');
                
                $selPrepMethod.bind('change', function(){
                    if(optLink != '' && optLink != 'none'){
                        alert($('.item_in_bund[opt_link=' + optLink + ']').length + ' to update');
                    }
                });*/
            
                
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
                          
                          //if(prodName == '<hr>'){
                            //var prodMixName = '<hr>';
                          //}
                          //else{
                            var prodMixName = rtnItem.mix_name;
                          //}
                          
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
                    
                    
                    console.log($inpIname.val());
                    
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
                    
                    if(trBplusId == '?'){
                       $(this).html('<td colspan="99"><hr></td>')
                    }
                    else if(setType == 'Mistletoe'){
                        $(this).find('td:eq(3)').html('mg <br><b>SC inj</b>').addClass('ing_unit');
                    }
                    else if(setType == 'TCMdrug'){
                        $(this).find('td:eq(3)').text('g').addClass('ing_unit');
                    }
                    else{
                        $(this).find('td:eq(3)').text('g').addClass('ing_unit');
                    }
                    
                    
                    
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
                        
                        var optLink = $(this).closest(".item_in_bund").attr('opt_link');
                        var optVal = $(this).val()
                        
                        if(optLink != '' && optLink != 'none'){
                            console.log($('.item_in_bund[opt_link=' + optLink + ']').length + ' to update ' + optLink);
                            
                            $('.item_in_bund[opt_link=' + optLink + '] select').each(function(){
                                $(this).val(optVal);
                                $(this).attr('cursel',optVal)
                            })
                        }
                    })
                    
                    if(trBplusId != '?'){
                        $(this).find('td:last').append($newSel).addClass('ing_method');
                    }
                   //$(this).find('td').addClass('hideborder');
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
                
                //console.log(objDat.enname)
                //console.log(objDat.enname.includes('((Calendar))'))
                
                if(objDat.enname.includes('((Calendar))')){
                    var today = new Date().toISOString().slice(0, 10)

                    var d = new Date();
                    var weekday = new Array(7);
                    weekday[0] = "Sun";
                    weekday[1] = "Mon";
                    weekday[2] = "Tue";
                    weekday[3] = "Wed";
                    weekday[4] = "Thu";
                    weekday[5] = "Fri";
                    weekday[6] = "Sat";

                    var n = weekday[d.getDay()];

                    objDat.enname = objDat.enname.replace('((Calendar))', '<dow>' + n + '</dow><input type="date" value="' + today + '" class="calendar" data-date-format="DD MMMM YYYY">')
                }
                
                var $inpIname = $('<div>',{
                    class: 'not_field setitem_name',
                    style: 'width:150px;',
                    bplus_id: objDat.bplus_id,
                    html: objDat.name,
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
                   html: '<td></td><td style="min-width:200px"></td><td></td><td></td>',
                   opt_link: objDat.opt_link
                })
                
                if(objDat.name == '<hr>'){
                    $newTR.attr('is_hr', true);
                }

                var interName = '';
                if(objDat.enname !== null){
                    if(objDat.name != objDat.enname){
                        interName = '<br><i>(' + objDat.enname + ')</i>';
                    }
                }
                
                $newTR.find('td:eq(0)').append($itsDel);
                $newTR.find('td:eq(1)').append($inpIname[0].innerHTML + interName);
                $newTR.find('td:eq(2)').append($inpIdose[0].innerHTML);
                $newTR.find('td:eq(3)').append($inpIamount[0].innerHTML);

                $newTR.find('.prep_method').prop('opt_link',objDat.opt_link);
                console.log('opt_link = ' + objDat.opt_link)

                /* $newTR.find('.calendar').each(function(){

                    $(this).change(function(){
                        $(this).closest('td').find('dow').text('dow');
                    })

                })  */               

                $table.find('.butt2').closest('tr').before($newTR);
                $table.find('td').addClass('hideborder');
            }
        
        
        
        return $blankTable;
    
    
    
        
    
    
    
    },
    
    varPaintJS: function varPaintJS(str){
        
        str = str.replace(/\(\(VAR[0-9]|\)\)|\[ENTER\]|\(\(NTH[0-9]|\)\)/gi, function(matched){
            if(matched == '))'){
              return '';
            }
            else if(matched == '[ENTER]'){
              return '<br>';
            }
            else if(matched.substr(0,5) == '((VAR')
            {
              return '<var  class="variable var" style="width:100px; background-color: yellow;" contenteditable="inherited">&nbsp;&nbsp;&nbsp;</var>';
            }
            else if(matched.substr(0,5) == '((NTH')
            {
              return '<keeptag><nth  class="variable input_box" style="width:100px; background-color: LightSeaGreen; color:white;" contenteditable="inherited" placeholder="count">&nbsp;ครั้งที่ #</nth>';
            }
            else
            {
              return '<var  class="variable" style="width:50px; background-color: yellow;" contenteditable="true" >&nbsp;&nbsp;&nbsp;</var>';
            }
        });
        
        return str;
    },
    
    varQ_JS: function varQ_JS(str){
        
        str = str.replace(/\(\(VAR[0-9]|\)\)|\[ENTER\]|\(\(NTH[0-9]|\)\)/gi, function(matched){
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

function copyToClipboard(element, is_text) {
  var $temp = $("<input>");
  $("body").append($temp);
  if(is_text){
    $temp.val($(element).text()).select();  
  }
  else
  {
    $temp.val($(element)[0].outerHTML).select();
  }
  document.execCommand("copy");
  $temp.remove();
}

//####################################################################################
//##
//##      function for HRsum
//##
//##
//####################################################################################
var funcHRsum = {
	initTabs: function initTabs(){
		$('#FuncCanvas mainUI').css('height',100);
		//var $tabsBill = $( "#tabsBill" ).tabs().css('width', 330).css('font-size', 12).removeClass('ui-widget-content');
        var $tabsBill = $( "#tabsBill" ).css('font-size', '0.8em');
        
		//$('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ').css('text-align','center').css('height',$('#billFiltrated').parent().height()-50).css('overflow-x','hidden');
        
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
        

		//refreshLeftColumn();
        
        $('#previewBill').click(function(){
            //var targetPayer = $('#billFiltrated').attr('payer');
            
            var arrOwner = new Array();
            
            
            
            $('.owner').each(function(index, owner){
                arrOwner.push ({ com_pos: $(this).attr('t_cashier'), hn: $(this).attr('id') })
            })
            
            console.dir(arrOwner);
            //console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');
            
            //if(typeof(targetPayer) != 'undefined'){
            if(arrOwner.length > 0){    
                funcCashier.openWindowWithPostRequest({ paidBy: arrOwner, cl_cash_tx: 'preview bills' },'preview');
            }
        });
	},
	
	initFilter: function initFilter(){
		//var tElem = $('#billsFilterList');
		//var fDate = $('#FuncCanvas #filterDate');
        
        $('#selOrg').change(function(){
            $('#orgData').load('include/orgChart.php', {
                orgName: $('#selOrg option:selected').val()
            }, function(){
                $('#selBrLot').off().on('change', function(){

                    $.post('include/getOrgJson.php', { orgName: $('#selOrg option:selected').val(), user_br: $('#selBrLot option:selected').val(), user_lot: $('#selBrLot option:selected').attr('user_lot'), Lang: 'Th' }, function(data){
                        var jPdata = $.parseJSON(data);
                        
                        /*$.each(jPdata, function(index, elem){
                            try{
                                elem.curchar = $.parseJSON(elem.curchar);
                            }
                            catch(e){
                                //elem.curchar = 'unextractable char';
                            }
                        });*/
                        
                        //console.dir(jPdata);
                        
                        $('#orgChartArea').html('<br><br><br><br><br>');
                        
                        $tableGender = $('<table>', {
                            id: 'genderTable',
                            html: '<tr><th></th><th></th><th></th></tr>'
                        })
                        
                        $tr1 = $('<tr>', {
                            html: '<td>Person</td><td>Male</td><td>Female</td>'
                        })
                        $tableGender.append($tr1);
                        $tr2 = $('<tr>', {
                            html: '<td></td><td>' + jPdata.genderPattern.M + '</td><td>' + jPdata.genderPattern.F + '</td>'
                        })
                        $tableGender.append($tr2);
                        $('#orgChartArea').append($tableGender);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#genderTable\')">Copy GENDER</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        $tableChar = $('<table>', {
                            id: 'charTable',
                            html: '<tr><th></th><th>Current Character</th><th>Teenage Character</th><th>Child Character</th></tr>'
                        })
                        
                        $.each(jPdata.charPattern, function(index, elem){
                            $tr1 = $('<tr>', {
                                html: '<td>' + elem.type + '</td><td class="curChar">' + elem.count + '</td><td>' + jPdata['teenPattern'][index]['count'] + '</td><td>' + jPdata['childPattern'][index]['count'] + '</td>'
                            })
                            $tableChar.append($tr1);
                            $tr2 = $('<tr>', {
                                html: '<td>' + '</td><td class="curChar">' + elem.count + '</td><td>' + jPdata['teenPattern'][index]['count'] + '</td><td>' + jPdata['childPattern'][index]['count'] + '</td>'
                            })
                            $tableChar.append($tr2);
                        })
                        
                        $('#orgChartArea').append($tableChar);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#charTable\')">Copy CHARACTER</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        $tableBMI = $('<table>', {
                            id: 'bmiTable',
                            html: '<tr><th>Low BMI</th><th>Medium BMI</th><th>High BMI</th></tr>'
                        })
                        
                        
                        $tr1 = $('<tr>', {
                            html: '<td>' + jPdata.bmiPattern.Low + '</td><td class="curChar">' + jPdata.bmiPattern.Moderate + '</td><td>' + jPdata.bmiPattern.High + '</td>'
                        })
                        $tableBMI.append($tr1);
                        
                        
                        $('#orgChartArea').append($tableBMI);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#bmiTable\')">Copy BMI</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        $tableWHR = $('<table>', {
                            id: 'whrTable',
                            html: '<tr><th>Gender</th><th>Normal W/H</th><th>Risk W/H</th></tr>'
                        })
                        
                        
                        $tr1 = $('<tr>', {
                            html: '<td>Male</td><td>' + jPdata.whPattern.MaleNormal + '</td><td>' + jPdata.whPattern.MaleMetabolicRisk + '</td>'
                        })
                        $tableWHR.append($tr1);
                        $tr2 = $('<tr>', {
                            html: '<td>Female</td><td>' + jPdata.whPattern.FemaleNormal + '</td><td>' + jPdata.whPattern.FemaleMetabolicRisk + '</td>'
                        })
                        $tableWHR.append($tr2);
                        
                        $('#orgChartArea').append($tableWHR);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#whrTable\')">Copy W/H Ratio</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        
                        //#####################################
                        //
                        // Process Life Style
                        //
                        //#####################################
                        $tableLifeStyle = $('<table>', {
                            id: 'lifeStyleTable',
                            html: '<tr><th></th><th></th><th></th><th></th></tr>'
                        })
                        
                        $tr1 = $('<tr>', {
                            html: '<td>overall</td><td>GOOD</td><td><-- --></td><td>BAD</td><td></td><td></td>'
                        })
                        $tableLifeStyle.append($tr1);
                        
                        var avgSLQ = jPdata.avgLifeStyle.SLQ
                        
                        //console.dir(jPdata['avgLifeStyle']['SLQ']);
                        
                        $tr2 = $('<tr>', { html: '<td>Sleep Quality</td><td>' + avgSLQ['good sleep'] + '</td><td>' + avgSLQ['fair sleep'] + '</td><td>' + avgSLQ['poor sleep'] + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr2);
                        
                        $tr3 = $('<tr>', { html: '<td>Average sleep hour</td><td>' + jPdata.avgLifeStyle.sleepHr + '</td><td></td><td></td><td></td>'
                        })
                        $tableLifeStyle.append($tr3);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var stress = jPdata.avgLifeStyle.stress
                        
                        $tr4 = $('<tr>', { html: '<td>Self evaluation of stress</td><td>' + stress['low stress'] + '</td><td>' +  stress['medium stress'] + '</td><td>' + stress['high stress'] + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr4);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var bowel = jPdata.avgLifeStyle.bowelHabbit
                        
                        $tr5 = $('<tr>', { html: '<td>Bowel habbit</td><td>' + bowel.good + '</td><td>' + bowel.malabsorption + '</td><td>' + bowel.constipation + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr5);
                        //$tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var drink = jPdata.avgLifeStyle.drinkingHabbit
                        
                        $tr6 = $('<tr>', { html: '<td>Most drinking type</td><td>' + drink.good + '</td><td>' + drink.bad + '</td><td></td><td></td>'
                        })
                        $tableLifeStyle.append($tr6);
                        $tr7 = $('<tr>', { html: '<td>Average drinking (glass)</td><td>' + jPdata.avgLifeStyle.drinkingGlass + '</td><td></td><td></td><td></td>'
                        })
                        $tableLifeStyle.append($tr7);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var eating = jPdata.avgLifeStyle.eatingHabbit
                        
                        $tr8 = $('<tr>', { html: '<td>Eating habbit</td><td>' + eating.vegetable + '</td><td>' + eating['dairy product'] + '</td><td>' + eating.fruit + '</td><td>' + eating.seafood + '</td><td>' + eating.meat + '</td>'
                        })
                        $tableLifeStyle.append($tr8);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var smoking = jPdata.avgLifeStyle.smoking
                        
                        $tr9 = $('<tr>', { html: '<td>Smoke habbit</td><td>' + smoking['not smoke'] + '</td><td>' + smoking['light smoker'] + '</td><td>' + smoking['heavy smoker'] + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr9);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var alcohol = jPdata.avgLifeStyle.alcohol
                        
                        $tr10 = $('<tr>', { html: '<td>Alcohol habbit</td><td>' + alcohol['no alcohol'] + '</td><td>' + alcohol['social drinker'] + '</td><td>' + alcohol['heavy drinker'] + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr10);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        var exercise = jPdata.avgLifeStyle.exercise
                        
                        $tr11 = $('<tr>', { html: '<td>Exercise habbit</td><td>' + exercise['sport man'] + '</td><td>' + exercise['exercise'] + '</td><td>' +exercise['sedentary'] + '</td><td></td>'
                        })
                        $tableLifeStyle.append($tr11);
                        $tableLifeStyle.append('<tr><td colspan="99"><hr><td></tr>');
                        
                        //#####################################
                        //
                        // Process Actual stress
                        //
                        //#####################################
                        $('#orgChartArea').append($tableLifeStyle);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#lifeStyleTable\')">Copy LIFE STYLE</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        $tableStress = $('<table>', {
                            id: 'stressTable',
                            html: '<tr><th></th><th></th><th></th></tr>'
                        })
                        
                        $tr1 = $('<tr>', {
                            html: '<td>overall</td><td>Relax</td><td>Stress</td>'
                        })
                        $tableStress.append($tr1);
                        $tr2 = $('<tr>', {
                            html: '<td></td><td>' + jPdata.stressPattern.goodHRRR + '</td><td>' + jPdata.stressPattern.dissociateHRRR + '</td>'
                        })
                        $tableStress.append($tr2);
                        $('#orgChartArea').append($tableStress);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#stressTable\')">Copy HR/RR ratio</button>')
                        $('#orgChartArea').append('<hr>');
                        
                        //########################################
                        //
                        //    PROCESS TOP HIT AILMENTS
                        //
                        //########################################
                        $tableTopIll = $('<table>', {
                            id: 'topillTable',
                            html: '<tr><th>TOP ILLNESS & COMPLAINT</th><th><img src="images/SortNum.png" class="btn btn-primary" width="40" height="30"></th></tr>',
                            style: "background-color: white;"
                        })
                        
                        $.each(jPdata.dxidx, function(index, elem){
                            $tr1 = $('<tr>', {
                                html: '<td class="illTxt">' + elem.txt + '</td><td class="count">' + elem.count + '</td>'
                            })
                            $tableTopIll.append($tr1);
                        })
                        
                        $('#orgChartArea').append($tableTopIll);
                        $('#orgChartArea').append('<br><button class="btn btn-default btn-block" onclick="copyToClipboard(\'#topillTable\')">Copy TOP ILL</button>')
                        $('#orgChartArea').append('<hr>');
                        $('#orgChartArea').append('<br><br><br><br><br><br><br><br><br>');
                        
                        $('th').click(function(){
                            var table = $(this).parents('table').eq(0)
                            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
                            this.asc = !this.asc
                            if (!this.asc){rows = rows.reverse()}
                            for (var i = 0; i < rows.length; i++){table.append(rows[i])}
                        })
                        function comparer(index) {
                            return function(a, b) {
                                var valA = getCellValue(a, index), valB = getCellValue(b, index)
                                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                            }
                        }
                        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
                    })
                })
            });
        })

        $('#selOrg').trigger('change');

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
            //var targetPayer = $('#billFiltrated').attr('payer');
            
            var arrOwner = new Array();
            
            
            
            $('.owner').each(function(index, owner){
                arrOwner.push ({ com_pos: $(this).attr('t_cashier'), hn: $(this).attr('id') })
            })
            
            console.dir(arrOwner);
            //console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');
            
            //if(typeof(targetPayer) != 'undefined'){
            if(arrOwner.length > 0){    
                funcCashier.openWindowWithPostRequest({ paidBy: arrOwner, cl_cash_tx: 'preview bills' },'preview');
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
                                    html: billName + cashRevertButt(Jsonp.tab.name, value.hospital_number),
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
                    
                    $('.revertBill').off('click').on('click',function(){
                        revertBill($(this).attr('revert_hn'));
                    });
                    
                    
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
                    
                    
                        var item = '<td>' + (parseInt(index)+1) +'</td><td><input type="checkbox" class="confirm" hn="' + numHN + '" value="check"' + isCheck + '></td><td width="300px">' + value.product_name + '<br><font style="font-size:0.8em;" color="gray">' + numHN + '/' + value.ODT + '/' + value.slash_number + '</font></td><td>x <amount>' + value.amount + '</amount></td><td  width="80px">' + value.unit + '</td><td style="width:200px; overflow:hidden; max-width:200px;">' + value.dosage + '</td><td class="fullprice hidden">' + value.full_price + '</td><td' + pColor + ' class="dprice" width="100px">' + value.discounted_price + '</td><td>' + $('#selectCurrency option:selected').attr('show') + '</td><td width="100px" class="sumItem">' + sumItem(value.discounted_price, value.amount) + '</td>';
                        
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
                $('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ');
                
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
                var record_hn = $(this).find('.confirm').attr('hn');
                var old_id = $(this).attr('old_id');
                var pd_id = $(this).attr('pd_id');
                
                if(typeof(old_id) == 'undefined'){ old_id = 'null'; }
                
                var isCheck = $(this).find('.confirm').is(':checked');

                //console.log($(this).text() + ' isCheck = ' + isCheck);
                
                arrRecord[key] = {
                    record_id: record_id,
                    hn: record_hn,
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
              console.log('paidBill rtn data --v');
              console.dir(data);
              //alert('data ' + isJson(data));

              var pData = $.parseJSON(data)
              var updateIDs = pData.update_id;
              var cl_cash_tx = pData.cl_cash_tx;
            
              $('th#cl_cash_tx').html('');
              $.each(cl_cash_tx,function(index,value){
                $('th#cl_cash_tx').append('<tx>' + value + '</tx>');
              });
            
              $.each(updateIDs,function(index,HNrecord_id){
                  console.log('HNrecord_id --v');
                  console.dir(HNrecord_id);
                  
                  var tRow = $("tr[record_id='" + HNrecord_id.rec_id + "']")
                  
                  tRow.removeClass('ui-state-highlight');
                  tRow.addClass('btn-success');
                  tRow.find('.confirm').attr('disabled','disabled');
                  
                  
                  
              });
                        
              refreshLeftColumn();
              funcCashier.openWindowWithPostRequest({ paidBy: paidBy, cl_cash_tx: cl_cash_tx, update_id: pData.update_id }, 'check bill');
            
              $("#AlertHeader").text('BILL(S) PAID');
              $("#AlertMsg").text(pData.update_id.length + 'BILL(S) WAS PAID.');
              $("#myModal").modal();
            
              
            
          }); //End of $.get('multiModdat.php')
    }, //End of doPaidBill
    
    showPaidBill: function showPaidBill(arr_paidby_cash_tx){
        //console.dir(arr_paidby_cash_tx);
        
        paidBy = arr_paidby_cash_tx.paidBy;
        cl_cash_tx = arr_paidby_cash_tx.cl_cash_tx;
        
        console.log('paidBy = '+ paidBy);
        console.log('cl_cash_tx = '+ cl_cash_tx);
        //funcCashier.openWindowWithPostRequest(arr_paidby_cash_tx);
        
        funcCashier.openWindowWithPostRequest({ paidBy: paidBy, cl_cash_tx: cl_cash_tx }, 'check bill');
    },
    
    openWindowWithPostRequest: function openWindowWithPostRequest(arr_paidby_cash_tx, forceShowMode) {
      var winName='PRINT RECEIPT';
      var winURL='include/showPaidBill.php';
      var windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
        
      var showMode = $('.show.mode:checked').val();
      console.log('show mode = ' + showMode);
        
      console.log('list cash to show --v');
      console.dir(arr_paidby_cash_tx);
        
      var params,cl_cash_tx,preview_bills;
        
      console.log('forceShowMode = ' + forceShowMode);
        
      if(forceShowMode == 'preview'){
          showMode = forceShowMode;
          params = { paid_by_whom: 'no yet paid', show_mode:showMode };
          preview_bills = arr_paidby_cash_tx['paidBy'];
          
          console.log('PREVIEW assign cl_cash_tx as --v');
          console.dir(preview_bills);
      }
      else if(forceShowMode == 'check bill'){      
          params = { paid_by_whom: arr_paidby_cash_tx['paidBy'], show_mode:showMode };
          cl_cash_tx = arr_paidby_cash_tx['cl_cash_tx'];
          
          console.log('CHECK BILL assign cl_cash_tx as --v');
          console.dir(cl_cash_tx);
      }
        
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
    
      //var cl_cash_tx = arr_paidby_cash_tx['cl_cash_tx'];
        
      console.log('receive cl_cash_tx as --v');
      console.dir(cl_cash_tx);
      console.log('### showMode ' + showMode + " ###");
      
      if(showMode == 'items'){
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
      }
      else if(showMode == 'preview'){ //IN CASE OF PREVIEW
          
              console.log('send to preview');
              $.each(preview_bills, function(index,bill){
                console.dir(bill);  
                  
                var input = document.createElement('input');
                  input.type = 'hidden';
                  input.name = 'bill' + index;
                  input.value = bill['hn'] + '.' + bill['com_pos'];
                  form.appendChild(input);
              })
          
              /*for (var i in cl_cash_tx) {
                if (cl_cash_tx.hasOwnProperty(i)) {
                  var input = document.createElement('input');
                  input.type = 'hidden';
                  input.name = 'tx' + i;
                  input.value = cl_cash_tx[i];
                  form.appendChild(input);

                }
              }*/
          
      }
        
      document.body.appendChild(form);                       
      //window.open('', winName,windowoption);
      window.open('', winName);
      form.target = winName;
      form.submit();                 
      document.body.removeChild(form);           
    }
} //End of funcCashier namespace


//####################################################################################
//##
//##      function for Course Creation
//##
//##
//####################################################################################
var funcCourse = {
	initTabs: function initTabs(){
		$('#FuncCanvas mainUI').css('height',100);
		//var $tabsBill = $( "#tabsBill" ).tabs().css('width', 330).css('font-size', 12).removeClass('ui-widget-content');
        var $tabsBill = $( "#tabsBill" ).css('font-size', '0.8em');
        
		//$('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ').css('text-align','center').css('height',$('#billFiltrated').parent().height()-50).css('overflow-x','hidden');
        
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
        
        $('.addNewCourse').click(function(){
            addNewCourse();
        })
        
		
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
            //var targetPayer = $('#billFiltrated').attr('payer');
            
            var arrOwner = new Array();
            
            
            
            $('.owner').each(function(index, owner){
                arrOwner.push ({ com_pos: $(this).attr('t_cashier'), hn: $(this).attr('id') })
            })
            
            console.dir(arrOwner);
            //console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');
            
            //if(typeof(targetPayer) != 'undefined'){
            if(arrOwner.length > 0){    
                funcCashier.openWindowWithPostRequest({ paidBy: arrOwner, cl_cash_tx: 'preview bills' },'preview');
            }
        });
	},
	
	initFilter: function initFilter(){
		var tElem = $('#courseDetail');
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
		
	}
}

//####################################################################################
//##
//##      function for Product Creation
//##
//##
//####################################################################################
var funcICD10 = {
	initICD10: function initICD10(){
        $( "input#searchDx" ).autocomplete({
              source: "./include/searchICD10.php",
              minLength: 1,
              select: function( event, ui ) {

                  console.dir(ui.item)

                  funcICD10.addNewICD10tr(ui.item);
                  $("input#searchDx").val('');
                  
                  
              },
          })
        
        $('.ICD10').each(function(){
            console.log('tr icd = ' + $(this).attr('icd10'));
            $(this).find('img').bind('click',function(){
                $(this).closest('tr').remove();
            });
        })
        //alert('ICD10 ready there are ' + $('.ICD10').length + ' ICD10');
    },
    
    addNewICD10tr: function addNewICD10tr(icd){
        
        var arrStatus = ['diagnosis', 'cure', 'under treatment', 'relapse', 'recurrent', 'unknown'];
        
        var $optStatus;
        
        $.each(arrStatus, function(index, txt){
            $optStatus = $optStatus + '<option value="' + txt + '">' + txt + '</option>';
        })
        
        var selStatus = $('<select>',{
            style: 'background-color:#cccccc;',
            html: $optStatus
        })
        
        //alert($optStatus);

        
        $newBtn = $('<div>',{
            class: 'btn btn-warning btn-block',
            html: '&nbsp;<icd_code>' + icd.code + '</icd_code>&nbsp;&nbsp;<icd_txt>' + icd.value +  '</icd_txt>&nbsp;&nbsp;status&nbsp;&nbsp;'
        }).append(selStatus);
        
        var $itsDel = $('<img>',{
                    width:"15",
                    height:"15",
                    style:'position:relative; left:0px;',
                    src:"../ICONS/delete.png"
        });
        $itsDel.click(function(){
            $(this).closest('tr').remove();
        })
        
        $newBtn.prepend($itsDel);
        
        $newTDicd_txt = $('<td>',{
            colspan: 99
        })
        
        $newTDicd_txt.append($newBtn);
        
        var nowDate = now();
        
        $newTR = $('<tr>',{
            class: 'ICD10',
            icd10: icd.code,
            ondate: nowDate
        })

        $newTR.append($newTDicd_txt);
        
        var $lastICDrow = $('#lastICDRow');
        $lastICDrow.before($newTR)
    }
}
//########################### END OF NAME SPACE ######################################

//####################################################################################
//##
//##      function for Product Creation
//##
//##
//####################################################################################
var funcProductMx = {
	initTabs: function initTabs(){
		$('#FuncCanvas mainUI').css('height',100);
		//var $tabsBill = $( "#tabsBill" ).tabs().css('width', 330).css('font-size', 12).removeClass('ui-widget-content');
        var $tabsBill = $( "#tabsBill" ).css('font-size', '0.8em');
        
		//$('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ').css('text-align','center').css('height',$('#billFiltrated').parent().height()-50).css('overflow-x','hidden');
        
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
        
        $('.addNewCourse').click(function(){
            addNewProduct();
        })
        
        
        
		
		refreshLeftColumn = function(){
		
			/*funcProductMx.refreshItem({
				tElem: '#tabs-1',
				product_type: 0,
				dateRange: { 
                            startDate: 'all',
                            endDate: null
                        },
                com_pos: 'null'
			});
			
			funcProductMx.refreshItem({
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
            
            funcProductMx.refreshItem({
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
			});*/
            
            console.log('3 TABS REFRESH SUCCEEDED');
            
            $('.nav-tabs li').on('click',function(){
                var newTab = $(this).attr('index');

                $('.nav-tabs li').removeClass('active');
                $(this).addClass('active');
                $('.tab-pane').removeClass('in active');
                $($(this).find('a').attr('href')).addClass('in active');
                
                $('input#cat_id').val($(this).find('a').attr('cat_id'));
                console.log('change input#cat_id val (' + $('input#cat_id').length + ') to ' + $(this).find('a').attr('cat_id'));
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
            //var targetPayer = $('#billFiltrated').attr('payer');
            
            var arrOwner = new Array();
            
            
            
            $('.owner').each(function(index, owner){
                arrOwner.push ({ com_pos: $(this).attr('t_cashier'), hn: $(this).attr('id') })
            })
            
            console.dir(arrOwner);
            //console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');
            
            //if(typeof(targetPayer) != 'undefined'){
            if(arrOwner.length > 0){    
                funcCashier.openWindowWithPostRequest({ paidBy: arrOwner, cl_cash_tx: 'preview bills' },'preview');
            }
        });
	},
	
	initFilter: function initFilter(){
		var tElem = $('#productDetail');
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
		
	}
}

//####################################################################################
//##
//##      function for Notify Remed
//##
//##
//####################################################################################
var funcNotifyRemed = {
    initTabs: function initTabs() {
        $('#FuncCanvas mainUI').css('height', 100);
        //var $tabsBill = $( "#tabsBill" ).tabs().css('width', 330).css('font-size', 12).removeClass('ui-widget-content');
        var $tabsBill = $("#tabsBill").css('font-size', '0.8em');

        //$('#billFiltrated').html('เลือกบิลจากแถบขวามือ เพื่อทำการคิดเงิน<br>กด Shift เพื่อคิดบิลร่วมกับคนอื่นๆ').css('text-align','center').css('height',$('#billFiltrated').parent().height()-50).css('overflow-x','hidden');

        var refreshButt = $('#refreshBills').click(function () {
            refreshLeftColumn();
            console.log('casher refreshed');
        }).css('style', 'width:100%;'); //.addClass('btn btn-primary');



        $('#searchPt').addClass('hidden');
        $('#widget2').html($("#tabsBill"))
        $('#widget2').prepend(refreshButt);
        $('#widget2').removeClass('hidden');
        $('#widget2').removeClass('hidden panel panel-info');
        $('#widget2').css('min-height', '600px');

        $('.addNewCourse').click(function () {
            addNewProduct();
        })




        refreshLeftColumn = function () {

			/*funcProductMx.refreshItem({
				tElem: '#tabs-1',
				product_type: 0,
				dateRange: { 
                            startDate: 'all',
                            endDate: null
                        },
                com_pos: 'null'
			});
			
			funcProductMx.refreshItem({
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
            
            funcProductMx.refreshItem({
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
			});*/

            console.log('3 TABS REFRESH SUCCEEDED');

            $('.nav-tabs li').on('click', function () {
                var newTab = $(this).attr('index');

                $('.nav-tabs li').removeClass('active');
                $(this).addClass('active');
                $('.tab-pane').removeClass('in active');
                $($(this).find('a').attr('href')).addClass('in active');

                $('input#cat_id').val($(this).find('a').attr('cat_id'));
                console.log('change input#cat_id val (' + $('input#cat_id').length + ') to ' + $(this).find('a').attr('cat_id'));
            })

            $('.nav-pills li').on('click', function () {
                $('.nav-pills li').removeClass('active');
                $(this).addClass('active');

                if ($(this).find('a').attr('com_pos') != 'n/a') {
                    funcCashierReport.openWindowWithPostRequest($(this).find('a').attr('com_pos'), $('#reportPicker').val())
                }
            })
        }

        refreshLeftColumn();



        $('#previewBill').click(function () {
            //var targetPayer = $('#billFiltrated').attr('payer');

            var arrOwner = new Array();



            $('.owner').each(function (index, owner) {
                arrOwner.push({ com_pos: $(this).attr('t_cashier'), hn: $(this).attr('id') })
            })

            console.dir(arrOwner);
            //console.log('preview targetPayer ' + targetPayer + ' set cl_cash_tx = preview bills');

            //if(typeof(targetPayer) != 'undefined'){
            if (arrOwner.length > 0) {
                funcCashier.openWindowWithPostRequest({ paidBy: arrOwner, cl_cash_tx: 'preview bills' }, 'preview');
            }
        });
    },

    initFilter: function initFilter() {
        var tElem = $('#productDetail');
        var fDate = $('#FuncCanvas #filterDate');



        var stPick = $('#startPicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            showWeek: true,
            yearRange: 'c-50:c+50',
            defaultDate: '-1m',
            selectDefaultDate: true,

            dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],

            monthNamesShort: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],

            changeMonth: true,
            changeYear: true,

            onChangeMonthYear: function () {
                setTimeout(function () {
                    //var rtn = Th_En_Y();  
                    //console.log('CE = ' + rtn.CE + ' Vs BE = ' + rtn.BE);
                }, 50);
            },
            onSelect: function (dateText, inst) {
                dateBefore = $(this).val();
                var arrayDate = dateText.split("-");
                //arrayDate[2]=parseInt(arrayDate[2]) + parseInt($('#yearType').val());  
                var dateStr = arrayDate[2] + "-" + arrayDate[1] + "-" + arrayDate[0];
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

            dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],

            monthNamesShort: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],

            changeMonth: true,
            changeYear: true,

            onChangeMonthYear: function () {
                setTimeout(function () {
                    //var rtn = Th_En_Y();  
                    //console.log('CE = ' + rtn.CE + ' Vs BE = ' + rtn.BE);
                }, 50);


            },
            onSelect: function (dateText, inst) {
                dateBefore = $(this).val();
                var arrayDate = dateText.split("-");
                //arrayDate[2]=parseInt(arrayDate[2]) + parseInt($('#yearType').val());  
                var dateStr = arrayDate[2] + "-" + arrayDate[1] + "-" + arrayDate[0];
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
        })).bind('mouseup', function () {
            stPick.show();
        });
        stPick.appendTo($stButt);
        stPick.hide();

        var $ndButt = $('#endDate').text(now({
            d: 0,
            m: 0,
            y: 0
        })).bind('mouseup', function () {
            ndPick.show();
        });
        ndPick.appendTo($ndButt);
        ndPick.hide();

    }
}


//####################################################################################
//##
//##      function for Phenotype Analysis
//##
//##
//####################################################################################
var funcPhenotype= {

    openWindowWithPostRequest: function openWindowWithPostRequest(arrPtInput, winName, HN) {
    
      //var winName= arrPtInput['ptName'] + arrPtInput['ptLastName'] + ' result';
        
      if(typeof(winName) == 'undefined'){
        winName = '_self';
      }
        
        //winName = '_blank'

      console.log('create tab ' + winName);

      var winURL='include/healthEngine/advConsult.php';
      var windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';


      console.log('phenotype arrPtInput --v');
      console.dir(arrPtInput);


     var form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", winURL);
      form.setAttribute("target",winName); 
        
      console.log('for HN' + HN);
        
      if(typeof(HN) != 'undefined'){
        
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'targetHN';
        input.value = HN;
        form.appendChild(input);
      }
        
      for (var i in arrPtInput) {
        if (arrPtInput.hasOwnProperty(i)) {
          if(i == 'curchar' && !isJson(arrPtInput[i])){
              arrPtInput[i] = $.parseJSON(arrPtInput[i]);
          }

          if(typeof arrPtInput[i] === 'object' && arrPtInput[i] !== null){
              
              $.each(arrPtInput[i], function(index, item){
                  
                  if(typeof item === 'object' && item !== null){
                    $.each(item, function(idx2, itm2){
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = i + '[' + index + '][' + idx2 + ']';
                        input.value = itm2;
                        form.appendChild(input);
                    })
                      
                  }
                  else{
                      var input = document.createElement('input');
                      input.type = 'hidden';
                      input.name = i + '[' + index + ']';
                      input.value = item;
                      form.appendChild(input);
                  }
              });
          }
          else{
              var input = document.createElement('input');
              input.type = 'hidden';
              input.name = i;
              input.value = arrPtInput[i];
              form.appendChild(input);
          }
          
        }
      }

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'open condition';
        input.value = 'in new tab';
        form.appendChild(input);

      document.body.appendChild(form);  

      //window.open('', winName,windowoption);
      var reportWin = window.open('', winName);
      form.target = winName;
      form.submit();                 
      document.body.removeChild(form); 

    }
}

//####################################################################################
//##
//##      function for Edit Medical Image
//##
//##
//####################################################################################
var funcEditMedImg= {

    openWindowWithPostRequest: function openWindowWithPostRequest(arrImgInput) {
    
      //var winName= arrPtInput['ptName'] + arrPtInput['ptLastName'] + ' result';
      var winName = '_blank';

      console.log('create tab ' + winName);

      var winURL='include/editMedImg.php';
      var windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';


      console.log('arrImgInput --v');
      console.dir(arrImgInput);


     var form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", winURL);
      form.setAttribute("target",winName);  
      for (var i in arrImgInput) {
        if (arrImgInput.hasOwnProperty(i)) {
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = i;
          input.value = arrImgInput[i];
          form.appendChild(input);
        }
      }

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'open condition';
        input.value = 'in new tab';
        form.appendChild(input);

      document.body.appendChild(form);  

      //window.open('', winName,windowoption);
      var reportWin = window.open('', winName);
      form.target = winName;
      form.submit();                 
      document.body.removeChild(form); 

    }
}

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

function addNewCourse(){
    console.log('addNewCourse added');
    
    var $tCDetail = $('#courseDetail');
    $tCDetail.html('');
    
    var $tb = $('<table>',{
        id: 'courseTab',
        class: "text table table-striped",
        style: "display:table; background-color: white;"
    })
    
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $inpText = $('<input>',{
        placeholder: 'this course name',
        value: '',
        width: '70%',
        id: 'cName'
    })
    $tr.find('td:eq(0)').append('COURSE NAME')
    $tr.find('td:eq(1)').append($inpText)
    $tb.append($tr);
    
    //##################################
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $inpText = $('<input>',{
        placeholder: 'course owner',
        value: gOwner,
        width: '70%',
        id: 'cOwner'
    })
    $tr.find('td:eq(0)').append('COURSE OWNER')
    $tr.find('td:eq(1)').append($inpText)
    $tb.append($tr);
    
    //##################################
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $inpText = $('<input>',{
        placeholder: 'copy right holder',
        value: gOwner,
        width: '70%',
        id: 'crOwner'
    })
    $tr.find('td:eq(0)').append('COPY RIGHT HOLDER')
    $tr.find('td:eq(1)').append($inpText)
    $tb.append($tr);
    
    //##################################
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $inpText = $('<input>',{
        placeholder: 'course length (Yr)',
        value: '',
        width: '70%',
        id: 'cLength'
    })
    $tr.find('td:eq(0)').append('COURSE LENGTH')
    $tr.find('td:eq(1)').append($inpText)
    $tb.append($tr);
    
    //##################################
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $inpText = $('<input>',{
        placeholder: '1st year, 2nd year, ...',
        value: '',
        width: '70%',
        id: 'nthYr'
    })
    $tr.find('td:eq(0)').append('YEAR')
    $tr.find('td:eq(1)').append($inpText)
    $tb.append($tr);
    
    //##################################
    var $tr = $('<tr>',{
        html:'<td class="txt"></td><td class="inp"></td>',
        style:'width:100%;'
    })
    var $btn = $('<div>',{
        text: 'Add new media',
        class: 'btn btn-default',
        width: '70%'
    }).click(function(){
        addNewMedia($('#mediaType').val());
    })
    
    var $optMediaType
    var $optArr = { 'vdo':'vdo clip', 'quiz':'quiz', 'conf':'conference', 'assig':'assignment' }
    
    $.each($optArr,function(key,text){
        $optMediaType = $optMediaType + '<option value="' + key + '">' + text +  '</option>'
    })

    var $selMediaType = $('<select>',{
        border: '0px solid',
        width: '90%',
        padding: '5px',
        id: 'mediaType'
    }).append($optMediaType);
    $selMediaType.find('option:eq(0)').prop('selected', true);
    
    $tr.find('td:eq(0)').append($selMediaType)
    $tr.find('td:eq(1)').append($btn)
    $tb.append($tr);
    
    
    
    
    $tCDetail.append($tb);
    
    $saveBtn = $('<div>',{
        id: 'saveCourse',
        class: 'btn btn-primary btn-block',
        text: 'SAVE THIS COURSE',
        mode: 'new'
    }).click(function(){
        saveCourse($(this).attr('mode'))
    })
    
    $tCDetail.append($saveBtn)
    $tCDetail.append('<hr>')
}

function saveCourse(mode){
    console.log('save ' + mode)
    $('#courseTab').find('input, textarea').each(function(){
        $(this).closest('td').removeClass('alert-warning');
    })
    
    var validate = true;
    $('#courseTab').find('input, textarea').each(function(){
        if($(this).val() == ''){
            $(this).closest('td').addClass('alert-warning');

            validate = false;
            
        }
    })
    
    if(validate){
        /*var arrMail = { 
            name: 'TIPATAT JUNHASAVASDIKUL',
            email: 'dr.tipatat@gmail.com',
            subject: 'TEST WITH LOVE',
            details: 'ทดสอบการส่งข้อความจากระบบ Server ไม่มีอะไรต้องเป็นห่วงครับ\nรักนะครับ\ปอง'
            
        };

        
        $.post('./mailer.php', arrMail, function(data){
            $("#AlertMsg").text(data);
            $("#myModal").modal();
        });*/
        
        var arrMedia = { };
        
        $('.tabsMedia').each(function(index, value){
            var mediaType = $(this).attr('type');
            
            switch(mediaType){
                case 'vdo':
                    var arrDetails = { }
                    
                    $(this).find('select,input').each(function(){
                        arrDetails[$(this).attr('class')] = $(this).val();
                    })
                    arrDetails['index'] = index;
                    
                    arrMedia['' + index] = arrDetails;
                    
                    
                    break;
            }
        })
        
        var CDTL = {
            cName: $('#cName').val(),
            cOwner:$('#cOwner').val(),
            crOwner:$('#crOwner').val(),
            cLength:$('#cLength').val(),
            nthYr:$('#nthYr').val(),
            details:arrMedia
        }

        $.post('./include/saveCourse.php', CDTL ,function(data){
            $("#AlertMsg").html(data);
            $("#myModal").modal();
        })
    }
    else
    {
        $("#AlertHeader").text('WARNING')
        $("#AlertMsg").html('SOME DATA INCOMPLETE!<br>CAN\'T SAVE THIS COURSE TO DATABASE');
        $("#myModal").modal();
    }
}

var arrMediaType = {
    vdo: '<ul  class="nav nav-tabs" style="font-family:Helvetica; background-color:rgba(255, 255, 255, 0.0); font-size:0.8em;"><li index="1" class="active"><a href="#tabs-1" data-toggle="tab" show=""><img src="../ICONS/delete.png" width="15" height="15"> &nbsp;<name><n></n> SUBJECT</name></a></li></ul><div class="tab-content"><div id="media-1" class="content" show="unpublished" class="tab-pane fade in active tab_self" style="font-size:0.8em; padding:10px"></div></div>',
    
    quiz: '<ul  class="nav nav-tabs" style="font-family:Helvetica; background-color:rgba(255, 255, 255, 0.0); font-size:0.8em;"><li index="1" class="active"><a href="#tabs-1" data-toggle="tab" show=""><img src="../ICONS/delete.png" width="15" height="15"> &nbsp;<name><n></n> QUIZ</name></a></li></ul><div class="tab-content"><div id="media-1" class="content" show="unpublished" class="tab-pane fade in active tab_self" style="font-size:0.8em; padding:10px"></div></div>',
    
    conf: '<ul  class="nav nav-tabs" style="font-family:Helvetica; background-color:rgba(255, 255, 255, 0.0); font-size:0.8em;"><li index="1" class="active"><a href="#tabs-1" data-toggle="tab" show=""><img src="../ICONS/delete.png" width="15" height="15"> &nbsp;<name><n></n> CONFERENCE APPOINTMENT</name></a></li></ul><div class="tab-content"><div id="media-1" class="content" show="unpublished" class="tab-pane fade in active tab_self" style="font-size:0.8em; padding:10px"></div></div>',
    
    assig: '<ul  class="nav nav-tabs" style="font-family:Helvetica; background-color:rgba(255, 255, 255, 0.0); font-size:0.8em;"><li index="1" class="active"><a href="#tabs-1" data-toggle="tab" show=""><img src="../ICONS/delete.png" width="15" height="15"> &nbsp;<name><n></n> ASSIGNMENT</name></a></li></ul><div class="tab-content"><div id="media-1" class="content" show="unpublished" class="tab-pane fade in active tab_self" style="font-size:0.8em; padding:10px"></div></div>',
}

function addNewMedia(typeMedia){
    console.log('new media (' + typeMedia + ') added');
    
    var $mediaPanel = $('<td>',{
        class: 'tabsMedia ' + typeMedia,
        type: typeMedia,
        colspan: 2,
        html: arrMediaType[typeMedia],
    })
    
    $mediaPanel.find('n').text(($('.tabsMedia').length + 1));
    
    var $delBut = $mediaPanel.find('img').click(function(){
        $(this).closest('.tabsMedia').remove();

        var n = 1;

        $('n').each(function(){

            $(this).text(n);
            n = n + 1;
        })
    })

    switch(typeMedia){
        case 'vdo':
            

            var $optMediaType
            var $optArr = { 'vdo':'vdo clip', 'quiz':'quiz', 'conf':'conference', 'assig':'assignment' }

            $.each($optArr,function(key,text){
                $optMediaType = $optMediaType + '<option value="' + key + '">' + text +  '</option>'
            })

            var $selMediaType = $('<select>',{
                border: '0px solid',
                width: '90%',
                padding: '5px',
                class: 'selType'
            }).append($optMediaType);
            $mediaPanel.find('.content').append($selMediaType);
            $mediaPanel.find('.content').append('<br><br>');


            var $inpMediaName = $('<input>',{
                placeholder: 'subject name',
                border: '0px solid',
                width: '90%',
                padding: '5px',
                class: 'subjName'
            })
            $mediaPanel.find('.content').append($inpMediaName);
            $mediaPanel.find('.content').append('<br><br>');

            var $inpMediaURL = $('<input>',{
                placeholder: 'URL',
                value: 'https://player.vimeo.com/video/',
                border: '0px solid',
                width: '90%',
                padding: '5px',
                class: 'medURL'
            }).keyup(function(e){
                if(e.keyCode == 13){
                    $(this).parent().find('.vdo').attr('src',
                    $(this).val())

                }         
            })
            $mediaPanel.find('.content').append($inpMediaURL);
            $mediaPanel.find('.content').append('<br>');

            var $inpMediaFrame = $('<iframe>',{
                src: '',
                border: '0px solid',
                width: '320px',
                height: '180px',
                frameborder: '0',
                padding: '5px',
                class: 'vdo'
            })


            $mediaPanel.find('.content').append($inpMediaFrame);
            $mediaPanel.find('.content').append('<br><br>');

            var $inp

            var $tr = $('<tr>',{
                style:'width:100%;',
                margin:'15px'
            }).append($mediaPanel)

            var $tCTab = $('#courseTab');
            $tCTab.append($tr);
            break;
        //#####################################
        case 'quiz':
            var $divQuest = $('<div>',{
                border: '0px solid',
                width: '90%',
                padding: '5px'
            })
            
            var $taQuiz = $('<textarea>',{
                placeholder: 'question',
                border: '0px solid',
                width: '70%',
                height: '150px',
                padding: '5px'
            })
            $divQuest.append($taQuiz);
            
            var $imgQuiz = $('<img>',{
                alt: 'question',
                border: '0px solid',
                width: '30%',
                height: '150px',
                padding: '5px'
            })
            $divQuest.append($imgQuiz);
            $divQuest.append('<br><br>');
            
            $mediaPanel.find('.content').append($divQuest);
            $mediaPanel.find('.content').append('<br>');
            
            var $formQuizOption = $('<form>',{
                border: '0px solid',
                width: '90%',
                padding: '5px'
            })
            
            for(i = 1; i < 6; i++){
                var $inpQuizOption = $('<input>',{
                    placeholder: 'answer ' + i,
                    border: '0px solid',
                    width: '70%',
                })
                $formQuizOption.append($inpQuizOption);
                
                var $radioQuizOption = $('<input>',{
                    type: 'radio',
                    name: 'correct_ans',
                    value: 'true',
                    border: '0px solid'
                }).bind('change',function(){
                    $(this).siblings().each(function(){
                        $(this).next('label').addClass('hidden');
                    });
                    $(this).next('label').removeClass('hidden');
                })
                
                $formQuizOption.append('&nbsp;&nbsp;');
                $formQuizOption.append($radioQuizOption);
                $formQuizOption.append('&nbsp;&nbsp;');
                $formQuizOption.append('<label for="correct_ans' + i + '" class="hidden">CORRECT</label>')
                $mediaPanel.find('.content').append($formQuizOption);
                
            }
            
            $mediaPanel.find('.content').append('<br><hr>');
            
            var $formRotateOption = $('<form>',{
                border: '0px solid',
                width: '90%',
                padding: '5px'
            })
            
            
            $formRotateOption.append('ALLOW RANDOM ROTATE ANSWER &nbsp;&nbsp;');
            
           
            var $radioRandomOption = $('<input>',{
                    type: 'radio',
                    name: 'randomAns',
                    value: 'true',
                    border: '0px solid'
                }).prop('checked',true)
           $formRotateOption.append($radioRandomOption)
            $formRotateOption.append('<label for="randomAns1"> YES </label>&nbsp;&nbsp;&nbsp;&nbsp;')
            
            var $radioRandomOption = $('<input>',{
                    type: 'radio',
                    name: 'randomAns',
                    value: 'true',
                    border: '0px solid'
                })
           $formRotateOption.append($radioRandomOption)
            $formRotateOption.append('<label for="randomAns2"> NO </label>')
            $mediaPanel.find('.content').append($formRotateOption);
            
            var $tr = $('<tr>',{
                style:'width:100%;',
                margin:'15px'
            }).append($mediaPanel)
            
            var $tCTab = $('#courseTab');
            $tCTab.append($tr);
            break;
        //#####################################
        case 'conf':
            
            $mediaPanel.find('.content').append('FORCE STUDENT TO ATTEND ONLINE CONFERENCE<br>BEFORE PROCEEDING TO NEXT LECTURE');
            
            var $tr = $('<tr>',{
                style:'width:100%;',
                margin:'15px'
            }).append($mediaPanel)
            
            var $tCTab = $('#courseTab');
            $tCTab.append($tr);
            break;
        //#####################################
            
        case 'assig':
            
            $mediaPanel.find('.content').append('STUDENT ASSIGNMENT');
            
            var $tr = $('<tr>',{
                style:'width:100%;',
                margin:'15px'
            }).append($mediaPanel)
            
            var $formProceedOption = $('<form>',{
                border: '0px solid',
                width: '90%',
                padding: '5px'
            })
            
            
            $formProceedOption.append('ALLOW PROCEED BEFORE ASSIGNMENT COMPLETION? &nbsp;&nbsp;');
            
           
            var $radioProceedOption = $('<input>',{
                    type: 'radio',
                    name: 'proceedAns',
                    value: 'true',
                    border: '0px solid'
                })
           $formProceedOption.append($radioProceedOption)
            $formProceedOption.append('<label for="proceedAns1"> YES </label>&nbsp;&nbsp;&nbsp;&nbsp;')
            
            var $radioProceedOption = $('<input>',{
                    type: 'radio',
                    name: 'proceedAns',
                    value: 'true',
                    border: '0px solid'
                }).prop('checked',true)
           $formProceedOption.append($radioProceedOption)
            $formProceedOption.append('<label for="proceedAns2"> NO </label>')
            $mediaPanel.find('.content').append($formProceedOption);
            
            var $inpAssign = $('<input>',{
                placeholder: 'Assign some report, for student to upload for consideration ex. *.doc, *.pdf, *.txt',
                value: '',
                border: '0px solid',
                width: '90%',
                padding: '5px'
            })
            
            $mediaPanel.find('.content').append($inpAssign);
            
            var $tCTab = $('#courseTab');
            $tCTab.append($tr);
            break;
        //#####################################
    }  
    
}