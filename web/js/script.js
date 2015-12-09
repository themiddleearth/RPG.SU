$(document).ready(function(){
	$("input:file").livequery("change",function(){
	if($(this).attr("class")==1)
	$('<input name="file[]" type="file" value="" class="1"><br>').prependTo("#files");
	$(this).attr("class","2");
	});
});