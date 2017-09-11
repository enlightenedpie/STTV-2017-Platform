/*jshint strict:false */
var sections = {
	count : function(a) {return a.parent().parent().children().length;},
	add : function(x) {
		var b = x.parent().parent();
		var c = 0;
		if (b.has('*')) {
			c = this.count(x);
		}
		
		var lc = c-1;
		var newSe = b.children().last().clone();
		newSe.find('input,label,select').each(function() {
			var theAttr = (this.name) ? 'name' : 'for';
			$(this).attr(theAttr,$(this).attr(theAttr).replace('['+lc+']', '['+c+']'));
			$(this).val('');
		});
		newSe.appendTo(b);
	},
	remove : function(x) {
		x.parent().remove();
	},
	addSub : function(x) {
		var newSe = x.parent().clone();
		var c = x.parent().parent().children().length;
		var lc = c-1;
		newSe.find('input,label,select').each(function() {
			var theAttr = (this.name) ? 'name' : 'for';
			$(this).attr(theAttr,$(this).attr(theAttr).replace('[videos]['+lc+']', '[videos]['+c+']'));
			$(this).val('');
		});
		
		newSe.appendTo(x.parent().parent());
	},
	addSubSub : function(x) {
		var a = x.parent().clone();
		var b = x.parent().parent();
		var c = b.children().length;
		var lc = c-1;
		a.find('input,label,select').each(function() {
			var theAttr = (this.name) ? 'name' : 'for';
			$(this).attr(theAttr,$(this).attr(theAttr).replace('[sections]['+lc+']', '[sections]['+c+']'));
			$(this).val('');
		});
		
		a.appendTo(b);
	},
	removeSubSub : function(x) {
		x.parent().siblings('.course_subsubsec').empty();
	}
};
$(document).ready(function() {
	//sections.add();
});
$(document).on('click','.add-section',function(e){
	e.preventDefault();
	sections.add($(this));
});
$(document).on('click','.add-sub-section',function(e){
	e.preventDefault();
	sections.addSub($(this));
});
$(document).on('click','.add-sub-sub-section',function(e){
	e.preventDefault();
	sections.addSubSub($(this));
});
$(document).on('click','.remove-section',function(e){
	e.preventDefault();
	sections.remove($(this));
});
$('input.hassubsub').change(function() {
	if ($(this).is(':checked')){
		//alert('checked')
		sections.addSubSub($(this));
	} else {
		//alert('unchecked')
		sections.removeSubSub($(this));
	}
});