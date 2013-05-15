/**
 * @class Ball class for displaying and arranging one event.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Ball = function() {
  return {
    element : null,
    text : null,
    link : null,
    color : null,
    initialize : function(id, weight, x, y, z) {
      this.id = id;
      this.weight = weight;
      this.x = x;
      this.y = y;
      this.z = z;
    },
    inside : function(pos) {
      return distance(this.x, this.y, pos.x, pos.y) <= weight;
    },
    setText : function(text) {
      if (text == "") {
        text = "_";
      }
      this.text = text;
      update();
    },
    update : function() {
      var txt = this.text.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "")
          .replace(/</, "&lt;").replace(/>/, "&gt;");

      if (this.link != null) {
        $(element).html(txt);
      } else {
        $(element).html(
            '<a href="' + this.link + '" target="_blank">' + txt + '</a>');
      }
    },
    setLink : function(link) {
      this.link = link;
      update();
    },
    setColor : function(color) {
      this.color = color;
      update();
    }
  };
};

/**
 * @class Box class for arranging events in an area.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Box = function() {

};

/**
 * @class Metaballs class for displaying and arranging the available events.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Metaballs = {
  balls : new Array(),
  edge : 50,
  mouseDownPos : null,
  selectedBall : null,
  div : null,
  init : function(div) {
    this.div = div;
    setInterval("Metaballs.step()", 20);
    $(div).on("mousedown", function(event) {
      mouseDownPos = {
        x : event.pageX,
        y : event.pageY
      };
    });
    $(div).on("mouseup", function(event) {
      if (mouseDownPos != undefined) {
        if (mouseDownPos.x == event.pageX && mouseDownPos.y == event.pageY) {
          clicked(mouseDownPos);
        } else {
          var nextPos = {
            x : event.pageX,
            y : event.pageY
          };
          dragged(mouseDownPos, nextPos);
        }
        mouseDownPos = undefined;
      }
    });

    $(div).on("mousemove", function(event) {
      if (mouseDownPos != undefined) {
        var nextPos = {
          x : event.pageX,
          y : event.pageY
        };
        dragged(mouseDownPos, nextPos);
        mouseDownPos = nextPos;
      }
    });

    $(div).on("mouseout", function(event) {
      mouseDownPos = undefined;
    });
  },
  /**
   * @param json
   *          formatted string describing the states of the metaballs to load.
   */
  load : function(json) {
    var reviver = function(key, value) {
      var type;
      if (value && typeof value === 'object') {
        type = value.type;
        if (typeof type === 'string' && typeof window[type] === 'function') {
          return new (window[type])(value);
        }
      }
      return value;
    };
    this.balls = JSON.parse(json, reviver);
  },
  /**
   * @returns a json formatted string describing the current states of the
   *          metaballs.
   */
  save : function() {
    var replacer = function(key, value) {
      if (typeof value === 'number' && !isFinite(value)) {
        return String(value);
      }
      return value;
    };
    return JSON.stringify(balls, replacer);
  },
  clicked : function(pos) {
    alert("clicked " + pos.x + ", " + pos.y);
  },

  dragged : function(from, to) {
    alert("dragged " + from.x + ", " + from.y + " - " + to.x + ", " + to.y);
  },

  detect : function(pos) {
    var hits = new Array();
    $.each(balls, function(index, ball) {
      if (ball.inside(pos)) {
        hits.push(ball);
      }
    });
    return hits;
  },
  add : function() {
    var c = Math.random() * 256 * 256 * 256;
    var w = $(div).width();
    var h = $(div).height();
    var e = Ball("i" + balls.length, Math.floor(Math.random() * 30) + 30, Math
        .floor(Math.random() * w), Math.floor(Math.random() * h));
    $(div).append(
        '<div id="' + e.id + '" class="ball" style="left:' + e.x + 'px;top:'
            + e.y + 'px;width:' + (e.weight * 2) + 'px;height:'
            + (e.weight * 2) + 'px;line-height:' + e.weight
            + 'px;opacity:0.5;background:#' + dx(c) + ';">Something</div>');
    e.element = $('#' + e.id).get(0);
    balls.push(e);
  },
  /**
   * Finds the first ball with the given text.
   * 
   * @param text
   *          of the ball.
   * @returns the ball or <code>null</code> if not found.
   */
  find : function(text) {
    var found = null;
    $.each(balls, function(index, ball) {
      if (ball.text == text) {
        found = ball;
        return false;
      }
    });
    return found;
  },
  /**
   * Gets the ball represented by the given element.
   * 
   * @param element
   *          representing a ball.
   * @returns the ball or <code>null</code> if not found.
   */
  get : function(element) {
    var found = null;
    $.each(balls, function(index, ball) {
      if (ball.element = element) {
        found = ball;
        return false;
      }
    });
    return found;
  },
  /**
   * Gets a sorted array of all unique ball colors.
   * 
   * @returns sorted array of all unique ball colors.
   */
  getBallColors : function() {
    var colors = [];
    $.each(balls, function(index, ball) {
      if ($.inArray(ball.color, colors) == -1) {
        colors.push(ball.color);
      }
    });
    colors.sort();
    return colors;
  },
  /**
   * Iteration step for animation.
   */
  step : function() {
    var w = $(div).width();
    var h = $(div).height();

    $.each(balls, function(index, e) {
      if (e.x < edge) {
        e.x += (edge - e.x) / edge;
      }
      if (e.x > w - edge) {
        e.x += ((w - edge) - e.x) / edge;
      }
      if (e.y < edge) {
        e.y += (edge - e.y) / edge;
      }
      if (e.y > h - edge) {
        e.y += ((h - edge) - e.y) / edge;
      }

      var flee = collides(e);
      if (flee != undefined) {
        e.x += flee.x;
        e.y += flee.y;
        $('#' + e.id).css({
          "left" : Math.round(e.x) + "px",
          "top" : Math.round(e.y) + "px"
        });
      }
    });
  },
  collides : function(e) {
    var collision = undefined;
    $.each(balls, function(index, c) {
      if (c != e) {
        var d = distance(c.x, c.y, e.x, e.y);
        if (d < c.weight + e.weight) {
          var f = flee(c.x, c.y, e.x, e.y);
          debug(c.id + " hits " + e.id + " with " + d + " as " + c.weight + "+"
              + e.weight + " flees " + f.x + "," + f.y);
          collision = f;
          return false;
        }
      }
    });
    return collision;
  },
  distance : function(ax, ay, bx, by) {
    return len(ax - bx, ay - by);
  },
  len : function(x, y) {
    return Math.sqrt(x * x + y * y);
  },
  flee : function(ax, ay, bx, by) {
    var dx = ax - bx;
    var dy = ay - by;
    var l = len(dx, dy);
    return {
      x : -dx / l,
      y : -dy / l
    };
  },
  dx : function(d) {
    max = Math.pow(16, 8);
    if (d > max) {
      return;
    }
    if (d < 0) {
      return;
    }
    var z = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A",
        "B", "C", "D", "E", "F");
    var x = "";
    var i = 1, v = d;
    while (v > 15) {
      v = Math.floor(v / 16);
      i++;
    }
    v = d;
    for ( var j = i; j >= 1; j--) {
      x = x + z[Math.floor(v / Math.pow(16, j - 1))];
      v = v - (Math.floor(v / Math.pow(16, j - 1)) * Math.pow(16, j - 1));
    }
    return x;
  },
  debug : function(mesg) {
    // $("#debug").html(mesg);
  }
};
