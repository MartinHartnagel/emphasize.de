var balls=new Array();
setInterval("step()", 20);
var edge=50;

var mouseDownPos;
var selectedBall;

var Ball = function() {
 return {
  initialize: function(id, weight, x, y, z) {
    this.id=id;
    this.weight=weight;
    this.x=x;
    this.y=y;
    this.z=z;
  },
  inside: function(pos) {
    return distance(this.x, this.y, pos.x, pos.y) <= weight;
  }
}};

$("#box").live("mousedown", function(event) {
  mouseDownPos={
    x:event.pageX,
    y:event.pageY
  };
  
});

$("#box").live("mouseup", function(event) {
  if (mouseDownPos != undefined) {
    if (mouseDownPos.x == event.pageX && mouseDownPos.y == event.pageY) {
      clicked(mouseDownPos);
    } else {
      var nextPos={
        x:event.pageX,
        y:event.pageY
      };
      dragged(mouseDownPos, nextPos);
    }
    mouseDownPos = undefined;
  }
});

$("#box").live("mousemove", function(event) {
  if (mouseDownPos != undefined) {
    var nextPos={
      x:event.pageX,
      y:event.pageY
    };
    dragged(mouseDownPos, nextPos);
    mouseDownPos=nextPos;
  }
});

$("#box").live("mouseout", function(event) {
  mouseDownPos = undefined;
});

function clicked(pos) {
  alert("clicked "+pos.x+", "+pos.y);
}

function dragged(from, to) {
  alert("dragged "+from.x+", "+from.y+" - "+to.x+", "+to.y);
}

function detect(pos) {
  var hits=new Array();
  for(var i=0;i<balls.length;i++) {
    if (balls[i].inside(pos)) {
      hits.push(balls[i]);
    }
  }
  return hits;
}

function add() {
  var c=Math.random()*256*256*256;
  var w=$('#box').width();
  var h=$('#box').height();
  var e=Ball("i"+balls.length,Math.floor(Math.random()*30)+30,Math.floor(Math.random()*w),Math.floor(Math.random()*h));
  $("#box").append('<div id="'+e.id+'" class="ball" style="left:'+e.x+'px;top:'+e.y+'px;width:'+(e.weight*2)+'px;height:'+(e.weight*2)+'px;line-height:'+e.weight+'px;opacity:0.5;background:#'+dx(c)+';">Something</div>');
  balls.push(e);
}

function step() {
  var w=$('#box').width();
  var h=$('#box').height();
  
  for(var i=0;i<balls.length;i++) {
    var e=balls[i];
    if (e.x < edge) {
      e.x+=(edge-e.x)/edge;
    }
    if (e.x > w-edge) {
      e.x+=((w-edge)-e.x)/edge;
    }
    if (e.y < edge) {
      e.y+=(edge-e.y)/edge;
    }
    if (e.y > h-edge) {
      e.y+=((h-edge)-e.y)/edge;
    }
    
    var flee=collides(e);
    if (flee != undefined) {
      e.x+=flee.x;
      e.y+=flee.y;
      $('#'+e.id).css({"left": Math.round(e.x)+"px", "top": Math.round(e.y)+"px"});
    }
  }
}

function collides(e) {
  for(var i=0;i<balls.length;i++) {
    var c=balls[i];
    if (c != e) {
      var d=distance(c.x,c.y,e.x,e.y);
      if (d<c.weight+e.weight) {
        var f=flee(c.x,c.y,e.x,e.y);
        debug(c.id+" hits "+e.id + " with "+d+" as "+c.weight+"+"+e.weight+" flees " +f.x + ","+f.y);
        return f;
      }
    }
  }
  return undefined;
}

function distance(ax, ay, bx, by) {
  return len(ax-bx,ay-by);
}

function len(x, y) {
  return Math.sqrt(x*x+y*y);
}

function flee(ax, ay, bx, by) {
  var dx=ax-bx;
  var dy=ay-by;
  var l=len(dx,dy);
  return {
      x:-dx/l,
      y:-dy/l
    };
}

function dx (d) {
  max = Math.pow(16,8);
  if (d > max) {
    return;
  }
  if (d < 0) {
    return;
  }
  var z = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
    "A", "B", "C", "D", "E", "F");
  var x = "";
  var i = 1, v = d, r = 0;
  while (v > 15) {
    v = Math.floor(v / 16);
    i++;
  }
  v = d;
  for (j=i; j >= 1; j--) {
    x = x + z[Math.floor(v / Math.pow(16, j-1))];
    v = v - (Math.floor(v / Math.pow(16, j-1)) * Math.pow(16, j-1));
  }
  return x;
}

function debug(mesg) {
  $("#debug").html(mesg);
}

