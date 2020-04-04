//var gRoot declared in header.php

$(function(){
	//แสดงจำนวนสินค้าบนเมนู
	upDateNavCartNumber();
	//แสดงตะกร้าสินค้า
    //alert(gRoot);
    
	$.ajax({
  		url: gRoot + 'include/miniCartAjax.php',
  		type:'post',
  		success:function(data){
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  		}
  	});
});

//input type date change
/*$(document).on('change','input[type="date"]',function() {
    $('input[type="date"]').change(function(){
        alert(this.value);         //Date in full format alert(new Date(this.value));
        var inputDate = new Date(this.value);
    });
})*/

//คลิกเพจลิงค์ด้านล่าง 
$(document).on('click','.ajax-page-link',function() {
		var currentTagId = $(this).attr('id');
		var str = currentTagId;
		var n = str.indexOf("-");
		var page = str.slice(n+1);
		pageNo = parseInt(page);
     	myProductAjax(pageNo);
    
        //alert('.ajax-page-link clicked');
});

//คลิกเมนูติดต่อเรา
$(document).on('click','#contact-us-nav',function() {
	var n = true;
	$.ajax({
  		url: gRoot + 'contactUsAjax.php',
  		data:{link:n},
  		type:'get',
  		success:function(data){
  			$("#product-container").empty().append(data).fadeIn(1000);
  			$("#category-container").hide();
  			$("#lastest-link-content").hide();
  		}
  	});	
});

function reinitPageLink(){
    console.log($('.ajax-page-link').length);
}




    $(document).on('click','.ajax-page-link',function() {
        var currentTagId = $(this).attr('id');
        var str = currentTagId;
        var n = str.indexOf("-");
        var page = str.slice(n+1);
        pageNo = parseInt(page);
        myProductAjax(pageNo);
    });

    $(document).on('click','.ajax-page-link-product-list',function() {
            var currentTagId = $(this).attr('id');
            var str = currentTagId;
            var n = str.indexOf("-");
            var page = str.slice(n+1);
            pageNo = parseInt(page);
            var currentTagCatId = $('.hidden-cat-product').attr('id');	
            var strCatId = currentTagCatId;
            var m = strCatId.indexOf("-");
            var cat = strCatId.slice(m+1);
            catId = parseInt(cat);
            listProductAjax(pageNo,catId);
    });



    //คลิก paging link ที่ผลลัพธ์มาจาก Search
    $(document).on('click','.ajax-page-link-search-list',function() {
            var currentTagId = $(this).attr('id');
            var str = currentTagId;
            var n = str.indexOf("-");
            var page = str.slice(n+1);
            pageNo = parseInt(page);
            var currentSearchStr = $('.hidden-search-product').attr('id');	
            var strSearch = currentSearchStr;
            var m = strSearch.indexOf("-");
            var searchText = strSearch.slice(m+1);
            productSearchAjax(pageNo,searchText);
    });

    $(document).on('click','.add-to-cart',function() {
            addToCartProcess($(this));
            /*var currentId = $(this).attr('id');
            var str = currentId;
            var n = str.indexOf("-");
            var pd_type = str.split('-')[0];
            var id = str.slice(n+1);
            var productId = { };

            if(pd_type == 'multi'){
                $arrID = $.parseJSON(id);

                $.each($arrID,function(index,copyDose){
                    productId[index] = copyDose;
                })
            }
            else{
                //SINGLE PRODUCT ID
                productId[id] = 'ct_dose';

                if($(this).find('.special-dosage').length > 0){
                    productId[id] = $(this).find('.special-dosage').html();
                }
            }

            miniCartAjax(productId,$(this).closest('.panel-info').attr('od_date'));*/

    });

    function addToCartProcess($THIS,opDose){
            var currentId = $THIS.attr('id');
            var str = currentId;
            var n = str.indexOf("-");
            var pd_type = str.split('-')[0];
            var id = str.slice(n+1);
            var productId = { };
        
            //console.clear();

            if(pd_type == 'multi'){
                $arrID = $THIS.data('id'); //$.parseJSON(id);
                $arrPrice = $THIS.data('dPrice'); 
                
                console.log('multi id');
                console.dir($arrID);
                console.log('--------');

                $.each($arrID,function(index,copyDose){
                    console.log('copyDose ==v');
                    console.dir(copyDose);
                    
                    productId[index] = { dose:copyDose, price:null };
                })
                
                
                $.each($arrPrice,function(index,copyPrice){
                    console.log('copyPrice = ' + copyPrice);
                    
                    productId[index]['price'] = copyPrice;
                })
            }
            else{
                //SINGLE PRODUCT ID
                /*if(typeof(opDose) == 'undefined'){
                    productId[id] = 'ct_dose';
                }
                else
                {
                    
                }*/

                if($THIS.find('.special-dosage').length > 0){
                    var str = $THIS.find('.special-dosage').html();
                    
                    
                    productId[id] = { dose:str, price: $THIS.data('dPrice') };
                }
                else if($THIS.find('.oldDose').length > 0){
                    var str = $THIS.find('.oldDose').html();
                    
                    
                    productId[id] = { dose:str, price: $THIS.data('dPrice') };
                }
                else{
                    productId[id] = { dose:'ct_dose', price: $THIS.data('dPrice') };
                }
                
                $arrPrice = $THIS.data('dPrice'); 
                
                console.log('single dPrice')
                console.dir($arrPrice);
                
                console.log('$$$$$$$$$$$$$$$$$$$$$$$$$$$$');
                console.dir(productId[id]);
                console.log('$$$$$$$$$$$$$$$$$$$$$$$$$$$$');
            }

            miniCartAjax(productId,$THIS.closest('.panel-info').attr('od_date'));
    }

    $(document).on('click','.show-detail-product',function() {
            var currentId = $(this).attr('id');
            var str = currentId;
            var n = str.indexOf("-");
            var id = str.slice(n+1);
            productId = parseInt(id);
            var currentTagCatId = $('.hidden-cat-product').attr('id');	
            var strCatId = currentTagCatId;
            var m = strCatId.indexOf("-");
            var cat = strCatId.slice(m+1);
            catId = parseInt(cat);
            showProductDetailAjax(productId,catId);

    });

    //#################################
    //    LINKE URL TO TOOLS
    //#################################
    $(document).on('mousedown','.list-group-item',function() {
        console.log('off changePt')
        $(document).off('changePt');
    });

    $(document).on('mouseup changeCat','.list-group-item',function() {
            var currentId = $(this).attr('id');
            var currentURL = $(this).attr('cat_url');
            var str = currentId;
            var n = str.indexOf("-");
            var id = str.slice(n+1);
            catId = parseInt(id);
            
            //console.clear();
            $('#checkSession').trigger('checkSession');

            console.log('catId = ' + catId);
            console.log('cat_url = ' + cat_url);

            $('#topImg').removeClass('no_print');
            $('#searchPt').removeClass('hidden');
            $('#widget2').html('').addClass('hidden');

            var tools = $("#catname-" + catId).attr('tools');
            var cat_url = $("#catname-" + catId).attr('cat_url');

            if(tools != 'cat_img_sel'){
                $('#tools_img_sel').attr('style','display: none;');
                $('#tools_of_cat').attr('style','display: block;');

                $('#toolCanvas').load(cat_url);
                console.log('show tools ' + cat_url);
            }
            else
            {
                $('#tools_img_sel').attr('style','display: table;');
                $('#tools_of_cat').attr('style','display: none;');

                catID = 24;
            }

            listProductAjax(1,catId);
            reinitPageLink();
    });

    $(document).on('click','.home-link',function() {
            var currentId = $(this).attr('id');
            var str = currentId;
            var n = str.indexOf("-");
            var id = str.slice(n+1);
            catId = parseInt(id);
            listProductAjax(1,catId);
    });

    $(document).on('click','.category-group-item',function() {
            var currentId = $(this).attr('id');
            var str = currentId;
            var n = str.indexOf("-");
            var id = str.slice(n+1);
            catId = parseInt(id);
            listProductAjax(1,catId);
            console.log('show item in cat ');
    });

    $(document).on('click','.search-product',function() {
        $(window).scrollTop($('#product-container').offset().top);
        var str = $('#search-box').val();
        //var findString = str.replace(/([ #;&,.%+*~\':"!^$[\]()=>|\/])/g,'\\\\$1');
        var findString = str;
        productSearchAjax(1,findString);

    });

    $(document).on('keyup','#search-box', function(e){
        //console.log('search ' + $('#search-box').val());
        var searchCondition = $('#search-box').val();
        
        if(e.which != 13 && searchCondition != ''){
            $('.order_item,.lab-item').each(function(){
                var str = $(this).text(); 
                var search = searchCondition;
                var res = str.toLowerCase().search(search.toLowerCase());

                if(res >= 0){
                    $(this).removeClass('hidden');
                    $(this).next('staff').removeClass('hidden');
                    $(this).next('staff').next('hr').removeClass('hidden');
                }
                else{
                    $(this).addClass('hidden');
                    $(this).next('staff').addClass('hidden');
                    $(this).next('staff').next('hr').addClass('hidden');
                }
            });
            
            $('.past_hx').each(function(){
                //console.log('od_date' + $(this).attr('od_date'));
                
                if($(this).find('.order_item').not('.hidden').length > 0){
                    $(this).removeClass('hidden');
                    $(this).next('staff').removeClass('hidden');
                    $(this).next('staff').next('hr').removeClass('hidden');
                }
                else
                {
                    $(this).addClass('hidden');   
                    $(this).next('staff').addClass('hidden');
                    $(this).next('staff').next('hr').addClass('hidden');
                }
            });
        }
        else{
            $('.order_item,.lab-item').removeClass('hidden');
            $('staff').removeClass('hidden');
            $('.past_hx').removeClass('hidden');
        }
    })

    $(document).on('click','#category-button-switch',function() {
        var classUp = $(this).hasClass('category-switch-up');
        if(classUp == true){
            $( "#category-content-switch" ).slideUp( "slow", function() {
                $("#category-button-switch").toggleClass('category-switch-up');
                $("#switch-up-down-name").empty().append('&nbsp;&nbsp;&nbsp;แสดง Tools&nbsp;&nbsp;&nbsp;');
                $("#category-direction-icon").removeClass('glyphicon-chevron-up');
                $("#category-direction-icon").addClass('glyphicon-chevron-down hidden-print');
            });
        } else {
            $( "#category-content-switch" ).slideDown( "slow", function() {
                $("#category-button-switch").toggleClass('category-switch-up');
                $("#switch-up-down-name").empty().append('&nbsp;&nbsp;&nbsp;ซ่อน Tools&nbsp;&nbsp;&nbsp;');
                $("#category-direction-icon").removeClass('glyphicon-chevron-down');
                $("#category-direction-icon").addClass('glyphicon-chevron-up hidden-print');
            });
        }
    });


    $(document).on('click','#switchModule .dropdown-menu li a',function() {

              $(this).parents(".dropdown").find('.btn').html($(this).text() + ' <span class="caret"></span>');
              $(this).parents(".dropdown").find('.btn').val($(this).data('value'));


              $('#modulePresent').load($(this).attr('url'),function (responseText, textStatus, req) {
                  if (textStatus == "error") {
                      $('#modulePresent').text('');
                      console.log('load status error');
                  }

                      $.getScript( "./include/ptHxScript.js").done(function( script, textStatus ) {
                        console.log('#switchModule ' + textStatus );
                        ptHxCalend();
                      })
                      .fail(function( jqxhr, settings, exception ) {
                        $( "div.log" ).text( "Triggered ajaxError handler." );
                      });

              })
              .ajaxError(function(event, xhr, options) {
                  console.log('ajaxError');
              });

    });
    
//} //End of reinitPageLink()
//reinitPageLink();

$(document).on('click','.list-group-item',function() {
        var currentId = $(this).attr('id');
        var currentURL = $(this).attr('cat_url');
        var str = currentId;
        var n = str.indexOf("-");
        var id = str.slice(n+1);
        catId = parseInt(id);

        console.log('catId = ' + catId);
        console.log('cat_url = ' + cat_url);

        $('#searchPt').removeClass('hidden');
        $('#widget2').html('').addClass('hidden');
        $('#cart-content-mini').addClass('hidden');
        $('#postedOrder').addClass('hidden');
        $('.alert-info').addClass('hidden');

        var tools = $("#catname-" + catId).attr('tools');
        var cat_url = $("#catname-" + catId).attr('cat_url');

        if(tools != 'cat_img_sel'){
            $('#tools_img_sel').attr('style','display: none;');
            $('#tools_of_cat').attr('style','display: block;');

            $('#toolCanvas').load(cat_url);
            console.log('show tools ' + cat_url);
        }
        else
        {
            $('#tools_img_sel').attr('style','display: table;');
            $('#tools_of_cat').attr('style','display: none;');

            catID = 24;
        }
    
        try{
            if(catID != 27 && catID != 29){
                $('#cart-content-mini').removeClass('hidden');
                $('#postedOrder').removeClass('hidden');
                $('.alert-info').removeClass('hidden');
            }

            listProductAjax(1,catId);
        }
        catch(err)
        {
            
        }
        reinitPageLink();
});

function productSearchAjax(n,str){
    console.log('search term ]' + str + '[');
    console.log($('#searchModal').length);
    
	$(".search-result").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url: 'include/productSearchAjax.php',
  		data:{page:n,strSearch:str},
  		type:'get',
  		success:function(data){
  			setTimeout(function(){
  				$(".search-result").empty().append(data).fadeIn(1000);
  				searchPagingLinkAjax(n,str);
                $('#searchModal').modal();
  			}, 500);
  		}
  	});
}

var frozenStockSearchAjax = function (n,str){
    console.log('search term ]' + str + '[');
    
	$(".search-result").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/frozenStockSearchAjax.php',
  		data:{page:n,strSearch:str},
  		type:'get',
  		success:function(data){
            if(data == 'notified'){
                console.log(data);
                return;
            }
            
  			setTimeout(function(){
  				$(".search-result").empty().append(data).fadeIn(1000);
  				//searchPagingLinkAjax(n,str);
                $('#searchModal').modal();
  			}, 500);
  		}
  	});
    
    
}

function searchPagingLinkAjax(n,str){
  	$.ajax({
  		url:'include/searchPagingLinkAjax.php',
  		data:{page:n,strSearch:str},
  		type:'get',
  		success:function(data){
  			$("#lastest-link-content").empty().append(data).fadeIn(1000);
  		}
  	});
}

function myProductAjax(n){
    console.log('myProductAjax(' + n + ')');
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/lastestProductWithAjax.php',
  		data:{page:n},
  		type:'get',
  		success:function(data){
  			setTimeout(function(){
  				$("#product-container").empty().append(data).fadeIn(1000);
  				$("#category-container").show();
  				pagingLinkAjax(n);
  			}, 500);
  		}
  	});

}

function pagingLinkAjax(n){
  	$.ajax({
  		url:'include/lastestPagingLinkAjax.php',
  		data:{page:n},
  		type:'get',
  		success:function(data){
            //# CREATE PAGES LINK FOR MULTIPLE PAGE DISPLAY
            //console.log('latestPagingLinkAjax.php rtn');
            console.log(data);
  			
            $("#lastest-link-content").empty().append(data).fadeIn(1000);
            reinitPageLink();
  		}
  	});
}

function listProductAjax(n,catId){
    //alert('listProductAjax(' + n + ',' + catID + ')')
    
    //if(catID != 27){
        $("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
    //}
    
  	$.ajax({
  		url: gRoot + 'include/listProductWithAjax.php',
  		data:{page:n,c:catId, tools:$("#catname-" + catId).attr('tools')},
  		type:'get',
  		success:function(data){  		
  			setTimeout(function(){
  				$("#product-container").empty().append(data).fadeIn(1000);
  				$("#category-container").show();
  				listPagingLinkAjax(n,catId,$("#catname-" + catId).attr('tools'));
                console.log($("#catname-" + catId).text() + ' [' + $("#catname-" + catId).attr('tools') + ']');
  			}, 500);
  		}
  	});
    
    console.log('listProductAjax ' + n + ' of ' + catId);
}
function listPagingLinkAjax(m,cat,tools){
    tools = typeof tools !== 'undefined' ? tools : 'cat_img_sel';
    
  	$.ajax({
  		url: gRoot + 'include/listProductPagingLinkAjax.php',
  		data:{page:m,c:cat,tools:tools},
  		type:'get',
  		success:function(data){
  			$("#lastest-link-content").empty().append(data).fadeIn(1000);
            
            reinitPageLink();
  		}
  	});
    
    
}



function miniCartAjax(productId, od_date){
    if(typeof(od_date) == 'undefined'){
        od_date = '';
    }
    
  	$.ajax({
  		url:'include/miniCartAjax.php',	//ร้องขอไปยังไฟล์ miniCartAjax.php
  		data:{
            productAddTominCart: productId,
            //copyDose: copyDose,
            order_date: od_date
        },	//ส่งรหัสสินค้าไป
  		type:'post',			//เลือก type เป็นแบบ get
  		success:function(data){
			//แสดงตะกร้าสินค้าที่ด้านขวามือของเว็บเพจ 
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  			//อัพเดทจำนวนของในตะกร้าที่เมนู
  			upDateNavCartNumber();
  			$('html, body').animate({
        		scrollTop: $("#cart-content-mini").offset().top-10
    		}, 1000);
            
    		
  		}
        
  	});
}



function upDateNavCartNumber(){
    console.log('updateNavCartNumber');
  	$.ajax({
  		url:'include/navCartNumAjax.php',
  		type:'get',
  		success:function(data){
  			$("#nav-cart-number").empty().append(data).fadeIn(1000);
  		}
  	});
}

function showProductDetailAjax(productId,catId){
	productId = parseInt(productId);
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/productDetailAjax.php',
  		data:{pdId:productId,cat:catId},
  		type:'get',
  		success:function(data){
  			$("#product-container").empty().append(data).fadeIn(1000);
  			$("#lastest-link-content").empty();
  			$("#category-container").hide();
    		$(window).scrollTop($('#product-container').offset().top);
  		}
  	});
}

function submitSearchByEnter(e)
{
	var keycode;
	if (window.event) {
		keycode = window.event.keyCode;
	} else if (e) {
		keycode = e.which;
	} else {
		return true;
	}
	if (keycode == 13){
   		$( "#search-product-button" ).trigger( "click" );
   		return false;
   	} else {
   		return true;
   	}
}