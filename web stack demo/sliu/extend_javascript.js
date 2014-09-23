/*
	Extends some of javascript's core objects with my own methods.  
	You must include this file _before_ your page's javascript code.
	Calling these methods work just like any other javascript method.
	IE:
		SomeDateObj.to12HourStr();
		SomeDateObj.toYMDStr();
		SomeString.prettify();
*/

Date.prototype.to12HourStr = function(){
	var h		= this.getHours(),
		m		= this.getMinutes(),
		ampm	= '';
		
	ampm	= this.AMPM();
	h		= (h > 12) ? h % 12 : h;
	m		= this.prepend0(m);
	
	return h + ((m == '00') ? '' : ':' + m ) + ampm;
	}
	
	
Date.prototype.toYMDStr = function(){
	var y = this.getFullYear(),
		m = this.getMonth()+1,
		d = this.getDate();
		
	m = this.prepend0(m);
	d = this.prepend0(d);
		
	return y + '-' + m + '-' + d;
	}
	
	
Date.prototype.toHMStr = function(){
	var h = this.getHours(),
		m = this.getMinutes(),
		s = '00';
		
	h = this.prepend0(h);
	m = this.prepend0(m);
	
	return h + ':' + m + ':' + s;
	}
	
Date.prototype.toHMSStr = function(){
	var h		= this.getHours(),
		m		= this.getMinutes(),
		s		= this.getSeconds()
		ampm	= '';
		
	h		= (h > 12) ? h % 12 : h;
	m		= this.prepend0(m);
	s		= this.prepend0(s);
	ampm	= this.AMPM();
	
	return h + ':' + m + ':' + s + ampm;
	}
	
	
Date.prototype.prepend0 = function( num ){
	return (num < 10) ? '0' + num : num;
	}
	
Date.prototype.AMPM = function(){
	return (this.getHours() >= 12) ? 'pm' : 'am';
	}
	
	
Date.prototype.weekdaysBtwn = function( end ){
	var today		= this,
		numDays		= 0,
		weekdays	= ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		daysInWeek	= weekdays.length,
		msInDay		= 1000*60*60*24,
		array		= [];
		
	do {
		array.push( weekdays[today.getDay()] );
		today = new Date(today.getTime() + msInDay);
		numDays++;
		}while( (today <= end) && (numDays < daysInWeek) );

	return array;
	}
	
	
	
	
	
	
	
//adds my own methods to javascript String object - javascript's inheritance
String.prototype.prettify = function(){
	var string = this.replace(/_/g, ' ');
	return string.charAt(0).toUpperCase() + string.slice(1);
	}
	
	
String.prototype.isYMDStr = function(){
	return( this.match(/^[0-9]{1,4}-([1-9]|0[1-9]|1[0-2])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/) );
	}
	
	
String.prototype.isY2000MDStr = function(){
	return( this.isYMDStr() && this.match(/^[2-9][0-9]{3}-/) );
	}