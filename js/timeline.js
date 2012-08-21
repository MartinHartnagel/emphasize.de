/**
 * @class Timeline class for displaying of tracked events in time.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Timeline = {
  list : new Array(),
  init : function() {
  },
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
  getEventAt : function(time) {
    var lastEvent = null;
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time <= time) {
        lastEvent = this.list[i].event;
      } else {
        break;
      }
    }
    return lastEvent;
  },
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
  clear : function() {
    if (this.list.length > 0) {
      this.list.splice(0, this.list.length);
    }
  }
}