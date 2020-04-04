/*
Strip whitespace from the beginning and end of a string
Input : a string
*/
function trim(str)
{
	return str.replace(/^\s+|\s+$/g,'');
}

/*
Make sure that textBox only contain number
*/
function checkNumber(textBox)
{
	while (textBox.value.length > 0 && isNaN(textBox.value)) {
		textBox.value = textBox.value.substring(0, textBox.value.length - 1)
	}
	
	textBox.value = trim(textBox.value);
/*	if (textBox.value.length == 0) {
		textBox.value = 0;		
	} else {
		textBox.value = parseInt(textBox.value);
	}*/
}

/*
	Check if a form element is empty.
	If it is display an alert box and focus
	on the element
*/
function isEmpty(formElement, message) {
	formElement.value = trim(formElement.value);
	
	_isEmpty = false;
	if (formElement.value == '') {
		_isEmpty = true;
		alert(message);
		formElement.focus();
	}
	
	return _isEmpty;
}

/*
	Set one value in combo box as the selected value
*/
function setSelect(listElement, listValue)
{
	for (i=0; i < listElement.options.length; i++) {
		if (listElement.options[i].value == listValue)	{
			listElement.selectedIndex = i;
		}
	}	
}

function validateEmail(emailElementValue, message) { 

    var mailExpression = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    if(mailExpression.test(emailElementValue) || emailElementValue == 'site_staff'){
    	return true;
    } else {
    	alert(message);
    	return false;
    }
    
} 

/*
function validatePhone(phoneElementValue, message) { 

    var phoneExpression = /(\b[0]{1}?\d{2}|\b[0]{1}?[2]{1})[-.]?(\d{3}[-.]?\d{4}\b|\d{3}[-.]?\d{3}\b)/;
    if(phoneExpression.test(phoneElementValue)){
    	return true;
    } else {
    	alert(message);
    	return false;
    }
    
}
*/

function validatePhone(data, message) {
   var msg = 'โปรดกรอกหมายเลขโทรศัพท์ 10 หลัก ด้วยรูปแบบดังนี้ 08XXXXXXXX ไม่ต้องใส่เครื่องหมายขีด (-) วงเล็บหรือเว้นวรรค';
   s = new String(data);
    
   if(s == 'site_staff'){
      return true;
   }

   if ( s.length != 10)
   {
      alert(msg);
      return false;
   }

   for (i = 0; i < s.length; i++ ) {               
      if ( s.charCodeAt(i) < 48 || s.charCodeAt(i) > 57 ) {
         alert(msg);
         return false;
      } else {
         
         if ( ((i == 0) && (s.charCodeAt(i) != 48)) || ((i == 1) && (s.charCodeAt(i) != 56)) )
         {
            alert(msg);
            return false;
         }
      }
   }            
   return true;
}

function validateZipCode(zipElementValue, message) { 
    if(zipElementValue == 'site_staff'){
        return true;
    } 

    var zipCodeExpression = /\b[1-9]{1}?\d{3}[0]\b/;
    if(zipCodeExpression.test(zipElementValue)){
    	return true;
    } else {
    	alert(message);
    	return false;
    }
    
}

function validateUserName(userNameElementValue) { 

    var userNameCodeExpression = /^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/i;
    if(userNameCodeExpression.test(userNameElementValue)){
    	if(userNameElementValue.length<6){
    		alert('Username จะต้องมีอักษรอย่างน้อย 6 ตัวอักษร');
    		return false;
    	}
    	return true;
    } else {
    	alert('userName จะต้องมีทั้่งอักษรและตัวเลขผสมกัน');
    	return false;
    }
    
}