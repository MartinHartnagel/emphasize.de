
$.extend({createUploadIframe:function(id,uri)
{var frameId='jUploadFrame'+id;if(window.ActiveXObject){var io=document.createElement('<iframe id="'+frameId+'" name="'+frameId+'" />');if(typeof uri=='boolean'){io.src='javascript:false';}
else if(typeof uri=='string'){io.src=uri;}}
else{var io=document.createElement('iframe');io.id=frameId;io.name=frameId;}
io.style.position='absolute';io.style.top='-1000px';io.style.left='-1000px';document.body.appendChild(io);return io},createUploadForm:function(id,fileElementId)
{var formId='jUploadForm'+id;var fileId='jUploadFile'+id;var form=$('<form  action="" method="POST" name="'+formId+'" id="'+formId+'" enctype="multipart/form-data"></form>');var oldElement=$('#'+fileElementId);var newElement=$(oldElement).clone();$(oldElement).attr('id',fileId);$(oldElement).before(newElement);$(oldElement).appendTo(form);$(form).css('position','absolute');$(form).css('top','-1200px');$(form).css('left','-1200px');$(form).appendTo('body');return form;},ajaxFileUpload:function(s){s=$.extend({},$.ajaxSettings,s);var id=new Date().getTime()
var form=$.createUploadForm(id,s.fileElementId);var io=$.createUploadIframe(id,s.secureuri);var frameId='jUploadFrame'+id;var formId='jUploadForm'+id;if(s.global&&!$.active++)
{$.event.trigger("ajaxStart");}
var requestDone=false;var xml={}
if(s.global)
$.event.trigger("ajaxSend",[xml,s]);var uploadCallback=function(isTimeout)
{var io=document.getElementById(frameId);try
{if(io.contentWindow)
{xml.responseText=io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;xml.responseXML=io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;}else if(io.contentDocument)
{xml.responseText=io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;xml.responseXML=io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;}}catch(e)
{$.handleError(s,xml,null,e);}
if(xml||isTimeout=="timeout")
{requestDone=true;var status;try{status=isTimeout!="timeout"?"success":"error";if(status!="error")
{var data=$.uploadHttpData(xml,s.dataType);if(s.success)
s.success(data,status);if(s.global)
$.event.trigger("ajaxSuccess",[xml,s]);}else
$.handleError(s,xml,status);}catch(e)
{status="error";$.handleError(s,xml,status,e);}
if(s.global)
$.event.trigger("ajaxComplete",[xml,s]);if(s.global&&!--$.active)
$.event.trigger("ajaxStop");if(s.complete)
s.complete(xml,status);$(io).unbind()
setTimeout(function()
{try
{$(io).remove();$(form).remove();}catch(e)
{$.handleError(s,xml,null,e);}},100)
xml=null}}
if(s.timeout>0)
{setTimeout(function(){if(!requestDone)uploadCallback("timeout");},s.timeout);}
try
{var form=$('#'+formId);$(form).attr('action',s.url);$(form).attr('method','POST');$(form).attr('target',frameId);if(form.encoding)
{form.encoding='multipart/form-data';}
else
{form.enctype='multipart/form-data';}
$(form).submit();}catch(e)
{$.handleError(s,xml,null,e);}
if(window.attachEvent){document.getElementById(frameId).attachEvent('onload',uploadCallback);}
else{document.getElementById(frameId).addEventListener('load',uploadCallback,false);}
return{abort:function(){}};},uploadHttpData:function(r,type){var data=!type;var dataparsed=r.responseText.split("{");dataparsed=dataparsed[1].split("}");var ds=dataparsed[0].replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/&amp;/g,"&");data=type=="xml"||"{ "+ds+" }";if(type=="script")
$.globalEval(data);if(type=="json")
eval("data = "+data);if(type=="html")
$("<div>").html(data).evalScripts();return data;}})
function Tabletti(table){this.table=table;var currentEditsElement;var currentEdits=new Array();var grid;var record=false;var actions=new Array();var valid=true;var editColors;var demo=false;var self="Tabletti.prototype";var onEdited;var onPlaced;var onColor;var debug;var entryMaxLength=108;$("table.tabletti td").click(function(event){Tabletti.prototype.placeUser(this);});Tabletti.prototype.setEntryMaxLength=function(len){entryMaxLength=len;}
Tabletti.prototype.setDemo=function(flag){demo=flag;}
Tabletti.prototype.setSelf=function(s){self=s;}
Tabletti.prototype.setDebug=function(s){debug=s;}
Tabletti.prototype.setOnEdited=function(callback){onEdited=callback;}
Tabletti.prototype.setOnPlaced=function(callback){onPlaced=callback;}
Tabletti.prototype.setOnColor=function(callback){onColor=callback;}
function getGrid(){updateGrid();return grid;}
function invalidateGrid(){grid=undefined;}
function ensureGrid(x,y){while(grid.length<y+1){grid.push(new Array());}
for(var i=0;i<=y;i++){while(grid[i].length<x+1){grid[i].push(null);}}
return grid[y][x];}
function checkValidity(agrid){v=true;for(var y=0;y<agrid.length;y++){for(var x=0;x<agrid[y].length;x++){if(agrid[y][x]==null){v=false;}}}
return v;}
function fillGrid(){var v=true;grid=new Array();for(var y=0;y<table.rows.length;y++){var x=0;for(var a=0;a<table.rows[y].cells.length;a++){var td=table.rows[y].cells[a];while(ensureGrid(x,y)!=null){x++;}
td.logGrid_x=x;td.logGrid_y=y;for(var h=0;h<td.rowSpan;h++){for(var w=0;w<td.colSpan;w++){if(ensureGrid(x+w,y+h)!=null){if(debug!=undefined)debug("already set "+(y+h)+":"+(x+w));v=false;}
grid[y+h][x+w]=td;}}}}
var maxCols=1;for(var y=0;y<grid.length;y++){if(maxCols<grid[y].length){maxCols=grid[y].length}}
for(var y=0;y<grid.length;y++){ensureGrid(maxCols-1,y);}
return v;}
function updateGrid(){if(grid!==undefined){return;}
var v=true;v=v&&fillGrid();v=v&&checkValidity(grid);if(!v){repairTable(grid);v=true;v=v&&fillGrid();v=v&&checkValidity(grid);debug("repaired table "+v);}
valid=v;if(!v){Tabletti.prototype.showGrid();}}
function repairTable(agrid){for(var y=0;y<agrid.length;y++){for(var x=0;x<agrid[y].length;x++){if(agrid[y][x]==null){var l=table.rows[y].cells.length;var td=table.rows[y].insertCell(l);td.colSpan=1;td.rowSpan=1;td.bgColor="#aa0000";var text=document.createTextNode("fix("+x+","+y+")");td.appendChild(text);}}}}
this.isValid=function(){updateGrid();return valid;}
function watch(){if(demo){editColors=new Array();return;}
if(true){$("table.tabletti td").mousemove(function(event){Tabletti.prototype.showEdits(this);});if(currentEditsElement!=undefined){Tabletti.prototype.showEdits(currentEditsElement);}
editColors=new Array();}else{$("table.tabletti td").click(function(event){Tabletti.prototype.placeUser(this)});if(currentEditsElement!=undefined){hideEdits(currentEditsElement);}
if(onEdited!=undefined){onEdited();}
editColors=undefined;}}
function canDoVerticalGlue(element){var tdo=getTdo(element);var td=Tabletti.prototype.getTd(tdo.logGrid_x+tdo.colSpan,tdo.logGrid_y);if((td!=null)&&(td.logGrid_y==tdo.logGrid_y)&&(td.rowSpan==tdo.rowSpan)){return true;}
return false;}
Tabletti.prototype.verticalMerge=function(element){if(demo)return;var tdo=getTdo(element);var td=Tabletti.prototype.getTd(tdo.logGrid_x+tdo.colSpan,tdo.logGrid_y);if((td!=null)&&(td.logGrid_y==tdo.logGrid_y)&&(td.rowSpan==tdo.rowSpan)){if(record){if(actions.length>20)actions.pop();actions.push("if ("+self+".isValid())  "+self+".verticalMerge("+self+".getTd("+tdo.logGrid_x+","+tdo.logGrid_y+"));");}
invalidateGrid();tdo.colSpan+=td.colSpan;var tro=tdo.parentNode;tro.deleteCell(td.cellIndex);}
Tabletti.prototype.updateEdits();return true;}
function canDoHorizontalGlue(element){var tdo=getTdo(element);var td=Tabletti.prototype.getTd(tdo.logGrid_x,tdo.logGrid_y+tdo.rowSpan);if((td!=null)&&(td.logGrid_x==tdo.logGrid_x)&&(td.colSpan==tdo.colSpan)){return true;}
return false;}
Tabletti.prototype.horizontalMerge=function(element){if(demo)return;var tdo=getTdo(element);var td=Tabletti.prototype.getTd(tdo.logGrid_x,tdo.logGrid_y+tdo.rowSpan);if((td!=null)&&(td.logGrid_x==tdo.logGrid_x)&&(td.colSpan==tdo.colSpan)){if(record){if(actions.length>20)actions.pop();actions.push("if ("+self+".isValid()) "+self+".horizontalMerge("+self+".getTd("+tdo.logGrid_x+","+tdo.logGrid_y+"));");}
invalidateGrid();tdo.rowSpan+=td.rowSpan;var tr=td.parentNode;tr.deleteCell(td.cellIndex);}
Tabletti.prototype.updateEdits();return true;}
Tabletti.prototype.verticalSplit=function(element){if(demo)return;var tdo=getTdo(element);if(record){if(actions.length>20)actions.pop();actions.push("if ("+self+".isValid()) "+self+".verticalSplit("+self+".getTd("+tdo.logGrid_x+","+tdo.logGrid_y+"));");}
var td;if(tdo.colSpan>1){invalidateGrid();var tro=tdo.parentNode;td=tro.insertCell(tdo.cellIndex+1);td.colSpan=Math.floor(tdo.colSpan/2);tdo.colSpan-=td.colSpan;td.rowSpan=tdo.rowSpan;td.bgColor=tdo.bgColor;}else{var filled=undefined;for(var i=0;i<grid.length;i++){var tda=Tabletti.prototype.getTd(tdo.logGrid_x,i);if((tda!=tdo)&&(tda!=filled)){tda.colSpan++;filled=tda;}}
invalidateGrid();var tro=tdo.parentNode;td=tro.insertCell(tdo.cellIndex+1);td.colSpan=1;td.rowSpan=tdo.rowSpan;td.bgColor=tdo.bgColor;}
var text=document.createTextNode(cloneText(tdo));td.appendChild(text);Tabletti.prototype.updateEdits();return true;}
Tabletti.prototype.findElement=function(text){var place=null;for(var r=0;r<table.rows.length;r++){for(var c=0;c<table.rows[r].cells.length;c++){var txt=Tabletti.prototype.getEntry(table.rows[r].cells[c]);if(text==txt){place=table.rows[r].cells[c];return place;}}}
return place;}
function cloneText(tdo){hideEdits(currentEditsElement);var text=Tabletti.prototype.getEntry(tdo).replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/ \((\d+)\)$/,"");var c=2;while(Tabletti.prototype.findElement(text+" ("+c+")")!=null){c++;}
return text+" ("+c+")";}
function getTdo(element){updateGrid();var p=element;while(p.nodeName!="TD"){p=p.parentNode;}
return p;}
Tabletti.prototype.getTd=function(x,y){if(x==undefined||y==undefined){if(debug!=undefined)debug("error: getTd needs x and y arguments. Call:\n"+Tabletti.prototype.getTd.caller);}
updateGrid();if(y>=grid.length){return null;}
if(x>=grid[y].length){return null;}
return grid[y][x];}
Tabletti.prototype.horizontalSplit=function(element){if(demo)return;var tdo=getTdo(element);if(record){if(actions.length>20)actions.pop();actions.push("if ("+self+".isValid()) "+self+".horizontalSplit("+self+".getTd("+tdo.logGrid_x+","+tdo.logGrid_y+"));");}
var td;if(tdo.rowSpan>1){var ty=tdo.logGrid_y+Math.ceil(tdo.rowSpan/2);var after=0;for(var i=tdo.logGrid_x-1;i>0;i--){var tb=Tabletti.prototype.getTd(i,ty);if(tb.logGrid_y==ty){after=tb.cellIndex+1;break;}}
invalidateGrid();if(table.rows.length>ty){td=table.rows[ty].insertCell(after);}else if(after==0){var tr=table.insertRow(table.rows.length);td=tr.insertCell(0);}
td.colSpan=tdo.colSpan;td.rowSpan=Math.floor(tdo.rowSpan/2);tdo.rowSpan-=td.rowSpan;td.bgColor=tdo.bgColor;}else{var filled=undefined;for(var i=0;i<grid[tdo.logGrid_y].length;i++){var tda=Tabletti.prototype.getTd(i,tdo.logGrid_y);if((tda!=tdo)&&(tda!=filled)){tda.rowSpan++;filled=tda;}}
invalidateGrid();var tr=table.insertRow(tdo.logGrid_y+1);td=tr.insertCell(0);td.colSpan=tdo.colSpan;td.rowSpan=1;td.bgColor=tdo.bgColor;}
var text=document.createTextNode(cloneText(tdo));td.appendChild(text);Tabletti.prototype.updateEdits();return true;}
function imgTag(src,css,click,title){var img=$('<img src="'+src+'" title="'+title+'" style="'+css+'" />');$(img).click(click);return img;}
function addEvent(tag,event,action){if(event!=undefined){if(tag.style!=undefined){eval("tag."+event+"=function() { "+action.replace("this","tag")+";}");}else{var tagEventAttrib=document.createAttribute(event);tagEventAttrib.nodeValue=action;tag.setAttributeNode(tagEventAttrib);}}}
function classTag(type,clazz,event,action,event2,action2){var tag=document.createElement(type);if((clazz!=undefined)&&(clazz!=null)){var tagStyleAttrib=document.createAttribute("class");tagStyleAttrib.nodeValue=clazz;tag.setAttributeNode(tagStyleAttrib);}
addEvent(tag,event,action);addEvent(tag,event2,action2);return tag;}
Tabletti.prototype.findTdAt=function(px,py){var pos=$(table).offset();if((px>pos.left)&&(px<pos.left+$(table).outerWidth())&&(py>pos.top)&&(py<pos.top+$(table).outerHeight())){for(var y=0;y<table.rows.length;y++){for(var a=0;a<table.rows[y].cells.length;a++){var td=table.rows[y].cells[a];var pos=$(td).offset();if((px>pos.left)&&(px<pos.left+$(td).outerWidth())&&(py>pos.top)&&(py<pos.top+$(td).outerHeight())){return td;}}}}
return null;}
Tabletti.prototype.getRect=function(element){if(element==undefined){if(debug!=undefined)debug("error: getRect for undefined element ("+Tabletti.prototype.getRect.caller+")");return undefined;}
var rect={x:$(element).offset().left,y:$(element).offset().top,w:$(element).width(),h:$(element).height()};return rect;}
Tabletti.prototype.placeUser=function(element){if(onPlaced!=undefined){onPlaced(element);}}
Tabletti.prototype.showEdits=function(element){if(currentEditsElement!=element){if(currentEditsElement!=undefined){hideEdits(currentEditsElement);}
var r={x:$(element).position().left,y:$(element).position().top,w:$(element).width(),h:$(element).height()};var text=$(element).text().replace(/"/g,'&quot;');$(element).css("color",Tabletti.prototype.getColor(element));var p=8;var d=$('<div id="cellEdit" style="position:absolute;overflow:hidden;top:'+(r.y+p)+'px;left:'+(r.x+p)+'px;width:'+(r.w-p-p)+'px;height:'+(r.h-p-p)+'px;z-index:6;font-family:Arial, Verdana, Helvetica, sans-serif;font-size:11px;vertical-align:middle;text-align:center;color:black;"><table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" ><tr><td align="center" valign="middle" style="background-image:none;"><input id="cellReportable" type="checkbox" name="reportable" />berichtbar<br/><input id="cellEvent" style="text-align:center;font-size:14px;width:100%;" type="text" value="'+text+'" maxlength="'+entryMaxLength+'" onkeyup="'+self+'".checkHideEdits(event, this.value)" /><br /><input type="button" id="colorSelect" value="Farbe" /></td></tr></table></div>');$(d).appendTo(element);$("#colorSelect").click(function(e){Tabletti.prototype.updateEditColors();var s='<table style="padding:0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" ><tr><td colspan="'+editColors.length+'" height="'+(r.h-p-p-16)+'" style="border-radius:0px;-moz-border-radius:0px;"><img id="color" src="graphics/colors.png" align="middle" title="Farbe" style="margin:0px;padding:0px;width:100%;height:100%;cursor:crosshair;" /></td></tr><tr>';for(var i=0;i<editColors.length;i++){s+='<td height="16" style="background-image:none;background-color:'+editColors[i]+';border-radius:0px;-moz-border-radius:0px;padding:0px;cursor:crosshair;" onclick="'+self+'.colorizeEdited(\''+editColors[i]+'\')"><img src="graphics/void.png" style="width:100%;height:100%;" /></td>';}
s+='</tr></table>';var d2=$('<div id="cellColor" style="position:absolute;overflow:hidden;top:'+(r.y+p)+'px;left:'+(r.x+p)+'px;width:'+(r.w-p-p)+'px;height:'+(r.h-p-p)+'px;z-index:7;font-family:Arial, Verdana, Helvetica, sans-serif;font-size:11px;vertical-align:middle;text-align:center;color:black;">'+s+'</div>');$(d2).appendTo(element);$("#cellEdit").hide();$("#color").click(Tabletti.prototype.changeColor);})
if(!demo)$("#cellEvent").focus();var cellReportable=$("#cellReportable").get(0);cellReportable.checked=!$(element).hasClass("noreport");var vs={w:28,h:49};var hs={w:50,h:28};var vg={w:40,h:47};var hg={w:42,h:37};var std="position:absolute;overflow:hidden;"
currentEdits.push(imgTag("graphics/horizontalSplit.png","z-index:52;"+std+"left:"+(r.x-hs.w/2)+"px;top:"+(r.y+r.h/2-hs.h/2)+"px;width:"+hs.w+"px;height:"+hs.h+"px;cursor:pointer;",function(){Tabletti.prototype.horizontalSplit(this);},"horizontal schneiden"));currentEdits.push(imgTag("graphics/verticalSplit.png","z-index:53;"+std+"left:"+(r.x+r.w/2-vs.w/2)+"px;top:"+(r.y-vs.h/2-8)+"px;width:"+vs.w+"px;height:"+vs.h+"px;cursor:pointer;",function(){Tabletti.prototype.verticalSplit(this);},"vertikal schneiden"));if(canDoHorizontalGlue(element)){currentEdits.push(imgTag("graphics/horizontalGlue.png","z-index:54;"+std+"left:"+(r.x+r.w/2-8)+"px;top:"+(r.y+r.h-hg.h/2+4)+"px;width:"+hg.w+"px;height:"+hg.h+"px;cursor:pointer;",function(){Tabletti.prototype.horizontalMerge(this);},"horizontal kleben"));}
if(canDoVerticalGlue(element)){currentEdits.push(imgTag("graphics/verticalGlue.png","z-index:55;"+std+"left:"+(r.x+r.w-vg.w/2+2)+"px;top:"+(r.y+r.h/2-8)+"px;width:"+vg.w+"px;height:"+vg.h+"px;cursor:pointer;",function(){Tabletti.prototype.verticalMerge(this);},"vertikal kleben"));}
for(var i=0;i<currentEdits.length;i++){$(currentEdits[i]).appendTo(element);}
currentEditsElement=element;}}
Tabletti.prototype.checkHideEdits=function(event,str){var code;if(event!=null){code=event.keyCode?event.keyCode:event.charCode;}
if(event==null||code==13){if(currentEditsElement!=undefined){hideEdits(currentEditsElement);}}}
Tabletti.prototype.changeColor=function(evt){var img=$("#color").get(0);var pos=$("#color").offset();var x=evt.pageX-pos.left;var y=evt.pageY-pos.top;var w=$("#color").outerWidth();var h=$("#color").outerHeight();onColor(img.src,x/w,y/h,Tabletti.prototype.colorizeEdited);}
function hideEdits(element){if(element!=undefined&&element!=null){for(var i=0;i<currentEdits.length;i++){$(currentEdits[i]).detach();}
var text=$("#cellEvent").val();if(text!=undefined){$(element).css("color",'');if(!demo){var checked=$("#cellReportable").attr("checked");$(element).toggleClass('noreport',!checked);}
$('#cellEdit').detach();$('#cellColor').detach();if(!demo){var txt=text.replace(/^[\s\xA0]+/,"").replace(/[\s\xA0]+$/,"").replace(/</,"&lt;").replace(/>/,"&gt;");if(txt==""){txt="_";}
$(element).html(txt);}}}
currentEdits=new Array();currentEditsElement=undefined;}
Tabletti.prototype.updateEdits=function(){hideEdits(currentEditsElement);Tabletti.prototype.showEdits(currentEditsElement);watch();}
Tabletti.prototype.colorizeEdited=function(color){currentEditsElement.bgColor=color;$(currentEditsElement).css("color",color);$("#cellColor").detach();$("#cellEdit").show();}
Tabletti.prototype.showActions=function(){if(record){if(debug!=undefined)debug(actions.join("\n"));else alert(actions.join("\n"));}else record=true;}
Tabletti.prototype.showGrid=function(){var s="";var grid=getGrid();for(var y=0;y<grid.length;y++){for(var x=0;x<grid[y].length;x++){if(grid[y][x]!=null){s+="["+grid[y][x].logGrid_y+","+grid[y][x].cellIndex+"]";}else{s+="[n]";}}
s+="\n";}
if(debug!=undefined)debug("actions:\n"+actions.join("\n")+"\n\ngrid:\n"+s);else alert("grid:\n"+s);}
Tabletti.prototype.getEntry=function(element){if(element!=undefined&&element!=null){return $(element).text().replace(/^[\s\xA0]+/,"").replace(/[\s\xA0]+$/,"");}else{return null;}}
Tabletti.prototype.isReportable=function(element){return(!$(element).hasClass("noreport"));}
Tabletti.prototype.getColor=function(element){var td=getTdo(element);var bgColor=td.bgColor;if(bgColor.indexOf("rgb")!=-1){var parts=bgColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);var s="#";for(var i=1;i<=3;++i){var p=parseInt(parts[i]).toString(16);if(p.length==1)s+="0";s+=p;}
bgColor=s;}
return bgColor;}
Tabletti.prototype.updateEditColors=function(){for(var y=0;y<table.rows.length;y++){for(var a=0;a<table.rows[y].cells.length;a++){var td=table.rows[y].cells[a];var bgColor=$(td).css("background-color");if(bgColor.indexOf("rgb")!=-1){var parts=bgColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);var s="#";for(var i=1;i<=3;++i){var p=parseInt(parts[i]).toString(16);if(p.length==1)s+="0";s+=p;}
bgColor=s;}
if($.inArray(bgColor,editColors)==-1){editColors.push(bgColor);}}}}
Tabletti.prototype.getTableHtml=function(){if(currentEditsElement!=undefined){hideEdits(currentEditsElement);}
var s=$(table).html();var offset=s.toLowerCase().lastIndexOf("</th>");if(offset<0){offset=0;}
offset=s.toLowerCase().indexOf("<tr>",offset);var end=s.toLowerCase().lastIndexOf("</tr>")+5;return s.substring(offset,end).replace(/ colspan="1"/gi,"").replace(/ rowspan="1"/gi,"").replace(/<tr><\/tr>/gi,"");}
Tabletti.prototype.setTableHtml=function(tbody){invalidateGrid();$(table).html(tbody);updateGrid();watch();}}
var t;var currentPlace=undefined;var timelineDateTime=getDateTime(new Date());var timelineWidth=600;var timelineHour=120;var timelineMax=1440*3;var timelineLastEdit=0;var closeTimeEditor=undefined;var blindPlace=false;var queue=new Array();var aboves=new Object();var editReset=undefined;var currentHelpId=undefined;var addInfoText=undefined;var currentInfoText=undefined;var nowText="";function updateTimelineMax(days){timelineMax=1440*days;$('.tDiv').css("width",(timelineMax+timelineMax)+"px");$('.tNow').css("left",(timelineMax+timelineMax)+"px");updateTimeline();}
function dereferLink(url){$('iframe#derefer').detach();var derefer=$('<iframe id="derefer" src="'+domain+'util/derefer.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');$(derefer).appendTo($("body"));$('iframe#derefer').load(function(){var context=this.contentWindow.document;$("#dereferUrl",context).val(url);$("#dereferSubmit",context).click();window.setTimeout("$('iframe#derefer').detach();",5000);});}
var progressVersion=0;function progressStart(){progressVersion++;$("#doing").progressbar("option","value",0);$("#doing").fadeIn();}
function progressComplete(version){if(version<=progressVersion){$("#doing").fadeOut();}}
function initEmphasize(){$.datepicker.setDefaults($.datepicker.regional[lang]);$("#reportFrom, #reportTo").datepicker({defaultDate:"+1w",changeMonth:true,dateFormat:'yy-mm-dd',showButtonPanel:true,showOtherMonths:true,selectOtherMonths:true,showWeek:true,showAnim:'blind',onSelect:function(selectedDate){var option=this.id=="reportFrom"?"minDate":"maxDate",instance=$(this).data("datepicker"),date=$.datepicker.parseDate(instance.settings.dateFormat||$.datepicker._defaults.dateFormat,selectedDate,instance.settings);dates.not(this).datepicker("option",option,date);}});$("#doing").progressbar({value:59});$('.docu').hide();$("#doing").ajaxStart(function(){progressStart();}).ajaxComplete(function(){$("#doing").progressbar("option","value",100);window.setTimeout('progressComplete('+progressVersion+')',500);});t=new Tabletti($("#table").get(0));t.setSelf("t");t.setOnEdited(function(){if(t.isValid()){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"updateTbody","token":token,tbody:t.getTableHtml()}),success:function(msg){showStatus(false,"Feldaufteilung gespeichert");},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Speichern der Feldaufteilung fehlgeschlagen");}});}else{showStatus(true,"Speichern ungültiger Feldaufteilung abgewiesen");}});t.setOnPlaced(function(element){placeUser(element);});t.setDebug(debug);t.showActions();$(window).resize(function(){t.updateEdits();if(currentPlace!=undefined){moveAvatar(currentPlace,true);}
updateTimelineWidth();});$("#time").bind('click',function(e){var pos=$("#time").position();moveTimeTo(-pos.left+e.pageX-4);});var timeTip=function(e){var pos=$("#time").position();var x=-pos.left+e.pageX-4;var now=new Date();if((x<timelineMax+20+now.getMinutes())&&(x>20+now.getMinutes())){var mins=timelineMax+20+now.getMinutes()-x;var tip=new Date();tip.setTime(now.getTime()-mins*60000-now.getSeconds()*1000);var txt=rightTrimmed("00",tip.getHours())+":"+rightTrimmed("00",tip.getMinutes());$("#timetipText").html(txt);$("#timetip").css("left",(x-37)+"px");$("#timetip").show();}else{$("#timetip").hide();}};$("#time").mousemove(timeTip);$("#time").hover(timeTip,function(){$("#timetip").hide();});addInfoText=$("#info").val();$("#info").focus(function(e){currentColor=$("#info").css('color');if(currentColor=='#777777'||currentColor=='rgb(119, 119, 119)'){$("#info").css('color','#000000');if(currentInfoText!=undefined){$("#info").val(currentInfoText);}else{$("#info").val('');}}});$("#info").blur(function(e){if($("#info").val()==""){$("#info").css('color','#777777');if(currentInfoText!=undefined){$("#info").val(currentInfoText);}else{$("#info").val(addInfoText);}}});$("#info").keypress(function(e){if(e.which=='13'){var datetime=getTimelineDateTime();var entry={"type":1,"info":$("#info").val(),"datetime":datetime};$("#info").val("");queue.push(entry);processQueue();e.preventDefault();$("#info").blur();}});$("#shadow").bind('click',function(e){clickThrough(e.pageX,e.pageY);});$("#avatar").bind('click',function(e){clickThrough(e.pageX,e.pageY);});$("#shadow").load(function(){if(currentPlace!=undefined){moveAvatar(currentPlace,true);}});$("#user").load(function(){if(currentPlace!=undefined){moveAvatar(currentPlace,true);}});$('a').live('click',function(){url=$(this).attr("href");if(url.indexOf(":/")!=-1&&url.indexOf(domain)==-1){dereferLink(url);return false;}});$(window).unload(function(){var element=t.findElement("Pause");if(currentPlace!=element&&element!=undefined&&element!=null){placeUser(element);return false;}
return true;});nowText=$("#timeText").attr("value");updateTimelineMax(3);$('.timeline').find('*').attr('unselectable','on').css('MozUserSelect','none');}
function initReport(){$('.docu').hide();$('a').live('click',function(){url=$(this).attr("href");if(url.indexOf(":/")!=-1&&url.indexOf(domain)==-1){dereferLink(url);return false;}});}
function initPlaceUser(event){if(blindPlace){$("#blind").hide();blindPlace=false;}
var element=t.findElement(event);if(element!=undefined&&element!=null){currentPlace=element;moveAvatar(element,true);}else if(event.length>0){$("#blind").html('<div class="border" style="padding:15pt 0pt 0pt 0pt;height:35pt;">'+event+'</div>');$("#blind").css({"background":"url("+domain+"graphics/blind.png)","height":"50pt"});$("#blind").show();blindPlace=true;currentPlace=$("#blind").get(0);moveAvatar(currentPlace,true);}else{$("#avatar").hide();$("#shadow").hide();}}
function getDateTime(now){if(isNaN(now.getFullYear())){alert("invalid now time: "+now);}
return now.getFullYear()+"-"+rightTrimmed("00",(now.getMonth()+1))+"-"+rightTrimmed("00",now.getDate())+" "+rightTrimmed("00",now.getHours())+":"+rightTrimmed("00",now.getMinutes())+":"+rightTrimmed("00",now.getSeconds());}
function parseDateTime(str){var s=str.toLowerCase().replace(/^[\s\xA0]+/,"").replace(/[\s\xA0]+$/,"");var now=new Date();if(s.charAt(0)=="-"){s=s.substr(1).replace(/^[\s\xA0]+/,"");if(s.match("da?y?s?$")){var days=s.replace(/ *da?y?s?/g,"")*1;if(!isNaN(days)){now.setTime(now.getTime()-days*86400000);return now;}}else if(s.match("ho?u?r?s?$")){var hours=s.replace(/ *ho?u?r?s?/g,"")*1;if(!isNaN(hours)){now.setTime(now.getTime()-hours*3600000);return now;}}else if(s.match("mi?n?u?t?e?s?$")){var mins=s.replace(/ *mi?n?u?t?e?s?/g,"")*1;if(!isNaN(mins)){now.setTime(now.getTime()-mins*60000);return now;}}else if(s.match("se?c?o?n?d?s?$")){var secs=s.replace(/ *se?c?o?n?d?s?/g,"")*1;if(!isNaN(secs)){now.setTime(now.getTime()-secs*1000);return now;}}}else if(s==nowText){return now;}else if(s.indexOf(":")==2&&s.length==5){return new Date(now.getFullYear(),now.getMonth(),now.getDate(),s.substr(0,2)*1,s.substr(3,2)*1,0);}else if(s.indexOf(":")==2&&s.indexOf(":",3)==5&&s.length==8){return new Date(now.getFullYear(),now.getMonth(),now.getDate(),s.substr(0,2)*1,s.substr(3,2)*1,s.substr(6,2)*1);}else if(s.indexOf(":")==13&&s.length==16){return new Date(s.substr(0,4)*1,s.substr(5,2)*1-1,s.substr(8,2)*1,s.substr(11,2)*1,s.substr(14,2)*1,0);}else if(s.indexOf(":")==13&&s.indexOf(":",15)==16&&s.length==19){return new Date(s.substr(0,4)*1,s.substr(5,2)*1-1,s.substr(8,2)*1,s.substr(11,2)*1,s.substr(14,2)*1,s.substr(17,2)*1);}
return null;}
function getTimelineDateTime(){if(timelineDateTime!=undefined){return timelineDateTime;}else{return getDateTime(new Date());}}
function placeUser(element){if(blindPlace){$("#blind").hide();blindPlace=false;}
if(element!=currentPlace){var event=t.getEntry(element);if(event!=null){var color=t.getColor(element);if((""+color).length!=7){debug("color-failed of "+$(element).html()+" is "+color);alert("failed: color is "+color);}else{var datetime=getTimelineDateTime();var entry={"type":0,"event":event,"color":color,"datetime":datetime};queue.push(entry);processQueue();}}
currentPlace=element;}
moveAvatar(element);}
function processQueue(){while(queue.length>0){var entry=queue.pop();if(entry.type==0){if((""+entry.color).length!=7){alert("queue-entry color failure: "+entry.color);}
$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"trackEvent","token":token,"event":entry.event,"color":entry.color,"time":entry.datetime}),success:function(msg){showStatus(false,"Beginn der Zeiterfassung für&nbsp;\""+entry.event+"\"");updateTimeline();},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;später erneuter Versuch");queue.push(entry);}});}else if(entry.type==1){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"addInfo","token":token,"info":entry.info,"time":entry.datetime}),success:function(msg){showStatus(false,"Info hinzugefügt&nbsp;\""+entry.info+"\"");updateTimeline();},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;später erneuter Versuch");queue.push(entry);}});}}}
function moveAvatar(element,instantly){if(element==undefined||element==null){alert("error: moveAvatar for undefined element ("+moveAvatar.caller+")");return;}
var event=$(element).text();if(isLoggedIn()){if(event!=null&&event!=undefined){document.title=event.replace(/&lt;/,"<").replace(/&gt;/,">").replace(/&amp;/g,"&")+" - Emphasize ("+user+")";}else{document.title="Emphasize ("+user+")";}}
var r=t.getRect(element);var avatar=$("#avatar").get(0);var w=$("#user").width();var h=$("#user").height();var hx=Math.max(h/3,h-w/3);var ow=h*0.5;var g=3;avatar.step=0;avatar.from=$("#avatar").offset();avatar.to={left:Math.floor(r.x+r.w/2-w/2),top:Math.min(r.y+r.h-h,Math.floor(r.y+r.h/2-14-hx))};if(avatar.from.left==avatar.to.left&&avatar.from.top==avatar.to.top){return;}
$("#avatar").stop();$("#avatar").css({width:w+"px",height:h+"px"});$("#shadow").show();$("#avatar").show();if((instantly!=undefined)&&(instantly)){$("#avatar").css({left:avatar.to.left+"px",top:avatar.to.top+"px"});$("#shadow").css({left:(avatar.to.left-ow-g)+"px",top:(avatar.to.top-g)+"px"});}else{$("#avatar").animate({"step":100},{duration:"slow",step:function(step){var p=step/100.0;var _p=(100-step)/100.0;var elevate=(2500-(step-50)*(step-50))/2500;var ax=Math.floor(avatar.from.left*_p+avatar.to.left*p);var ay=Math.floor(avatar.from.top*_p+avatar.to.top*p-elevate*30);$("#avatar").css({left:ax+"px",top:ay+"px"});$("#shadow").css({left:Math.round(ax-ow-g-elevate*15)+"px",top:Math.round(ay-g+elevate*15)+"px"});}});}}
function rightTrimmed(digits,text){var s=digits+text;return s.substr(s.length-digits.length,digits.length);}
function debug(text){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"debug","txt":text+"\n---\n"+user})});}
function logout(){$.ajax({url:domain+"util/ajax.php",type:"POST",async:false,dataType:"html",data:({"do":"logout","token":token}),success:function(msg){location.replace(domain);},error:function(req,status,error){showStatus(true,"Abmelden ist fehlgeschlagen: "+error+" "+status);}});}
function updateReportTime(){document.report.time.value=getDateTime(new Date());return true;}
function callAboveClose(type){var obj=eval("aboves."+type);obj.close();}
function isAboveOpen(type){if(typeof(aboves[type])=="undefined"){return false;}
var obj=eval("aboves."+type);return(obj.close!=null);}
function showAbove(type,element,url,focusElement,w,h,embedded){var x=0;var y=0;var orientation=0;if((element!=undefined)&&(element!=null)){var pos=$(element).offset();var rect={x:pos.left,y:pos.top,w:$(element).outerWidth(),h:$(element).outerHeight()};if(w>0){x=rect.x+rect.w/2+1;orientation=1;}else{x=rect.x+w-1;orientation=3;}
if(x<0){x=0;}
if(h>0){y=rect.y+rect.h/2+1;orientation++;}else{y=rect.y+h-1;}
if(y<0){y=0;}}else{x=($(document).width()-w)/2;y=($(document).height()-h)/2;}
var above=$('<div id="above" class="above aboveOrientation'+orientation+'"></div>');$(above).css({'left':x+'px','top':y+'px','width':Math.abs(w)+'px','height':Math.abs(h)+'px'});$(above).appendTo($("body"));var closeAbove=$("<img id=\"closeAbove\" src=\"graphics/close.png\" title=\"Schließen\" style=\"position:absolute;z-index:1002;left:"+(x+Math.abs(w)+17)+"px;top:"+(y+3)+"px;width:16px;height:16px;cursor:pointer;\" />");$(closeAbove).appendTo($("body"));$(closeAbove).hide();var hide,show;if($("#showHelp").attr("src").match("help.png$")=="help.png"){hide=$("#showHelp").attr("alt");show=$("#showHelp").attr("title");}else{show=$("#showHelp").attr("alt");hide=$("#showHelp").attr("title");}
var aboveHelp=$("<img id=\"aboveHelp\" src=\"graphics/help.png\" title=\""+show+"\" alt=\""+hide+"\" style=\"position:absolute;z-index:1002;left:"+(x+Math.abs(w)+18-18)+"px;top:"+(y+2)+"px;width:16px;height:16px;cursor:help;\" />");$(aboveHelp).appendTo($("body"));$(aboveHelp).hide();$(aboveHelp).click(toggleAboveHelp);aboves[type]={close:function(){$(above).hide();$(closeAbove).hide();$(aboveHelp).hide();$(above).detach();$(closeAbove).detach();$(aboveHelp).detach();aboves[type].close=null;}};$(closeAbove).click(aboves[type].close);var completed=function(){$(above).find('.docu').hide();$(above).show();$(closeAbove).show();$(aboveHelp).show();if($("#showHelp").attr("src").match("help.png$")!="help.png"){toggleAboveHelp();}
if((focusElement!=undefined)&&(focusElement!=null)){window.setTimeout('$("'+focusElement+'").focus()',100);}};if((url!=undefined)&&(url!=null)){if(isLoggedIn()){$(above).load(url,{'token':token},completed);}else{$(above).load(url,{},completed);}}else if((embedded!=undefined)&&(embedded!=null)){$(above).html(embedded);completed();}
return false;}
function getHoursHtml(){var now=(new Date()).getTime();var s="";var days=Math.ceil(timelineMax/1440);for(var d=days-1;d>=-1;d--){for(var h=24;h>0;h--){var then=new Date();then.setTime(now-(d*24+h)*3600000);var day=$.datepicker.formatDate("D",then);if(then.getHours()<10){s+='<span class="tHour">&nbsp;&nbsp;'+day+" "+then.getHours()+':00</span>';}else{s+='<span class="tHour">'+day+" "+then.getHours()+':00</span>';}
if((d==-1)&&(h==23)){break;}}}
return s;}
function initTimeline(){updateLoop();moveTimeTo(timelineMax+timelineHour);}
function updateLoop(){if(isLoggedIn()){updateTimeline();}else{updateTimelineWidth();$("#tHours").html(getHoursHtml());}
window.setTimeout("updateLoop()",3*60*1000);}
function updateTimelineWidth(){var pos=$("#time").position();var from=-pos.left+timelineWidth/2;var to=from-timelineWidth/2;timelineWidth=$("#timeline").outerWidth();if(to<0)to=0;if(to>timelineMax+timelineHour-timelineWidth)to=timelineMax+timelineHour-timelineWidth;$('#time').stop();$("#time").css("left",(-to)+"px");setTimelineDateTime(timelineDateTime);}
function updateTimeline(){var now=new Date();var before=new Date();before.setTime(now.getTime()-timelineMax*60000);updateTimelineWidth()
$("#tHours").html(getHoursHtml());$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"getTimelineHistory","token":token,"now":getDateTime(now),"before":getDateTime(before)}),success:function(msg){if(msg=="logged-out."){logout();return;}
$("#tLine").css({'background-position':(-before.getMinutes())+'px 0px','left':(before.getMinutes()-60)+'px'});$("#tLine").html(msg);if(timelineLastEdit<(new Date()).getTime()-60000){moveTimeTo(timelineMax+timelineHour);document.report.to.value=getDateTime(new Date()).substr(0,10);}},error:function(req,status,error){showStatus(true,error+" "+status);}});processQueue();}
function moveTimeTo(x){if(x==undefined){alert("moveTimeTo(undefined)");return;}
var newTimelineDateTime;var now=new Date();if(x>timelineMax+20+now.getMinutes()){x=timelineMax+20+now.getMinutes();newTimelineDateTime=undefined;}else{if(x<20+now.getMinutes()){x=20+now.getMinutes();}
var mins=timelineMax+20+now.getMinutes()-x;var before=new Date();before.setTime(now.getTime()-mins*60000-now.getSeconds()*1000);newTimelineDateTime=getDateTime(before);}
moveTimeline(x);setTimelineDateTime(newTimelineDateTime);}
function moveTimeline(x){var pos=$("#time").position();var from=pos.left;if(Math.abs(x+(from-timelineWidth/2))>timelineWidth/3){var to=x-timelineWidth/2;if(to<0)to=0;if(to>timelineMax+timelineHour-timelineWidth)to=timelineMax+timelineHour-timelineWidth;$('#time').stop();$("#time").animate({left:(-to)+"px"},{"queue":"false","duration":"slow"});}}
function setTimelineDateTime(newTimelineDateTime){var change=false;if(timelineDateTime!=newTimelineDateTime){change=true;}
timelineDateTime=newTimelineDateTime;$("#now").stop();if(timelineDateTime!=undefined){if(isLoggedIn()){$("#timeText").attr("value",timelineDateTime);}else{change=false;}
var now=new Date();var before=parseDateTime(timelineDateTime);if(before==null){alert("parsing date-time failed: "+timelineDateTime);return;}
var x=timelineMax+20+now.getMinutes()-((now.getTime()-before.getTime())/60000);moveTimeline(x);$('#now').animate({left:(x-9)+"px"},{"queue":"false","duration":"slow","easing":"swing"});timelineLastEdit=(new Date()).getTime();}else{if(isLoggedIn()){$("#timeText").attr("value",nowText);}else{change=false;}
var now=new Date();var x=timelineMax+20+now.getMinutes();moveTimeline(x);$('#now').animate({left:(x-9)+"px"},{"queue":"false","duration":"slow","easing":"swing"});}
if(timelineDateTime!=undefined){if($("#editor").size()>0&&!$("#editor").hasClass("editTime")){$("#editor").addClass("editTime");if(closeTimeEditor==undefined){closeTimeEditor=$("<img id=\"closeTimeEditor\" src=\"graphics/close.png\" title=\"Zeiteditierung Beenden\" style=\"position:absolute;z-index:22;right:2px;top:2px;width:16px;height:16px;cursor:pointer;\" />");$(closeTimeEditor).appendTo($("#editor"));$(closeTimeEditor).click(function(){moveTimeTo(timelineMax+timelineHour);});}}}else{if($("#editor").size()>0){$("#editor").removeClass("editTime");if(closeTimeEditor!=undefined){closeTimeEditor.detach();closeTimeEditor=undefined;}}}
if(change){timePlaceUser();}}
function timePlaceUser(){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"getPlace","token":token,"time":getTimelineDateTime()}),success:function(msg){initPlaceUser(msg);},error:function(req,status,error){showStatus(true,error+" "+status);}});}
function showStatus(isError,text){if(isError){$("#status").html("<b style=\"font-color:red\">"+text.replace(/</g,"&lt;").replace(/>/g,"&gt;")+"</b>");}else{$("#status").html(text.replace(/</g,"&lt;").replace(/>/g,"&gt;"));}}
function createUser(){if(!isAboveOpen("register")){showAbove("register",$("#register").get(0),domain+'util/register.php?lang='+lang,"#registerName",460,280);}
return false;}
function createFeedback(){if(!isAboveOpen("feedback")){showAbove("feedback",$("#feedback").get(0),domain+'util/feedform.php?lang='+lang,"#feedMessage",-300,-162);}
return false;}
function submitFeedback(){$.ajax({type:"POST",url:domain+"util/feedback.php",data:({"type":$('#feedbackType').get(0).value,"message":$('#feedMessage').get(0).value,"user":user,"from":email,"lang":$('#feedbackLang').get(0).value}),success:function(msg){if(isAboveOpen("feedback")){callAboveClose("feedback");}
showStatus(false,"Feedback übermittelt");},error:function(req,status,error){showStatus(true,error+" "+status);}});return false;}
function checkTimeText(event,str){var code;if(event!=null){code=event.keyCode?event.keyCode:event.charCode;}
if(event==null||code==13){var p=parseDateTime(str);if(p!=null){if(p.getTime()>=new Date().getTime()){setTimelineDateTime(undefined);}else{if(p.getTime()<new Date().getTime()-timelineMax*60000){updateTimelineMax(Math.ceil((new Date().getTime()-p.getTime())/86400000));p.setTime(new Date().getTime()-timelineMax*60000);}
setTimelineDateTime(getDateTime(p));}}}}
function checkFeedbackType(){if($('#feedbackType').get(0).value=='none'){$('#feedbackSubmit').get(0).disabled=true;}else{$('#feedbackSubmit').get(0).disabled=false;}}
function switchLang(lang){if(isLoggedIn()){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"setLang","token":token,"lang":lang}),success:function(msg){location.replace(location.href);},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Setzen der Sprache fehlgeschlagen");}});}else{if(location.search!=""){location.replace(location.href.replace(/lang=.?.?/,"lang="+lang));}else{location.replace(location.href+"?lang="+lang);}}}
function toggleShowHelp(){if($("#showHelp").attr("src").match("help.png$")=="help.png"){swapAltTitle("#showHelp");$("#showHelp").attr("src",domain+"graphics/helping.png");var dw=$(document).width();var dh=$(document).height();$(".help").each(function(index){if($(this).is(':visible')){var id=$(this).attr('id');var p=$(this).offset();var pos=$(this).position();var w=$(this).outerWidth();var h=$(this).outerHeight();var l;if(p.left+w/2<dw/2){l=pos.left+w-14;}else{l=pos.left-10;}
var img=$('<img id="toggleHelp_'+id+'" src="graphics/help.png" width="24" height="23" onmouseover="displayHelp(\''+id+'\', 900)" style="z-index:900;position:absolute;left:'+l+'px;top:'+(pos.top+h/2-11)+'px;cursor:help;"/>');$(img).appendTo($(this).offsetParent());}});}else{if(currentHelpId!=undefined){hideHelp(currentHelpId,1900);currentHelpId=undefined;}
swapAltTitle("#showHelp");$("#showHelp").attr("src",domain+"graphics/help.png");$(".docu").hide();$(".help").each(function(index){var id=$(this).attr('id');$("#toggleHelp_"+id).detach();});}}
function toggleAboveHelp(){if($("#aboveHelp").attr("src").match("help.png$")=="help.png"){swapAltTitle("#aboveHelp");$("#aboveHelp").attr("src",domain+"graphics/helping.png");var dw=$(document).width();var dh=$(document).height();$(".above").find(".help").each(function(index){if($(this).is(':visible')){var id=$(this).attr('id');var p=$(this).offset();var pos=$(this).position();var w=$(this).outerWidth();var h=$(this).outerHeight();var l;if(p.left+w/2<dw/2){l=pos.left+w-14;}else{l=pos.left-10;}
var img=$('<img id="toggleHelp_'+id+'" src="graphics/help.png" width="24" height="23" onmouseover="displayHelp(\''+id+'\', 1900)" style="z-index:1900;position:absolute;left:'+l+'px;top:'+(pos.top+h/2-11)+'px;cursor:help;"/>');$(img).appendTo($(this).offsetParent());}});}else{if(currentHelpId!=undefined){hideHelp(currentHelpId,1900);currentHelpId=undefined;}
swapAltTitle("#aboveHelp");$("#aboveHelp").attr("src",domain+"graphics/help.png");$(".above").find(".docu").hide();$(".above").find(".help").each(function(index){var id=$(this).attr('id');$("#toggleHelp_"+id).detach();});}}
function displayHelp(id,z){if(currentHelpId!=undefined){hideHelp(currentHelpId,z);}
currentHelpId=id;var dw=$(document).width();var dh=$(document).height();var hp=$("#toggleHelp_"+id).offset();var dir;if(hp.left<dw/2){if(hp.top<dh/2){dir=3;}else{dir=2;}}else{if(hp.top<dh/2){dir=1;}else{dir=0;}}
$("#toggleHelp_"+id).attr("src",domain+"graphics/helping.png");$("#toggleHelp_"+id).css("z-index",z+2);var p=$("#toggleHelp_"+id).position();var w=$("#help_"+id).outerWidth();var h=$("#help_"+id).outerHeight();$("#help_"+id).removeClass("docuDir0");$("#help_"+id).removeClass("docuDir1");$("#help_"+id).removeClass("docuDir2");$("#help_"+id).removeClass("docuDir3");$("#help_"+id).addClass("docuDir"+dir);if(dir==0)$("#help_"+id).css({"left":(p.left-w+26)+"px","top":(p.top-h+26)+"px","z-index":z+1});else if(dir==1)$("#help_"+id).css({"left":(p.left-w+26)+"px","top":(p.top-2)+"px","z-index":z+1});else if(dir==2)$("#help_"+id).css({"left":(p.left-2)+"px","top":(p.top-h+26)+"px","z-index":z+1});else if(dir==3)$("#help_"+id).css({"left":(p.left-2)+"px","top":(p.top-2)+"px","z-index":z+1});$("#help_"+id).mouseout(function(){hideHelp(id,z);});$("#help_"+id).show();}
function hideHelp(id,z){$("#toggleHelp_"+id).attr("src",domain+"graphics/help.png");$("#toggleHelp_"+id).css("z-index",z);$("#help_"+id).hide();}
function createAbout(){if(!isAboveOpen("about")){showAbove("about",$("#about").get(0),domain+'util/about.php?lang='+lang,"#about",-460,-580);}
return false;}
function showConfig(){if(!isAboveOpen("settings")){showAbove("settings",$("#config").get(0),domain+'util/settings.php?lang='+lang,"#fileToUpload",460,286);}
return false;}
function showTemplates(){if(!isAboveOpen("templates")){showAbove("templates",$("#placeedit").get(0),domain+'util/templates.php?lang='+lang,"#fileToUpload",430,166);}
return false;}
function setAvatar(avatar){$.ajax({url:domain+"util/pawn.php",type:"POST",async:true,dataType:"html",data:({"do":"setAvatar","token":token,"avatar":avatar}),success:function(msg){if(isAboveOpen("settings")){callAboveClose("settings");}
$("#shadow").detach();$("#avatar").detach();$(msg).appendTo($("body"));if(currentPlace!=undefined){moveAvatar(currentPlace,true);}
$("#shadow").hide().load(function(){$("#shadow").fadeIn();if(currentPlace!=undefined){moveAvatar(currentPlace,true);}});$("#user").hide().load(function(){$("#user").fadeIn();if(currentPlace!=undefined){moveAvatar(currentPlace,true);}});},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;austausch der Spielfigur fehlgeschlagen");}});}
function setBaseHref(baseHref){$.ajax({url:domain+"util/ajax.php",type:"POST",async:true,dataType:"html",data:({"do":"setBaseHref","token":token,"baseHref":baseHref}),success:function(msg){if(isAboveOpen("settings")){callAboveClose("settings");}},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;fehlgeschlagen");}});return false;}
function deleteAvatar(avatar){$.ajax({url:domain+"util/pawn.php",type:"POST",async:true,dataType:"html",data:({"do":"deleteAvatar","token":token,"avatar":avatar}),success:function(msg){$('#avatars').load(domain+"util/avatars.php");},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Löschen der Spielfigur fehlgeschlagen");}});}
function isLoggedIn(){return(typeof(window["token"])!="undefined"&&typeof(window["user"])!="undefined"&&user!="");}
function swapAltTitle(el){var alt=$(el).attr("alt");$(el).attr({"alt":$(el).attr("title"),"title":alt});}
function createTempl(){var desc=$("#descTemplate").get(0).value;$("#createTemplate").attr("disabled",true);var tbody=t.getTableHtml();$.ajax({url:domain+"util/templates.php",type:"POST",async:true,dataType:"html",data:({"do":"createTemplate","token":token,"name":desc,"tbody":tbody}),success:function(msg){$("#templateSelectSpan").html(msg);showStatus(false,"Vorlage angelegt");$("#descTemplate").get(0).value="";},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Vorlage anlegen fehlgeschlagen");}});return false;}
function loadTempl(){var key=$("#templateSelect").get(0).value;if((key=="reset")&&(editReset!=undefined)){t.setTableHtml(editReset);}else{$.ajax({url:domain+"util/templates.php",type:"POST",async:true,dataType:"html",data:({"do":"loadTemplate","token":token,"key":key}),success:function(msg){t.setTableHtml(msg);showStatus(false,"Vorlage geladen");},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Vorlage laden fehlgeschlagen");}});}
return false;}
function removeTempl(){var key=$("#templateSelect").get(0).value;$("#removeTemplate").attr("disabled",true);$.ajax({url:domain+"util/templates.php",type:"POST",async:true,dataType:"html",data:({"do":"removeTemplate","token":token,"key":key}),success:function(msg){$("#templateSelectSpan").html(msg);showStatus(false,"Vorlage entfernt");},error:function(req,status,error){showStatus(true,error+" "+status+",&nbsp;Vorlage entfernen fehlgeschlagen");}});return false;}
function checkTemplateName(){if($("#descTemplate").get(0).value.replace(/^[\s\xA0]+/,"").replace(/[\s\xA0]+$/,"").length==0){$("#createTemplate").attr("disabled",true);}else{$("#createTemplate").attr("disabled",false);}}
function checkRemoveTemplate(offset){if($("#templateSelect").get(0).selectedIndex<offset){$("#removeTemplate").attr("disabled",true);}else{$("#removeTemplate").attr("disabled",false);}}
function tubeTutorial(yt){var player=$('<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/'+yt+'?hl=de&fs=1&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'+yt+'?hl='+lang+'&fs=1&autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>');if(isAboveOpen("tube")){callAboveClose("tube");}
showAbove("tube",null,null,null,640,385,player);}
function clickThrough(x,y){var element=t.findTdAt(x,y);if(element!=null){placeUser(element);}}
function exportTempl(){var key=$("#descTemplate").val();$('iframe#exporter').detach();var exporter=$('<iframe id="exporter" src="'+domain+'util/export.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');$(exporter).appendTo($("body"));$('iframe#exporter').load(function(){var context=this.contentWindow.document;$("#exporterToken",context).val(token);$("#exporterKey",context).val(key);$("#exporterTbody",context).val(t.getTableHtml());$("#exporterSubmit",context).click();window.setTimeout("$('iframe#exporter').detach();",5000);});}