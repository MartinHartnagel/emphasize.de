/**
 * @class Timeline class for displaying of tracked events in time.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Timeline = {
  /**
   * Sorted list of event-objects currently looked at with the ability to drop
   * not accessed entries after some timeout.
   */
  list : new Array(),
  /**
   * Initializes the timeline object.
   */
  init : function() {
  },
  /**
   * Adds a event to the list of event-objects.
   * 
   * @param time
   *          of the event in the timeline.
   * @param event
   *          event-object to add.
   * @param access
   *          if set, the access time in milliseconds, elsewise now.
   */
  addEvent : function(time, event, access) {
    this.list.push({
      "time" : time,
      "event" : event,
      "access" : (access == undefined ? (new Date()).getTime() : access)
    });
    this.list.sort(function(a, b) {
      return a.time - b.time;
    });
  },
  /**
   * Returns the event-object in the timeline which started at or before the
   * given time.
   * 
   * @param time
   *          in milliseconds.
   * @returns the event-object in the timeline which started at or before the
   *          given time.
   */
  getEventAt : function(time) {
    var lastEvent = null;
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time <= time) {
        lastEvent = {
          from : this.list[i].time,
          to : this.list.length > i + 1 ? this.list[i + 1].time : null,
          event : this.list[i].event
        }
        this.list[i].access = (new Date()).getTime();
      } else {
        break;
      }
    }
    return lastEvent;
  },
  /**
   * Returns the subsequent event-object in the timeline after the given time.
   * 
   * @param time
   *          in milliseconds.
   * @returns the subsequent event-object in the timeline.
   */
  getEventAfter : function(time) {
    var nextEvent = null;
    // TODO: optimize with a global i-try counter for iteration
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time > time) {
        nextEvent = {
          from : this.list[i].time,
          to : this.list.length > i + 1 ? this.list[i + 1].time : null,
          event : this.list[i].event
        }
        this.list[i].access = (new Date()).getTime();
        break;
      }
    }
    return nextEvent;
  },
  /**
   * Drops entries out of the timeline which only have been last accessed at or
   * before timeout.
   * 
   * @param timeout
   *          time in milliseconds.
   */
  doTimeoutCleanup : function(timeout) {
    var i = 0;
    while (i < this.list.length) {
      if (this.list[i].access <= timeout) {
        this.list.splice(i, 1);
      } else {
        i++;
      }
    }
  },
  /**
   * Returns a short description of the events contained. Format of the string
   * is descriptive and may change without further notice.
   * 
   * @returns {String} a short description of the events contained.
   */
  shortDescription : function() {
    var s = "";
    for ( var i = 0; i < this.list.length; i++) {
      if (s != "") {
        s += ", ";
      }
      s += this.list[i].event;
    }
    return s;
  },
  /**
   * Clears the list of events so that no entries exist.
   */
  clear : function() {
    if (this.list.length > 0) {
      this.list.splice(0, this.list.length);
    }
  },
  /**
   * Renders the timeline contents.
   * 
   * @param from
   *          time in milliseconds.
   * @param to
   *          time in milliseconds
   * @param zoom
   *          zoom-factor, equal to the width of pixels of an hour.
   * @returns {String} html to be placed in the timeline-div.
   */
  render : function(from, to, zoom) {
    if (from >= to) {
      return "";
    }
    var s = '';
    s += '<div id="time" class="tDiv" style="width: '
        + Math.floor((to - from) * zoom / 3600000) + 'px;">';
    s += '<div id="timetip" class="tTip">';
    s += '<span id="timetipText"></span>';
    s += '</div>';
    var then = new Date();
    then.setTime(from);
    then.setMinutes(0);
    then.setSeconds(0);
    then.setMilliseconds(0);
    var offset = Math.floor((from - then.getTime()) * zoom / 3600000);
    s += '<div class="tHours" style="margin-left:' + offset + 'px;">';
    var sep = '<div class="tSeparators" style="position:absolute;margin-left:'
        + (offset - 4) + 'px;">';

    var c = 0;
    for ( var t = from; t <= to; t += 3600000) {
      then.setTime(t);
      var day = $.datepicker.formatDate("D", then);
      var w = (Math.floor(c + zoom) - Math.floor(c));
      c += zoom;
      s += '<span style="width:' + w + 'px;">';
      if (then.getHours() < 10) {
        s += '&nbsp;&nbsp;' + day + " " + then.getHours();
      } else {
        s += '' + day + " " + then.getHours();
      }
      s += ':00</span>';
      sep += '<span style="width:' + Math.floor((w - 2) / 2)
          + 'px;margin-left:' + Math.ceil((w - 2) / 2) + 'px;">&nbsp;</span>';
    }
    s += '</div>';
    s += sep + '</div>';
    s += '<div id="tLine" class="tLine" unselectable="on">';
    var current = this.getEventAt(from);
    if (current == null) {
      current = this.getEventAfter(from);
    }
    while (current != null) {
      var duration;
      if (current.to != null) {
        duration = current.to - current.from;
      } else {
        duration = ((new Date()).getTime()) - current.from;
      }
      s += '<div class="box" style="left:'
          + Math.floor((current.from - from) * zoom / 3600000)
          + 'px;width:'
          + Math.floor(duration * zoom / 3600000)
          + 'px"><div title="'
          + current.event.event.replace(/"/g, '&quot;')
          + '" style="background-color:'
          + current.event.color
          + ';"></div><img src="'
          + domain
          + '/graphics/seperator.png" width="15" height="12" class="tsep"></div>';
      current = this.getEventAfter(current.from);
    }
    s += '</div>';
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
    return s;
  },

}