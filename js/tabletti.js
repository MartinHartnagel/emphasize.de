/**
 as easy as a pill, handling a tablet:
 http://en.wiktionary.org/wiki/tabletti
*/
function Tabletti(table) {
  this.table=table;
  var currentEditsElement;
  var currentEdits=new Array();
  var grid;
  var record=false;
  var actions=new Array();
  var valid=true;
  var editColors;
  var demo=false;
  var self="Tabletti.prototype";
  var onEdited;
  var onPlaced;
  var onColor;
  var debug;
  var entryMaxLength=108;
  // initial placeUser-listener
  $("table.tabletti td").click(function(event) {
   Tabletti.prototype.placeUser(this);
  });
   
  Tabletti.prototype.setEntryMaxLength=function(len) {
    entryMaxLength=len;
  }   
  
  Tabletti.prototype.setDemo=function(flag) {
    demo=flag;
  }   
  
  Tabletti.prototype.setSelf=function(s) {
    self=s;
  }   
   
  Tabletti.prototype.setDebug=function(s) {
    debug=s;
  }
   
  Tabletti.prototype.setOnEdited=function(callback) {
    onEdited=callback;
  }
  
  Tabletti.prototype.setOnPlaced=function(callback) {
    onPlaced=callback;
  }
  
  Tabletti.prototype.setOnColor=function(callback) {
    onColor=callback;
  }
  
  function getGrid() {
    updateGrid();
    return grid;
  }
  
  function invalidateGrid() {
    grid=undefined;
  }
  
  function ensureGrid(x,y) {
    while(grid.length < y + 1) {
      grid.push(new Array());
    }
    for(var i=0; i <= y; i++) {
      while(grid[i].length < x + 1) {
        grid[i].push(null);
      }
    }
    return grid[y][x];
  }
  
  function checkValidity(agrid) {
    v=true;
    //check for validity
    for(var y=0; y<agrid.length; y++) {
      for(var x=0; x<agrid[y].length; x++) {
        if (agrid[y][x] == null) {
          v=false;
        }
      }
    }
    return v;
  }
  
  function fillGrid() {
    var v=true;
    grid=new Array();
    
    // fill grid with coords
    for(var y=0; y< table.rows.length; y++) {
      var x=0;
      for(var a=0; a< table.rows[y].cells.length;  a++) {
        var td=table.rows[y].cells[a];
        while(ensureGrid(x,y) != null) {
          x++;
        }
        td.logGrid_x=x;
        td.logGrid_y=y;
        for(var h=0; h < td.rowSpan; h++) {
          for(var w=0; w < td.colSpan; w++) {
            if (ensureGrid(x+w,y+h) != null) {
              if (debug != undefined) debug("already set " + (y+h)+":"+(x+w));
              v=false;
            }
            grid[y+h][x+w]=td;
          }
        }
      }
    }
    // fill dangling ends
    var maxCols=1;
    for(var y=0; y<grid.length; y++) {
      if (maxCols < grid[y].length) {
        maxCols=grid[y].length
      }
    }
    for(var y=0; y<grid.length; y++) {
      ensureGrid(maxCols-1,y);
    }
    return v;
  }
  
  function updateGrid() {
    if (grid!==undefined) {
      return;
    }
    var v=true;
    v=v && fillGrid();
    v=v && checkValidity(grid);
    
    if (!v) {
      repairTable(grid);
      v=true;
      v=v && fillGrid();
      v=v && checkValidity(grid);
      
      debug("repaired table " +v);
    }
        
    valid=v;
    if (!v) {
      Tabletti.prototype.showGrid();
    }
  }
  
  function repairTable(agrid) {
    for(var y=0; y<agrid.length; y++) {
      for(var x=0; x<agrid[y].length; x++) {
        if (agrid[y][x] == null) {
          var l=table.rows[y].cells.length;
          var td=table.rows[y].insertCell(l);
          td.colSpan=1;
          td.rowSpan=1;
          td.bgColor="#aa0000";
          var text = document.createTextNode("fix("+x+","+y+")");
          td.appendChild(text);
        }
      }
    }
  }
  
  this.isValid=function() {
    updateGrid();
    return valid;
  }
  
  function watch() {
    if (demo) {
      editColors=new Array();
      return;
    }
    if (true) { // TODO: smooth edit
      $("table.tabletti td").mousemove(function(event) {
       Tabletti.prototype.showEdits(this);
      }); 
      if (currentEditsElement!=undefined) {
        Tabletti.prototype.showEdits(currentEditsElement);
      }
      editColors=new Array();
    } else {    
      $("table.tabletti td").click(function(event) {
       Tabletti.prototype.placeUser(this)
      });
      if (currentEditsElement!=undefined) {
        hideEdits(currentEditsElement);
      }
      if (onEdited!=undefined) {
        onEdited();
      }
      editColors=undefined;
    }
  }
  
  function canDoVerticalGlue(element) {
    var tdo=getTdo(element);
    var td=Tabletti.prototype.getTd(tdo.logGrid_x + tdo.colSpan, tdo.logGrid_y);
    if ((td != null) && (td.logGrid_y == tdo.logGrid_y) && (td.rowSpan == tdo.rowSpan)) {
      return true;
    }
    return false;
  }

  Tabletti.prototype.verticalMerge=function(element) {
    if (demo) return;
    var tdo=getTdo(element);
    var td=Tabletti.prototype.getTd(tdo.logGrid_x + tdo.colSpan, tdo.logGrid_y);
    if ((td != null) && (td.logGrid_y == tdo.logGrid_y) && (td.rowSpan == tdo.rowSpan)) {
      if (record) {
        if (actions.length > 20) actions.pop();
        actions.push("if (" + self + ".isValid())  " + self + ".verticalMerge(" + self + ".getTd(" + tdo.logGrid_x + "," + tdo.logGrid_y + "));");
      }
      invalidateGrid();
      tdo.colSpan+=td.colSpan;
      var tro=tdo.parentNode;
      tro.deleteCell(td.cellIndex);
    }
    Tabletti.prototype.updateEdits();
    return true;
  }

  function canDoHorizontalGlue(element) {
    var tdo=getTdo(element);
    var td=Tabletti.prototype.getTd(tdo.logGrid_x, tdo.logGrid_y+tdo.rowSpan);
    if ((td != null) && (td.logGrid_x == tdo.logGrid_x) && (td.colSpan == tdo.colSpan)) {
      return true;
    }
    return false;
  }

  Tabletti.prototype.horizontalMerge=function(element) {
    if (demo) return;
    var tdo=getTdo(element);
    var td=Tabletti.prototype.getTd(tdo.logGrid_x, tdo.logGrid_y+tdo.rowSpan);
    if ((td != null) && (td.logGrid_x == tdo.logGrid_x) && (td.colSpan == tdo.colSpan)) {
      if (record) {
        if (actions.length > 20) actions.pop();
        actions.push("if (" + self + ".isValid()) " + self + ".horizontalMerge(" + self + ".getTd(" + tdo.logGrid_x + "," + tdo.logGrid_y + "));");
      }
      invalidateGrid();
      tdo.rowSpan+=td.rowSpan;
      var tr=td.parentNode;
      tr.deleteCell(td.cellIndex);
    }
    Tabletti.prototype.updateEdits();
    return true;
  }

  Tabletti.prototype.verticalSplit=function(element) {
    if (demo) return;
    var tdo=getTdo(element);
    if (record) {
      if (actions.length > 20) actions.pop();
      actions.push("if (" + self + ".isValid()) " + self + ".verticalSplit(" + self + ".getTd(" + tdo.logGrid_x + "," + tdo.logGrid_y + "));");
    }
    var td;
    if (tdo.colSpan > 1) {
      invalidateGrid();
      var tro=tdo.parentNode;
      td=tro.insertCell(tdo.cellIndex+1);
      td.colSpan=Math.floor(tdo.colSpan/2);
      tdo.colSpan-=td.colSpan;
      td.rowSpan=tdo.rowSpan;
      td.bgColor=tdo.bgColor;
    } else {
      // fill line left with extra colSpan, except for tdo
      var filled=undefined;
      for (var i=0; i < grid.length; i++) {
        var tda=Tabletti.prototype.getTd(tdo.logGrid_x, i);
        if ((tda != tdo) && (tda != filled)) {
          tda.colSpan++;
          filled=tda;
        }
      }
      invalidateGrid();
      var tro=tdo.parentNode;
      td=tro.insertCell(tdo.cellIndex+1);
      td.colSpan=1;
      td.rowSpan=tdo.rowSpan;
      td.bgColor=tdo.bgColor;
    }
    var text = document.createTextNode(cloneText(tdo));
    td.appendChild(text);
    Tabletti.prototype.updateEdits();
    return true;
  }

  Tabletti.prototype.findElement=function(text) {
    var place=null;
    for(var r=0; r < table.rows.length; r++) {
      for(var c=0; c < table.rows[r].cells.length; c++) {
        var txt=Tabletti.prototype.getEntry(table.rows[r].cells[c]);
        if (text == txt) {
          place=table.rows[r].cells[c];
          return place;
        }
      }
    }
    return place;
  }

  function cloneText(tdo) {
    hideEdits(currentEditsElement);
    var text=Tabletti.prototype.getEntry(tdo).replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/ \((\d+)\)$/, "");
    var c=2;
    while(Tabletti.prototype.findElement(text+" ("+c+")") != null) {
      c++;
    }
    return text+" ("+c+")";
  }

  function getTdo(element) {
    updateGrid();
    var p=element;
    while(p.nodeName != "TD") {
      p=p.parentNode;
    }
    return p;
  }

  Tabletti.prototype.getTd=function(x,y) {
    if (x== undefined || y == undefined) {
      if (debug != undefined) debug("error: getTd needs x and y arguments. Call:\n"+Tabletti.prototype.getTd.caller);
    }
    updateGrid();
    if (y >= grid.length) {
      return null;
    } 
    if (x >= grid[y].length) {
      return null;
    }
    return grid[y][x];
  }
 
  Tabletti.prototype.horizontalSplit=function(element) {
    if (demo) return;  
    var tdo=getTdo(element);
    if (record) {
      if (actions.length > 20) actions.pop();
      actions.push("if (" + self + ".isValid()) " + self + ".horizontalSplit(" + self + ".getTd(" + tdo.logGrid_x + "," + tdo.logGrid_y + "));");
    }
    var td;
    if (tdo.rowSpan > 1) {
      var ty=tdo.logGrid_y + Math.ceil(tdo.rowSpan/2);
      // find cell in target row ty before to insert after
      var after=0;
      for(var i=tdo.logGrid_x - 1; i > 0; i--) {
        var tb=Tabletti.prototype.getTd(i,ty);
        if (tb.logGrid_y==ty) {
          after=tb.cellIndex+1;
          break;
        }
      }
      invalidateGrid();
      if (table.rows.length > ty) {
        td=table.rows[ty].insertCell(after);
      } else if (after==0) {
        var tr=table.insertRow(table.rows.length);
        td=tr.insertCell(0);
      }
      td.colSpan=tdo.colSpan;
      td.rowSpan=Math.floor(tdo.rowSpan/2);
      tdo.rowSpan-=td.rowSpan;
      td.bgColor=tdo.bgColor;
    } else {
      // fill line above with extra rowSpan, except for tdo
      var filled=undefined;
      for (var i=0; i < grid[tdo.logGrid_y].length; i++) {
        var tda=Tabletti.prototype.getTd(i,tdo.logGrid_y);
        if ((tda != tdo) && (tda != filled)) {
          tda.rowSpan++;
          filled=tda;
        }
      }
      invalidateGrid();
      var tr=table.insertRow(tdo.logGrid_y + 1);
      td=tr.insertCell(0);
      td.colSpan=tdo.colSpan;
      td.rowSpan=1;
      td.bgColor=tdo.bgColor;
    }
    var text = document.createTextNode(cloneText(tdo));
    td.appendChild(text);
    Tabletti.prototype.updateEdits();
    return true;
  }
    
  function imgTag(src, css, click, title) {
    var img=$('<img src="'+src+'" title="'+title+'" style="'+css+'" />');
    $(img).click(click);
    return img;
  }

  function addEvent(tag, event, action) {
    if (event != undefined) {
      if (tag.style != undefined) { // IE special treatment
        eval("tag."+event+"=function() { "+action.replace("this", "tag")+";}");
      } else {
        var tagEventAttrib = document.createAttribute(event);
        tagEventAttrib.nodeValue = action;
        tag.setAttributeNode(tagEventAttrib);
      }
    }
  }

  function classTag(type, clazz, event, action, event2, action2) {
    var tag = document.createElement(type);
    if ((clazz != undefined) && (clazz != null)) {
      var tagStyleAttrib = document.createAttribute("class");
      tagStyleAttrib.nodeValue = clazz;
      tag.setAttributeNode(tagStyleAttrib);
    }
    addEvent(tag, event, action);
    addEvent(tag, event2, action2);
    return tag;
  }

  Tabletti.prototype.findTdAt=function(px, py) {
    var pos=$(table).offset();
    if ((px > pos.left) && (px < pos.left+$(table).outerWidth()) && (py > pos.top) && (py < pos.top+$(table).outerHeight())) {
      for(var y=0; y< table.rows.length; y++) {
        for(var a=0; a< table.rows[y].cells.length;  a++) {
          var td=table.rows[y].cells[a];
          var pos=$(td).offset();
          if ((px > pos.left) && (px < pos.left+$(td).outerWidth()) && (py > pos.top) && (py < pos.top+$(td).outerHeight())) {
            return td;
          }
        }
      }
    }
    return null;
  }

  Tabletti.prototype.getRect=function(element) {
    if (element == undefined) {
      if (debug != undefined) debug("error: getRect for undefined element ("+Tabletti.prototype.getRect.caller+")");
      return undefined;    
    }
    var rect={
      x:$(element).offset().left,
      y:$(element).offset().top,
      w:$(element).width(),
      h:$(element).height()
    };
    return rect;
  }

  Tabletti.prototype.placeUser=function(element) {
    if (onPlaced!=undefined) {
      onPlaced(element);
    }
  }

  Tabletti.prototype.showEdits=function(element) {
    if (currentEditsElement != element) {
      if (currentEditsElement != undefined) {
        hideEdits(currentEditsElement);
      }
      var r={
        x:$(element).position().left,
        y:$(element).position().top,
        w:$(element).width(),
        h:$(element).height()
      };
      // content-editing controls
      var text=$(element).text().replace(/"/g, '&quot;');
      $(element).css("color", Tabletti.prototype.getColor(element));
      var p=8;
      var d=$('<div id="cellEdit" style="position:absolute;overflow:hidden;top:'+(r.y+p)+'px;left:'+(r.x+p)+'px;width:'+(r.w-p-p)+'px;height:'+(r.h-p-p)+'px;z-index:6;font-family:Arial, Verdana, Helvetica, sans-serif;font-size:11px;vertical-align:middle;text-align:center;color:black;"><table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" ><tr><td align="center" valign="middle" style="background-image:none;"><input id="cellReportable" type="checkbox" name="reportable" /> <i18n key="tab1"><en>reportable</en><de>berichtbar</de><fr>rapportable</fr><es>notificables</es></i18n><br/><input id="cellEvent" style="text-align:center;font-size:14px;width:100%;" type="text" value="' + text+ '" maxlength="'+entryMaxLength+'" onkeyup="'+self+'".checkHideEdits(event, this.value)" /><br /><input type="button" id="colorSelect" value="<i18n key="tab2"><en>color</en><de>Farbe</de><fr>Couleur</fr><es>Color</es></i18n>" /></td></tr></table></div>');
      $(d).appendTo(element);
      $("#colorSelect").click(function(e) {
        Tabletti.prototype.updateEditColors();
        var s='<table style="padding:0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" ><tr><td colspan="'+editColors.length+'" height="'+(r.h-p-p-16)+'" style="border-radius:0px;-moz-border-radius:0px;"><img id="color" src="graphics/colors.png" align="middle" title="<i18n key="tab2"><en>color</en><de>Farbe</de><fr>Couleur</fr><es>Color</es></i18n>" style="margin:0px;padding:0px;width:100%;height:100%;cursor:crosshair;" /></td></tr><tr>';
        for(var i=0; i < editColors.length; i++) {
          s+='<td height="16" style="background-image:none;background-color:'+editColors[i]+';border-radius:0px;-moz-border-radius:0px;padding:0px;cursor:crosshair;" onclick="'+self+'.colorizeEdited(\''+editColors[i]+'\')"><img src="graphics/void.png" style="width:100%;height:100%;" /></td>';
        }
        s+='</tr></table>';
        var d2=$('<div id="cellColor" style="position:absolute;overflow:hidden;top:'+(r.y+p)+'px;left:'+(r.x+p)+'px;width:'+(r.w-p-p)+'px;height:'+(r.h-p-p)+'px;z-index:7;font-family:Arial, Verdana, Helvetica, sans-serif;font-size:11px;vertical-align:middle;text-align:center;color:black;">'+s+'</div>');
        $(d2).appendTo(element);
        $("#cellEdit").hide();
        $("#color").click(Tabletti.prototype.changeColor);
      })
      if (!demo) $("#cellEvent").focus();
      var cellReportable=$("#cellReportable").get(0);
      cellReportable.checked=!$(element).hasClass("noreport");
      // cutting controls
      var vs={w:28,h:49};
      var hs={w:50,h:28};
      var vg={w:40,h:47};
      var hg={w:42,h:37};
      var std="position:absolute;overflow:hidden;"
      currentEdits.push(imgTag("graphics/horizontalSplit.png", "z-index:52;" + std + "left:" + (r.x-hs.w/2) + "px;top:" + (r.y+r.h/2-hs.h/2) + "px;width:"+hs.w+"px;height:"+hs.h+"px;cursor:pointer;", function() {Tabletti.prototype.horizontalSplit(this);}, "<i18n key='tab3'><en>split horizontally</en><de>horizontal schneiden</de><fr>fissure horizontale</fr><es>división horizontal</es></i18n>"));
      currentEdits.push(imgTag("graphics/verticalSplit.png", "z-index:53;" + std + "left:" + (r.x+r.w/2-vs.w/2) + "px;top:" + (r.y-vs.h/2-8) + "px;width:"+vs.w+"px;height:"+vs.h+"px;cursor:pointer;", function() {Tabletti.prototype.verticalSplit(this);}, "<i18n key='tab4'><en>split vertically</en><de>vertikal schneiden</de><fr>fissure verticale</fr><es>división vertical</es></i18n>"));
      if (canDoHorizontalGlue(element)) {
        currentEdits.push(imgTag("graphics/horizontalGlue.png","z-index:54;" + std + "left:" + (r.x+r.w/2-8) + "px;top:" + (r.y+r.h-hg.h/2+4) + "px;width:"+hg.w+"px;height:"+hg.h+"px;cursor:pointer;", function() {Tabletti.prototype.horizontalMerge(this);}, "<i18n key='tab5'><en>glue horizontally</en><de>horizontal kleben</de><fr>colle horizontale</fr><es>cola horizontal</es></i18n>"));
      }
      if (canDoVerticalGlue(element)) {
        currentEdits.push(imgTag("graphics/verticalGlue.png", "z-index:55;" + std + "left:" + (r.x+r.w-vg.w/2+2) + "px;top:" + (r.y+r.h/2-8) + "px;width:"+vg.w+"px;height:"+vg.h+"px;cursor:pointer;", function() {Tabletti.prototype.verticalMerge(this);}, "<i18n key='tab6'><en>glue vertically</en><de>vertikal kleben</de><fr>colle verticale</fr><es>cola vertical</es></i18n>"));
      }

      for(var i=0; i<currentEdits.length; i++) {
        $(currentEdits[i]).appendTo(element);
      }
      currentEditsElement=element;
    }
  }
  
  Tabletti.prototype.checkHideEdits=function(event, str) {
    var code;
    if (event != null) {
      code=event.keyCode?event.keyCode:event.charCode;
    }
    if (event == null  || code==13) {
      if (currentEditsElement != undefined) {
        hideEdits(currentEditsElement);
      }
    }
  }

  Tabletti.prototype.changeColor=function(evt) {
    var img=$("#color").get(0);
    var pos=$("#color").offset();
    var x=evt.pageX-pos.left;
    var y=evt.pageY-pos.top;
    var w=$("#color").outerWidth();
    var h=$("#color").outerHeight();
    onColor(img.src, x/w, y/h, Tabletti.prototype.colorizeEdited);
  }

  function hideEdits(element) {
    if (element != undefined && element != null) {
      for(var i=0; i<currentEdits.length; i++) {
        $(currentEdits[i]).detach();
      }
      var text=$("#cellEvent").val();
      if (text != undefined) {
        $(element).css("color", '');
        if (!demo) {
          var checked=$("#cellReportable").attr("checked");
          $(element).toggleClass('noreport', !checked);
        }
        $('#cellEdit').detach();
        $('#cellColor').detach();
        if (!demo){
          var txt=text.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "").replace(/</, "&lt;").replace(/>/, "&gt;");
          if (txt == "") {
            txt="_";
          }
          $(element).html(txt);
          
        }
      }
    }
    currentEdits=new Array();
    currentEditsElement=undefined;
  }

  Tabletti.prototype.updateEdits=function() {
    hideEdits(currentEditsElement);
    Tabletti.prototype.showEdits(currentEditsElement);
    watch();
  }
        
  Tabletti.prototype.colorizeEdited=function(color) {
    currentEditsElement.bgColor=color;
    $(currentEditsElement).css("color", color);
    $("#cellColor").detach();
    $("#cellEdit").show();
  }  
    
  Tabletti.prototype.showActions=function() {
    if (record) {
      if (debug != undefined) debug(actions.join("\n"));
      else alert(actions.join("\n"));
    } else record=true;
  }

  Tabletti.prototype.showGrid=function() {
      var s="";
      var grid=getGrid();
      for(var y=0; y<grid.length; y++) {
        for(var x=0; x<grid[y].length; x++) {
          if (grid[y][x] != null) {
            s+="[" + grid[y][x].logGrid_y+","+grid[y][x].cellIndex + "]";
          } else {
            s+="[n]";
          }
        }
        s+="\n";
      }  
      if (debug != undefined) debug("actions:\n"+actions.join("\n")+"\n\ngrid:\n"+s);
      else alert("grid:\n"+s);
  }

  Tabletti.prototype.getEntry=function(element) {
      if (element != undefined && element != null) {
        return $(element).text().replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "");
      } else {
        return null;
      }
  }
  
  Tabletti.prototype.isReportable=function(element) {
      return (!$(element).hasClass("noreport"));
  }

  Tabletti.prototype.getColor=function(element) {
      var td=getTdo(element);
      var bgColor=td.bgColor;
      if (bgColor.indexOf("rgb") != -1) {
        var parts = bgColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        var s="#";
        for (var i = 1; i <= 3; ++i) {
            var p = parseInt(parts[i]).toString(16);
            if (p.length == 1) s+="0";
            s+=p;
        }
        bgColor=s;
      }
      return bgColor;
  }

  Tabletti.prototype.updateEditColors=function() {
      for(var y=0; y< table.rows.length; y++) {
        for(var a=0; a< table.rows[y].cells.length;  a++) {
          var td=table.rows[y].cells[a];
          var bgColor=$(td).css("background-color");
          if (bgColor.indexOf("rgb") != -1) {
            var parts = bgColor.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
            var s="#";
            for (var i = 1; i <= 3; ++i) {
                var p = parseInt(parts[i]).toString(16);
                if (p.length == 1) s+="0";
                s+=p;
            }
            bgColor=s;
          }
          if ($.inArray(bgColor, editColors) == -1) {
            editColors.push(bgColor);
          }
        }
      }
  }

  Tabletti.prototype.getTableHtml=function() {
      if (currentEditsElement!=undefined) {
        hideEdits(currentEditsElement);
      }
      var s=$(table).html();
      var offset=s.toLowerCase().lastIndexOf("</th>");
      if (offset < 0) {
        offset=0;
      }
      offset=s.toLowerCase().indexOf("<tr>", offset);
      var end=s.toLowerCase().lastIndexOf("</tr>") + 5;
      return s.substring(offset, end).replace(/ colspan="1"/gi, "").replace(/ rowspan="1"/gi, "").replace(/<tr><\/tr>/gi, "");
  }
  
  Tabletti.prototype.setTableHtml=function(tbody) {
    invalidateGrid();
    $(table).html(tbody);
    updateGrid();
    watch();
  }
}

