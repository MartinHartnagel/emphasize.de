/**
 * @class TimeSortedSet class for sorted list of objects currently looked at
 *        with the ability to drop not accessed entries after some timeout.
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
function TimeSortedSet() {
  /**
   * Sorted list of objects currently looked at with the ability to drop not
   * accessed entries after some timeout.
   */
  this.list = new Array();
  /**
   * Performance index iterator for subsequent getAfter calls.
   */
  this.afterIterator = 0;
  /**
   * Adds an object to the list of objects. An already contained object at the
   * same time will be replaced.
   * 
   * @param time
   *          of the object in the timeline.
   * @param object
   *          object to add.
   * @param access
   *          if set, the access time in milliseconds, elsewise now.
   */
  TimeSortedSet.prototype.add = function(time, object, access) {
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time == time) {
        this.list[i].object = object;
        this.list[i].access = (access == undefined ? (new Date()).getTime()
            : access);
        return;
      }
      if (this.list[i].time > time) {
        this.list.splice(i, 0, {
          "time" : time,
          "object" : object,
          "access" : (access == undefined ? (new Date()).getTime() : access)
        });
        return;
      }
    }
    this.list.push({
      "time" : time,
      "object" : object,
      "access" : (access == undefined ? (new Date()).getTime() : access)
    });
    /*
     * this.list.sort(function(a, b) { return a.time - b.time; });
     */
  },

  /**
   * Returns the object in the timeline which started at or before the given
   * time.
   * 
   * @param time
   *          in milliseconds.
   * @returns the object in the timeline which started at or before the given
   *          time.
   */
  TimeSortedSet.prototype.getAt = function(time) {
    var last = null;
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time <= time) {
        last = {
          from : this.list[i].time,
          to : this.list.length > i + 1 ? this.list[i + 1].time : null,
          object : this.list[i].object
        };
        this.list[i].access = (new Date()).getTime();
      } else {
        break;
      }
    }
    return last;
  },
  /**
   * Returns the subsequent object in the timeline after the given time.
   * 
   * @param time
   *          in milliseconds.
   * @returns the subsequent object in the timeline.
   */
  TimeSortedSet.prototype.getAfter = function(time) {
    var next = null;
    // try afterIterator for subsequent calls
    if (this.afterIterator + 1 < this.list.length
        && this.list[this.afterIterator].time <= time
        && this.list[this.afterIterator + 1].time > time) {
      var i = this.afterIterator + 1;
      next = {
        from : this.list[i].time,
        to : this.list.length > i + 1 ? this.list[i + 1].time : null,
        object : this.list[i].object
      };
      this.list[i].access = (new Date()).getTime();
      this.afterIterator = i;
      return next;
    }
    // not found, so doing full search
    for ( var i = 0; i < this.list.length; i++) {
      if (this.list[i].time > time) {
        next = {
          from : this.list[i].time,
          to : this.list.length > i + 1 ? this.list[i + 1].time : null,
          object : this.list[i].object
        };
        this.list[i].access = (new Date()).getTime();
        this.afterIterator = i;
        break;
      }
    }
    return next;
  },
  /**
   * Drops entries out of the timeline which only have been last accessed at or
   * before timeout.
   * 
   * @param timeout
   *          time in milliseconds.
   */
  TimeSortedSet.prototype.doTimeoutCleanup = function(timeout) {
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
   * Returns a short description of the objects contained. Format of the string
   * is descriptive and may change without further notice.
   * 
   * @returns {String} a short description of the objects contained.
   */
  TimeSortedSet.prototype.shortDescription = function() {
    var s = "";
    for ( var i = 0; i < this.list.length; i++) {
      if (s != "") {
        s += ", ";
      }
      s += this.list[i].object;
    }
    return s;
  },
  /**
   * Clears the list of objects so that no entries exist.
   */
  TimeSortedSet.prototype.clear = function() {
    if (this.list.length > 0) {
      this.list.splice(0, this.list.length);
    }
  };
}
