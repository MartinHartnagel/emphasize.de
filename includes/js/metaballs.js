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
      return Metaballs.distance(this.x, this.y, pos.x, pos.y) <= weight;
    },
    setText : function(text) {
      if (text == "") {
        text = "_";
      }
      this.text = text;
      this.update();
    },
    update : function() {
      if (this.element == null) {
        return;
      }
      $(this.element).html(this.inner());
      $(this.element).css("background-color", '#' + this.color);
    },
    setLink : function(link) {
      this.link = link;
      this.update();
    },
    setColor : function(color) {
      this.color = color;
      this.update();
    },
    inner : function() {
      var txt = this.text.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "")
          .replace(/</, "&lt;").replace(/>/, "&gt;");

      if (this.link == null) {
        return txt;
      } else {
        return '<a href="' + this.link + '" target="_blank">' + txt + '</a>';
      }
    },
    html : function() {

      return '<div id="' + this.id + '" class="ball drag" style="left:'
          + (this.x - this.weight) + 'px;top:' + (this.y - this.weight)
          + 'px;width:' + (this.weight * 2) + 'px;height:' + (this.weight * 2)
          + 'px;line-height:' + this.weight + 'px;background-color:#'
          + this.color + ';background-position: center ' + (73 + this.weight)
          + 'px;">' + this.inner() + '</div>';
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
 * @class Metaballs static class for displaying and arranging the available
 *        events.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Metaballs = {
  /** Flag set in demo mode to avoid user interaction with the animated demo. */
  demo : false,
  balls : new Object(),
  ballCount : 0,
  boxes : new Object(),
  boxCount : 0,
  edge : 50,
  mouseDownPos : null,
  selectedBall : null,
  div : null,
  drag : undefined,
  dragOffset : undefined,
  init : function(div) {
    this.div = div;
    if (!Dashboard.demo) {
      // dragging
      $(this.div).on("mousedown", ".drag", function(event) {
        Metaballs.drag = $(event.target);
        Metaballs.dragOffset = {
          left : Metaballs.drag.offset().left - event.pageX,
          top : Metaballs.drag.offset().top - event.pageY
        };
        event.preventDefault();
      });
      $(document.body).on("mouseup", function(event) {
        if (Metaballs.drag != undefined) {
          Metaballs.drag = undefined;
        }
      });
      $(document.body).on("mousemove", function(e) {
        if (Metaballs.drag != undefined) {
          var id = $(Metaballs.drag).attr('id');
          Metaballs.drag.offset({
            left : e.pageX + Metaballs.dragOffset.left,
            top : e.pageY + Metaballs.dragOffset.top
          });
          var b = Metaballs.balls[id];
          if (b != undefined) {
            b.x = Metaballs.drag.position().left + b.weight;
            b.y = Metaballs.drag.position().top + b.weight;
          }
        }
      });
    }
    // initial step
    this.step();
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
    var o = JSON.parse(json, reviver);
    this.balls = o.balls;
    this.boxes = o.boxes;
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
    var o = new Object();
    o.balls = this.balls;
    o.boxes = this.boxes;
    return JSON.stringify(o, replacer);
  },
  clicked : function(pos) {
    alert("clicked " + pos.x + ", " + pos.y);
  },

  dragged : function(from, to) {
    alert("dragged " + from.x + ", " + from.y + " - " + to.x + ", " + to.y);
  },

  detect : function(pos) {
    var hits = new Array();
    $.each(this.balls, function(index, ball) {
      if (ball.inside(pos)) {
        hits.push(ball);
      }
    });
    return hits;
  },
  addBox : function() {
    $(this.div).append(
        '<div id="b' + Math.random() + '" class="area drag" style="top: '
            + Math.floor(Math.random() * 75) + '%;left: '
            + Math.floor(Math.random() * 75) + '%;width: '
            + Math.floor(Math.random() * 50) + '%;height: '
            + Math.floor(Math.random() * 30 + 20) + '%;"></div>');
  },
  addBall : function() {
    var c = Math.random() * 256 * 256 * 256;
    var w = $(this.div).width();
    var h = $(this.div).height();
    var e = Ball();
    while (this.balls["i" + this.ballCount] != undefined) {
      this.ballCount++;
    }
    e.initialize("i" + this.ballCount, Math.floor(Math.random() * 30) + 30,
        Math.floor(Math.random() * w), Math.floor(Math.random() * h));
    e.setColor(this.dx(c));
    e.setText("Something");
    $(this.div).append(e.html());
    e.element = $('#' + e.id).get(0);
    this.balls[e.id] = e;
    this.ballCount++;
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
    $.each(this.balls, function(key, ball) {
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
    $.each(this.balls, function(key, ball) {
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
    $.each(this.balls, function(key, ball) {
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
    var velocity = 0;
    $.each(this.balls,
        function(key, ball) {
          var e = ball;
          var fl = undefined;
          var hits = 0;

          var inBox = false;
          $(".box").each(function(index) {
            var l = $(this).position().left;
            var t = $(this).position().top;
            var w = $(this).width();
            var h = $(this).height();

            var f = undefined;
            if (e.x - e.weight < l) {
              f = {
                w : 1,
                x : (l - (e.x - e.weight)),
                y : 0
              };
            }
            if (e.x + e.weight > l + w) {
              f = {
                w : 1,
                x : (l + w - (e.x + e.weight)),
                y : 0
              };
            }
            if (e.y - e.weight < t) {
              f = {
                w : 1,
                x : (f == undefined ? 0 : f.x),
                y : (t - (e.y - e.weight))
              };
            }
            if (e.y + e.weight > t + h) {
              f = {
                w : 1,
                x : (f == undefined ? 0 : f.x),
                y : (t + h - (e.y + e.weight))
              };
            }
            // normierung
            if (f != undefined) {
              var d = Metaballs.len(f.x, f.y);
              hits++;
              f.w = f.w / d;
              f.x = f.x / d;
              f.y = f.y / d;
              if (fl != undefined) {
                fl = Metaballs.mean(f, fl, hits);
              } else {
                fl = f;
              }
            } else {
              inBox = true;
            }
          });
          if (inBox) {
            // reset gravity-movement
            hits = 0;
            fl = undefined;
          }
          fl = Metaballs.collides(fl, e, hits);
          if (this.drag == undefined || $(this.drag).attr('id') != id) {
            if (fl != undefined
                && (Math.abs(fl.x) > 0.05 || Math.abs(fl.y) > 0.05)) {
              velocity += Math.abs(fl.x) + Math.abs(fl.y);
              e.x += fl.x;
              e.y += fl.y;
              $('#' + e.id).css({
                "left" : Math.round(e.x - e.weight) + "px",
                "top" : Math.round(e.y - e.weight) + "px"
              });
            }
          }
        });
    setTimeout("Metaballs.step()", Math.min(Math.max(
        Math.floor(100 / velocity), 5), 500));

  },
  mean : function(previous, current, n) {
    var weight = (previous.w * (n - 1) + current.w) / n;
    return {
      w : weight,
      x : (previous.x * previous.w * (n - 1) + current.x * current.w)
          / (n * weight),
      y : (previous.y * previous.w * (n - 1) + current.y * current.w)
          / (n * weight),
    };
  },
  collides : function(f, e, hits) {
    $.each(this.balls, function(key, c) {
      if (c != e) {
        var d = Metaballs.distance(c.x, c.y, e.x, e.y);
        if (d < c.weight + e.weight) {
          hits++;
          if (f == undefined) {
            f = Metaballs.flee(c.x, c.y, e.x, e.y);
          } else {
            f = Metaballs.mean(f, Metaballs.flee(c.x, c.y, e.x, e.y), hits);
          }
        }
      }
    });
    return f;
  },
  distance : function(ax, ay, bx, by) {
    return this.len(ax - bx, ay - by);
  },
  len : function(x, y) {
    return Math.sqrt(x * x + y * y);
  },
  flee : function(ax, ay, bx, by) {
    var dx = ax - bx;
    var dy = ay - by;
    var l = this.len(dx, dy);
    return {
      w : l,
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
