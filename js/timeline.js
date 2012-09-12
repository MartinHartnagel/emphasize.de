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
	zoom : 100,
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
	time : null,
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
			var pos = $("#time").position();
			moveTimeTo(-pos.left + e.pageX - 4);
		});
		var timeTip = function(e) {
			var pos = $("#time").position();
			var x = -pos.left + e.pageX;
			var time = Timeline.getTimeAt(x);
			var tip = new Date();
			tip.setTime(time);
			var txt = rightTrimmed("00", tip.getHours()) + ":"
					+ rightTrimmed("00", tip.getMinutes());
			$("#timetipText").html(txt);
			$("#timetip").css("left", (x - 40) + "px");
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
	 *            zoom-factor, equal to the width of pixels of an hour.
	 */
	setZoom : function(zoom) {
		this.zoom = zoom;
	},
	/**
	 * Returns the time in milliseconds currently represented at the x-position
	 * in the rendered timeline div.
	 * 
	 * @param x
	 *            x-position in the timeline-div.
	 * @returns {Number} time in milliseconds corresponding to the x-position.
	 */
	getTimeAt : function(x) {
		return Math.floor(this.from + x * 3600000 / this.zoom);
	},
	/**
	 * Returns the x-position in the timeline-div currently representing the
	 * time in milliseconds in the rendered timeline div.
	 * 
	 * @param time
	 *            in milliseconds to get the representing x in the timeline-div
	 *            in.
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
	 *            time in milliseconds.
	 * @param to
	 *            time in milliseconds
	 * @returns {String} html to be placed in the timeline-div.
	 */
	render : function(from, to) {
		if (from >= to) {
			return "";
		}
		this.from = from;
		this.to = to;
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
		s += '<div class="tHours" style="margin-left:' + offset + 'px;">\n';
		var sep = '<div class="tSeparators" style="position:absolute;margin-left:'
				+ (offset - 4) + 'px;">\n';

		var c = 0;
		for ( var t = from; t <= to; t += 3600000) {
			then.setTime(t);
			var day = $.datepicker.formatDate("D", then);
			var w = (Math.floor(c + this.zoom) - Math.floor(c));
			c += this.zoom;
			s += '\n<span style="width:' + w + 'px;">';
			if (then.getHours() < 10) {
				s += '&nbsp;&nbsp;' + day + " " + then.getHours();
			} else {
				s += '' + day + " " + then.getHours();
			}
			s += ':00</span>';
			sep += '\n<span style="width:' + Math.floor((w - 2) / 2)
					+ 'px;margin-left:' + Math.ceil((w - 2) / 2)
					+ 'px;">&nbsp;</span>';
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
		return s;
	},

	/**
	 * TODO
	 * 
	 * @param time
	 */
	setTime : function(time) {
		this.time = time;
		if (!$("#time").is(':visible')) {
			return;
		}
		var x = this.getX(time);

		var pos = $("#time").position();
		if (Math.abs(x + pos.left - this.getWidth() / 2) > this.getWidth() / 3) {
			var target = x - this.getWidth() / 2;
			if (target < 0) {
				target = 0;
			}
			if (target > timelineMax + this.zoom - this.getWidth()) {
				target = timelineMax + this.zoom - this.getWidth();
			}
			$('#time').stop();
			$("#time").animate({
				left : Math.floor(-target) + "px"
			}, {
				"queue" : "false",
				"duration" : "slow"
			});
		}

		$("#now").stop();
		if (timelineDateTime != undefined) {
			if (isLoggedIn()) {
				$("#timeText").attr("value", timelineDateTime);
			} else {
				change = false;
			}
			var now = new Date();
			var before = parseDateTime(timelineDateTime);
			if (before == null) {
				alert("parsing date-time failed: " + timelineDateTime);
				return;
			}
			var x = timelineMax + 20 + now.getMinutes()
					- ((now.getTime() - before.getTime()) / 60000);
			moveTimeline(x);
			$('#now').animate({
				left : Math.floor(x - 9) + "px"
			}, {
				"queue" : "false",
				"duration" : "slow",
				"easing" : "swing"
			});

			// last edited merken für auto-jetzt zurücksetzen
			timelineLastEdit = (new Date()).getTime();
		} else {
			if (isLoggedIn()) {
				$("#timeText").attr("value", nowText);
			} else {
				change = false;
			}
			var now = new Date();
			var x = timelineMax + 20 + now.getMinutes();
			moveTimeline(x);
			$('#now').animate({
				left : Math.floor(x - 9) + "px"
			}, {
				"queue" : "false",
				"duration" : "slow",
				"easing" : "swing"
			});
		}
	}

};