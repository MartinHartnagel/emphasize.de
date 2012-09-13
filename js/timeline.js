/**
 * @class Timeline class for displaying of tracked events and infos in time.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Timeline = {
  /**
   * Zoom-factor, equal to the width of pixels of an hour.
   */
  zoom : 120,
  /**
   * Currently render offset-time in milliseconds.
   */
  from : 0,
  /**
   * Currently render end-time in milliseconds.
   */
  to : 0,
  /**
   * Current time editing for in milliseconds or <code>null</code> if "now".
   */
  cursor : null,
  /**
   * TODO last edited merken für auto-jetzt zurücksetzen.
   */
  lastTimeEdit : 0,
  /**
   * Time sorted set of eventss currently looked at.
   */
  events : new TimeSortedSet(),
  /**
   * Time sorted set of infos currently looked at.
   */
  infos : new TimeSortedSet(),
  /**
   * Initializes the timeline object.
   */
  init : function() {
    $("#time").live('click', function(e) {
      if (isLoggedIn()) {
        var pos = $("#time").offset();
        var x = Math.ceil(e.pageX - pos.left - 1);
        var time = Timeline.getTimeAt(x);
        Timeline.setCursor(time);
      }
    });
    var timeTip = function(e) {
      var pos = $("#time").offset();
      var x = Math.ceil(e.pageX - pos.left - 1);
      var time = Timeline.getTimeAt(x);
      var tip = new Date();
      tip.setTime(time);
      var txt = rightTrimmed("00", tip.getHours()) + ":"
          + rightTrimmed("00", tip.getMinutes());
      $("#timetipText").html(txt);
      $("#timetip").css("left", (x - 37) + "px");
      $("#timetip").show();
    };
    $("#time").live("mousemove", timeTip);
    $("#time").live("hover", timeTip, function() {
      $("#timetip").hide();
    });
  },
  /**
   * Sets the zoom-factor, equal to the width of pixels of an hour.
   * 
   * @param zoom
   *          zoom-factor, equal to the width of pixels of an hour.
   */
  setZoom : function(zoom) {
    this.zoom = zoom;
  },
  /**
   * Returns the time in milliseconds currently represented at the x-position in
   * the rendered timeline div.
   * 
   * @param x
   *          x-position in the timeline-div.
   * @returns {Number} time in milliseconds corresponding to the x-position.
   */
  getTimeAt : function(x) {
    return Math.floor(this.from + x * 3600000 / this.zoom);
  },
  /**
   * Returns the x-position in the timeline-div currently representing the time
   * in milliseconds in the rendered timeline div.
   * 
   * @param time
   *          in milliseconds to get the representing x in the timeline-div in.
   * @returns {Number} x-position in the timeline-div.
   */
  getX : function(time) {
    if (time == null) {
      time = (new Date()).getTime();
    }
    return Math.floor((time - this.from) * this.zoom / 3600000);
  },
  /**
   * Returns the pixel-width currently rendered in the timeline-div.
   * 
   * @returns {Number} pixel-width currently rendered in the timeline-div.
   */
  getWidth : function() {
    return Math.floor((this.to - this.from) * this.zoom / 3600000);
  },
  /**
   * Renders the timeline contents.
   * 
   * @param from
   *          time in milliseconds, possibly adjusted to match the visible width
   *          of the timeline.
   * @param to
   *          time in milliseconds
   * @returns {String} html to be placed in the timeline-div.
   */
  render : function(from, to) {
    if (from >= to || !$("#timeline").is(':visible')) {
      return null;
    }
    this.to = to;
    var minDelta = Math.ceil($("#timeline").width() * 3600000 / this.zoom);
    if (to - from < minDelta) {
      from = to - minDelta;
    }
    this.from = from;
    var s = '';
    s += '<div id="time" class="tDiv" style="width: ' + this.getWidth()
        + 'px;">';
    s += '<div id="timetip" class="tTip">';
    s += '<span id="timetipText"></span>';
    s += '</div>\n';
    var then = new Date();
    then.setTime(from);
    then.setMinutes(0);
    then.setSeconds(0);
    then.setMilliseconds(0);
    var offset = Math.floor((from - then.getTime()) * this.zoom / 3600000);
    s += '<div class="tHours">\n';
    var sep = '<div class="tSeparators">\n';

    var c = 0;
    for ( var t = from; t <= to; t += 3600000) {
      then.setTime(t);
      var day = $.datepicker.formatDate("D", then);
      var left = this.getX(then) - offset;
      c += this.zoom;
      s += '\n<div style="left:' + left + 'px;">';
      if (then.getHours() < 10) {
        s += day + " 0" + then.getHours();
      } else {
        s += day + " " + then.getHours();
      }
      s += ':00</div>';
      sep += '\n<div style="left:' + left + 'px;width:'
          + Math.floor((this.zoom - 2) / 2) + 'px;" />';
    }
    s += '</div>';
    s += sep + '</div>';
    s += '<div id="tLine" class="tLine" unselectable="on">';
    // events
    var current = this.events.getAt(from);
    if (current == null) {
      current = this.events.getAfter(from);
    }
    while (current != null) {
      var duration;
      if (current.to != null) {
        duration = current.to - current.from;
      } else {
        duration = ((new Date()).getTime()) - current.from;
      }
      s += '\n<div class="box" style="left:'
          + Math.floor((current.from - from) * this.zoom / 3600000)
          + 'px;width:'
          + Math.floor(duration * this.zoom / 3600000)
          + 'px"><div title="'
          + current.object.event.replace(/"/g, '&quot;')
          + '" style="background-color:'
          + current.object.color
          + ';"></div><img src="'
          + domain
          + '/graphics/seperator.png" width="15" height="12" class="tsep"></div>';
      current = this.events.getAfter(current.from);
    }
    // infos
    current = this.infos.getAt(from);
    if (current == null) {
      current = this.infos.getAfter(from);
    }
    while (current != null) {
      var a = new Date(current.from);
      s += '\n<img src="' + domain + '/graphics/info.png" title="'
          + (a.getHours() < 10 ? '0' : '') + a.getHours() + ':'
          + (a.getMinutes() < 10 ? '0' : '') + a.getMinutes() + ' '
          + current.object.replace(/"/g, '&quot;') + '" style="left:'
          + Math.floor((current.from - from) * this.zoom / 3600000)
          + 'px;" class="ti" />';
      current = this.events.getAfter(current.from);
    }
    s += '\n</div>';
    s += '<div id="now" class="tNow">';
    s += '<img id="nowimg" src="graphics/now.png" title="';
    s += '<i18n key="tab43"><en>now</en><de>jetzt</de><fr>maintenant</fr><es>ahora</es></i18n>';
    s += '" width="19" height="58" class="help" />';
    s += '<div id="help_nowimg" class="docu"';
    s += 'style="width: 180px; height: 18px;">';
    s += '<i18n key="tab44"> <en>Editing time pointer.</en> <de>Editierzeit-Zeiger.</de><fr>Pointeur du temps d\'édition.</fr> <es>Puntero del tiempo edición.</es></i18n>';
    s += '</div>';
    s += '</div>';
    s += '</div>';
    $("#timeline").html(s);
    return s;
  },
  /**
   * TODO
   * 
   * @param time
   */
  setCursor : function(time) {
    var change = false;
    if (isLoggedIn() && this.cursor != time) {
      change = true;
    }
    this.cursor = time;
    if (time == null) {
      time = (new Date()).getTime();
      this.setText('<i18n ref="tab43" />');
      if ($("#closeTimeEditor").is(':visible')) {
        // editor normal anzeigen
        $("#closeTimeEditor").fadeOut();
      }
    } else {
      var date = new Date();
      date.setTime(time);
      this.setText(this.getDateTime(date));
      if (!$("#closeTimeEditor").is(':visible')) {
        // "X" anzeigen
        $("#closeTimeEditor").fadeIn();
      }
    }

    if (time > this.from && time < this.to) {
      var x = this.getX(time);
      var w = $("#time").width();
      var m = $('#timeline').width();
      $('#time').stop();
      $("#time").animate({
        "left" : Math.floor(Math.min(Math.max(-x + m / 2, -w + m), 0)) + "px"
      }, {
        "queue" : false,
        "duration" : "slow"
      });

      $("#now").stop();
      $('#now').animate({
        "left" : Math.floor(x - 10) + "px"
      }, {
        "queue" : false,
        "duration" : "slow",
        "easing" : "swing"
      });

      if (change) {
        timePlaceUser();

        // last edited merken für auto-jetzt zurücksetzen
        lastTimeEdit = (new Date()).getTime();
      }
    } else {
      var minDelta = Math.ceil($("#timeline").width() * 3600000 / this.zoom);

      Timeline.render(time - minDelta * 2, time + minDelta);
      // TODO elsewise queue and renderif (timelineDateTime != undefined) {
    }
  },
  /**
   * TODO
   * 
   * @param dateTimeText
   */
  setText : function(dateTimeText) {
    if (!$("#timeText").is(':visible')) {
      return;
    }
    $("#timeText").attr("value", dateTimeText);
  },
  /**
   * Returns TODO.
   * 
   * @param now
   *          a Date Object or if not set, the current cursor is used.
   * @returns {String} in format TODO.
   */
  getDateTime : function(now) {
    if (now == undefined) {
      now = new Date();
      if (Timeline.cursor != null) {
        now.setTime(Timeline.cursor);
      }
    }
    if (isNaN(now.getFullYear())) {
      alert("invalid now time: " + now);
    }
    return now.getFullYear() + "-" + rightTrimmed("00", (now.getMonth() + 1))
        + "-" + rightTrimmed("00", now.getDate()) + " "
        + rightTrimmed("00", now.getHours()) + ":"
        + rightTrimmed("00", now.getMinutes()) + ":"
        + rightTrimmed("00", now.getSeconds());
  },
  /**
   * TODO
   * 
   * @param str
   *          to interpret.
   * @returns Date or null.
   */
  parseDateTime : function(str) {
    var s = str.toLowerCase().replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/,
        "");
    var now = new Date();
    if (s.charAt(0) == "-") {
      s = s.substr(1).replace(/^[\s\xA0]+/, "");
      if (s.match("da?y?s?$")) {
        var days = s.replace(/ *da?y?s?/g, "") * 1;
        if (!isNaN(days)) {
          now.setTime(now.getTime() - days * 86400000);
          return now;
        }
      } else if (s.match("ho?u?r?s?$")) {
        var hours = s.replace(/ *ho?u?r?s?/g, "") * 1;
        if (!isNaN(hours)) {
          now.setTime(now.getTime() - hours * 3600000);
          return now;
        }
      } else if (s.match("mi?n?u?t?e?s?$")) {
        var mins = s.replace(/ *mi?n?u?t?e?s?/g, "") * 1;
        if (!isNaN(mins)) {
          now.setTime(now.getTime() - mins * 60000);
          return now;
        }
      } else if (s.match("se?c?o?n?d?s?$")) {
        var secs = s.replace(/ *se?c?o?n?d?s?/g, "") * 1;
        if (!isNaN(secs)) {
          now.setTime(now.getTime() - secs * 1000);
          return now;
        }
      }
    } else if (s == nowText) {
      return now;
    } else if (s.indexOf(":") == 2 && s.length == 5) {
      return new Date(now.getFullYear(), now.getMonth(), now.getDate(), s
          .substr(0, 2) * 1, s.substr(3, 2) * 1, 0);
    } else if (s.indexOf(":") == 2 && s.indexOf(":", 3) == 5 && s.length == 8) {
      return new Date(now.getFullYear(), now.getMonth(), now.getDate(), s
          .substr(0, 2) * 1, s.substr(3, 2) * 1, s.substr(6, 2) * 1);
    } else if (s.indexOf(":") == 13 && s.length == 16) {

      return new Date(s.substr(0, 4) * 1, s.substr(5, 2) * 1 - 1, s
          .substr(8, 2) * 1, s.substr(11, 2) * 1, s.substr(14, 2) * 1, 0);
    } else if (s.indexOf(":") == 13 && s.indexOf(":", 15) == 16
        && s.length == 19) {
      return new Date(s.substr(0, 4) * 1, s.substr(5, 2) * 1 - 1, s
          .substr(8, 2) * 1, s.substr(11, 2) * 1, s.substr(14, 2) * 1, s
          .substr(17, 2) * 1);
    }
    return null;
  }

};