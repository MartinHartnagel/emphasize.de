/**
 * @class Dashboard object controls modifications on a html table containing the
 *        fields..
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
Dashboard = function() {
	this.table = undefined;
	var over = undefined;
	var grid = undefined;
	var record = false;
	var actions = new Array();
	var valid = true;
	var editColors = undefined;
	var demo = false;
	var self = "Dashboard.prototype";

	var listeners = new Object();
	/**
	 * Registers a listener to receive change-notifications.
	 * 
	 * @param type
	 *            textual shortcut for an event.
	 * @param listener
	 *            to call on the event.
	 */
	Dashboard.prototype.addListener = function(type, listener) {
		if (listeners[type] == undefined) {
			listeners[type] = new Array();
		}
		listeners[type].push(listener);
	};

	/**
	 * Notifies added listeners of a type of a change.
	 * 
	 * @param type
	 *            of event.
	 * @param further
	 *            variable arguments passed to the listener-call.
	 */
	function notify(type) {
		var args = new Array();
		if (notify.arguments.length > 1) {
			for ( var i = 1; i < notify.arguments.length; i++) {
				args.push(notify.arguments[i]);
			}
		}

		if (listeners[type] == undefined) {
			return;
		}
		var array = listeners[type];
		for ( var i = 0; i < array.length; i++) {
			array[i].apply(this, args);
		}
	}

	var debug = undefined;
	/** Initial maximum character length for a field name. */
	var entryMaxLength = 108;
	$("table.dashboard td").live('mousemove', function(event) {
		Dashboard.prototype.showEdits(this);
	});
	$("table.dashboard td").live('click', function(event) {
		hideEdits(over);
		Dashboard.prototype.placeUser(this);
	});
	/**
	 * Sets the maximum character length for a field name.
	 */
	Dashboard.prototype.setEntryMaxLength = function(len) {
		entryMaxLength = len;
	};

	Dashboard.prototype.setDemo = function(flag) {
		demo = flag;
	};

	Dashboard.prototype.setSelf = function(s) {
		self = s;
	};

	Dashboard.prototype.setDebug = function(s) {
		debug = s;
	};

	function getGrid() {
		updateGrid();
		return grid;
	}

	/**
	 * Ensures that the grid-arrays include the given cell at <code>x</code>,
	 * <code>y</code> coordinate.
	 * 
	 * @param x
	 *            of the cell-coordinate.
	 * @param y
	 *            of the cell-coordinate.
	 * @returns the grid-element at this coordinate.
	 */
	function ensureGrid(x, y) {
		while (grid.length < y + 1) {
			grid.push(new Array());
		}
		for ( var i = 0; i <= y; i++) {
			while (grid[i].length < x + 1) {
				grid[i].push(null);
			}
		}
		return grid[y][x];
	}

	/**
	 * Chechs the validity of the given table-grid.
	 * 
	 * @param agrid
	 *            of a html-table
	 * @returns <code>true</code> if valid, <code>false</code> otherwise.
	 */
	function checkValidity(agrid) {
		v = true;
		for ( var y = 0; y < agrid.length; y++) {
			for ( var x = 0; x < agrid[y].length; x++) {
				if (agrid[y][x] == null) {
					v = false;
				}
			}
		}
		return v;
	}

	function fillGrid() {
		var v = true;
		grid = new Array();

		// fill grid with coords
		for ( var y = 0; y < table.rows.length; y++) {
			var x = 0;
			for ( var a = 0; a < table.rows[y].cells.length; a++) {
				var td = table.rows[y].cells[a];
				while (ensureGrid(x, y) != null) {
					x++;
				}
				td.logGrid_x = x;
				td.logGrid_y = y;
				for ( var h = 0; h < td.rowSpan; h++) {
					for ( var w = 0; w < td.colSpan; w++) {
						if (ensureGrid(x + w, y + h) != null) {
							if (debug != undefined) {
								debug("already set " + (y + h) + ":" + (x + w));
							}
							v = false;
						}
						grid[y + h][x + w] = td;
					}
				}
			}
		}
		// fill dangling ends
		var maxCols = 1;
		for ( var y = 0; y < grid.length; y++) {
			if (maxCols < grid[y].length) {
				maxCols = grid[y].length;
			}
		}
		for ( var y = 0; y < grid.length; y++) {
			ensureGrid(maxCols - 1, y);
		}
		return v;
	}

	function updateGrid() {
		if (grid !== undefined) {
			return;
		}
		var v = true;
		v = v && fillGrid();
		v = v && checkValidity(grid);

		if (!v) {
			repairTable(grid);
			v = true;
			v = v && fillGrid();
			v = v && checkValidity(grid);

			debug("repaired table " + v);
		}

		valid = v;
		if (!v) {
			Dashboard.prototype.showGrid();
		}
	}

	function repairTable(agrid) {
		for ( var y = 0; y < agrid.length; y++) {
			for ( var x = 0; x < agrid[y].length; x++) {
				if (agrid[y][x] == null) {
					var l = table.rows[y].cells.length;
					var td = table.rows[y].insertCell(l);
					td.colSpan = 1;
					td.rowSpan = 1;
					$(td).css("background-color", "#aa0000");
					var text = document.createTextNode("fix(" + x + "," + y
							+ ")");
					td.appendChild(text);
				}
			}
		}
	}

	this.isValid = function() {
		updateGrid();
		return valid;
	};

	function watch() {
		if (demo) {
			editColors = new Array();
			return;
		}

		if (over != undefined) {
			Dashboard.prototype.showEdits(over);
		}
		editColors = new Array();
	}

	function canDoVerticalGlue(element) {
		var tdo = getTdo(element);
		var td = Dashboard.prototype.getTd(tdo.logGrid_x + tdo.colSpan,
				tdo.logGrid_y);
		if ((td != null) && (td.logGrid_y == tdo.logGrid_y)
				&& (td.rowSpan == tdo.rowSpan)) {
			return true;
		}
		return false;
	}

	Dashboard.prototype.verticalMerge = function(element) {
		if (demo) {
			return;
		}
		var tdo = getTdo(element);
		var td = Dashboard.prototype.getTd(tdo.logGrid_x + tdo.colSpan,
				tdo.logGrid_y);
		if ((td != null) && (td.logGrid_y == tdo.logGrid_y)
				&& (td.rowSpan == tdo.rowSpan)) {
			if (record) {
				if (actions.length > 20) {
					actions.pop();
				}
				actions.push("if (" + self + ".isValid())  " + self
						+ ".verticalMerge(" + self + ".getTd(" + tdo.logGrid_x
						+ "," + tdo.logGrid_y + "));");
			}
			// invalidateGrid
			grid = undefined;
			tdo.colSpan += td.colSpan;
			var tro = tdo.parentNode;
			tro.deleteCell(td.cellIndex);
			notify("edit");
		}
		Dashboard.prototype.updateEdits();
		return true;
	};

	function canDoHorizontalGlue(element) {
		var tdo = getTdo(element);
		var td = Dashboard.prototype.getTd(tdo.logGrid_x, tdo.logGrid_y
				+ tdo.rowSpan);
		if ((td != null) && (td.logGrid_x == tdo.logGrid_x)
				&& (td.colSpan == tdo.colSpan)) {
			return true;
		}
		return false;
	}

	Dashboard.prototype.horizontalMerge = function(element) {
		if (demo) {
			return;
		}
		var tdo = getTdo(element);
		var td = Dashboard.prototype.getTd(tdo.logGrid_x, tdo.logGrid_y
				+ tdo.rowSpan);
		if ((td != null) && (td.logGrid_x == tdo.logGrid_x)
				&& (td.colSpan == tdo.colSpan)) {
			if (record) {
				if (actions.length > 20) {
					actions.pop();
				}
				actions.push("if (" + self + ".isValid()) " + self
						+ ".horizontalMerge(" + self + ".getTd("
						+ tdo.logGrid_x + "," + tdo.logGrid_y + "));");
			}
			// invalidateGrid
			grid = undefined;
			tdo.rowSpan += td.rowSpan;
			var tr = td.parentNode;
			tr.deleteCell(td.cellIndex);
			notify("edit");
		}
		Dashboard.prototype.updateEdits();
		return true;
	};

	Dashboard.prototype.verticalSplit = function(element) {
		if (demo) {
			return;
		}
		var tdo = getTdo(element);
		if (record) {
			if (actions.length > 20) {
				actions.pop();
			}
			actions.push("if (" + self + ".isValid()) " + self
					+ ".verticalSplit(" + self + ".getTd(" + tdo.logGrid_x
					+ "," + tdo.logGrid_y + "));");
		}
		var td;
		if (tdo.colSpan > 1) {
			// invalidateGrid
			grid = undefined;
			var tro = tdo.parentNode;
			td = tro.insertCell(tdo.cellIndex + 1);
			td.colSpan = Math.floor(tdo.colSpan / 2);
			tdo.colSpan -= td.colSpan;
			td.rowSpan = tdo.rowSpan;
			$(td).css("background-color", $(tdo).css("background-color"));
		} else {
			// fill line left with extra colSpan, except for tdo
			var filled = undefined;
			for ( var i = 0; i < grid.length; i++) {
				var tda = Dashboard.prototype.getTd(tdo.logGrid_x, i);
				if ((tda != tdo) && (tda != filled)) {
					tda.colSpan++;
					filled = tda;
				}
			}
			// invalidateGrid
			grid = undefined;
			var tro = tdo.parentNode;
			td = tro.insertCell(tdo.cellIndex + 1);
			td.colSpan = 1;
			td.rowSpan = tdo.rowSpan;
			$(td).css("background-color", $(tdo).css("background-color"));
		}
		var text = document.createTextNode(cloneText(tdo));
		td.appendChild(text);
		notify("edit");
		Dashboard.prototype.updateEdits();
		return true;
	};

	Dashboard.prototype.findElement = function(text) {
		if (this.table == undefined) {
			this.table = $("#table:visible").get(0);
		}
		var place = null;
		for ( var r = 0; r < table.rows.length; r++) {
			for ( var c = 0; c < table.rows[r].cells.length; c++) {
				var txt = Dashboard.prototype.getEntry(table.rows[r].cells[c]);
				if (text == txt) {
					place = table.rows[r].cells[c];
					return place;
				}
			}
		}
		return place;
	};

	function cloneText(tdo) {
		hideEdits(over);
		var text = Dashboard.prototype.getEntry(tdo).replace(/&lt;/g, "<")
				.replace(/&gt;/g, ">").replace(/ \((\d+)\)$/, "");
		var c = 2;
		// TODO create uniqueness through Event-Table
		while (Dashboard.prototype.findElement(text + " (" + c + ")") != null) {
			c++;
		}
		return text + " (" + c + ")";
	}

	function getTdo(element) {
		if ((element == undefined) || (element == null)) {
			return element;
		}
		var p = element;
		while (p.nodeName != "TD") {
			p = p.parentNode;
		}
		var td = p;
		while (p.nodeName != "TABLE") {
			p = p.parentNode;
		}
		if (this.table != p) {
			this.table = p;
			// invalidateGrid
			grid = undefined;
			updateGrid();
		}
		return td;
	}

	Dashboard.prototype.getTd = function(x, y) {
		if (this.table == undefined) {
			this.table = $("#table:visible").get(0);
		}

		if ((x == undefined) || (y == undefined)) {
			if (debug != undefined) {
				debug("error: getTd needs x and y arguments. Call:\n"
						+ Dashboard.prototype.getTd.caller);
			}
		}
		updateGrid();
		if (y >= grid.length) {
			return null;
		}
		if (x >= grid[y].length) {
			return null;
		}
		return grid[y][x];
	};

	Dashboard.prototype.horizontalSplit = function(element) {
		if (demo) {
			return;
		}
		var tdo = getTdo(element);
		if (record) {
			if (actions.length > 20) {
				actions.pop();
			}
			actions.push("if (" + self + ".isValid()) " + self
					+ ".horizontalSplit(" + self + ".getTd(" + tdo.logGrid_x
					+ "," + tdo.logGrid_y + "));");
		}
		var td;
		if (tdo.rowSpan > 1) {
			var ty = tdo.logGrid_y + Math.ceil(tdo.rowSpan / 2);
			// find cell in target row ty before to insert after
			var after = 0;
			for ( var i = tdo.logGrid_x - 1; i > 0; i--) {
				var tb = Dashboard.prototype.getTd(i, ty);
				if (tb.logGrid_y == ty) {
					after = tb.cellIndex + 1;
					break;
				}
			}
			// invalidateGrid
			grid = undefined;
			if (table.rows.length > ty) {
				td = table.rows[ty].insertCell(after);
			} else if (after == 0) {
				var tr = table.insertRow(table.rows.length);
				td = tr.insertCell(0);
			}
			td.colSpan = tdo.colSpan;
			td.rowSpan = Math.floor(tdo.rowSpan / 2);
			tdo.rowSpan -= td.rowSpan;
			$(td).css("background-color", $(tdo).css("background-color"));
		} else {
			// fill line above with extra rowSpan, except for tdo
			var filled = undefined;
			for ( var i = 0; i < grid[tdo.logGrid_y].length; i++) {
				var tda = Dashboard.prototype.getTd(i, tdo.logGrid_y);
				if ((tda != tdo) && (tda != filled)) {
					tda.rowSpan++;
					filled = tda;
				}
			}
			// invalidateGrid
			grid = undefined;
			var tr = table.insertRow(tdo.logGrid_y + 1);
			td = tr.insertCell(0);
			td.colSpan = tdo.colSpan;
			td.rowSpan = 1;
			$(td).css("background-color", $(tdo).css("background-color"));
		}
		var text = document.createTextNode(cloneText(tdo));
		td.appendChild(text);
		notify("edit");
		Dashboard.prototype.updateEdits();
		return true;
	};

	function editControl(src, css, click, title) {
		var img = $('<img src="'
				+ src
				+ '" title="'
				+ title
				+ '" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;display:none;'
				+ css + '" class="edit"/>');
		$(img).click(click);
		return img;
	}

	function addEvent(tag, event, action) {
		if (event != undefined) {
			if (tag.style != undefined) { // IE special treatment
				eval("tag." + event + "=function() { "
						+ action.replace("this", "tag") + ";}");
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

	Dashboard.prototype.findTdAt = function(px, py) {
		var pos = $(table).offset();
		if ((px > pos.left) && (px < pos.left + $(table).outerWidth(true))
				&& (py > pos.top)
				&& (py < pos.top + $(table).outerHeight(true))) {
			for ( var y = 0; y < table.rows.length; y++) {
				for ( var a = 0; a < table.rows[y].cells.length; a++) {
					var td = table.rows[y].cells[a];
					var pos = $(td).offset();
					if ((px > pos.left)
							&& (px < pos.left + $(td).outerWidth(true))
							&& (py > pos.top)
							&& (py < pos.top + $(td).outerHeight(true))) {
						return td;
					}
				}
			}
		}
		return null;
	};

	Dashboard.prototype.placeUser = function(element) {
		$('.edit').stop();
		notify("place", element);
	};

	function returnCompleteEscAborts(complete) {
		return function(event) {
			event.stopPropagation();
			var code = 0;
			if (event != null) {
				code = event.keyCode ? event.keyCode : event.charCode;
			}
			if ((event == null) || (code == 13) || (code == 27)) {
				if (code != 27) {
					complete();
				} else {
					$("#cellEvent").val(text);
					hideEdits(over);
				}
			}
		};
	}

	/**
	 * Displays a <code>cellEdit</code> component and allows editing of the
	 * event text.
	 * 
	 * @param element
	 *            to edit.
	 */
	Dashboard.prototype.editText = function(element) {
		var tdo = getTdo(element);
		// content-editing controls
		var text = $(element).text().replace(/"/g, '&quot;');
		$(element).css("color", "transparent");
		var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;top:50%;left:0px;width:100%;height:100%;margin-top:-14px;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:90%;" type="text" value="'
				+ text + '" maxlength="' + entryMaxLength + '" /></div>');
		$(d).appendTo(tdo);

		var doChange = function() {
			var txt = $("#cellEvent").val().replace(/^[\s\xA0]+/, "").replace(
					/[\s\xA0]+$/, "").replace(/</, "&lt;").replace(/>/, "&gt;");
			if (txt == "") {
				txt = "_";
			}
			$(tdo).html(txt);
			notify("edit");
			hideEdits(over);
		};

		if (!demo) {
			$("#cellEvent").click(function(event) {
				event.stopPropagation();
			});
			$("#cellEvent").blur(function(event) {
				event.stopPropagation();
				if ($("#cellEvent").is(':visible')) {
					doChange();
				}
			});
			$("#cellEvent").keyup(returnCompleteEscAborts(doChange));
			$("#cellEvent").focus();
		}
	};

	Dashboard.prototype.editLink = function(element) {
		var tdo = getTdo(element);
		// content-editing controls
		var text = $(element).text().replace(/"/g, '&quot;');
		var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:90%;" type="text" value="http://" maxlength="'
				+ entryMaxLength + '" /></div>');
		$(d).appendTo(tdo);

		var doChange = function() {
			var link = $("#cellEvent").val().replace(/^[\s\xA0]+/, "").replace(
					/[\s\xA0]+$/, "").replace(/</, "&lt;").replace(/>/, "&gt;");
			if (link == "") {
				$(tdo).html(text);
			} else {
				$(tdo).html(
						'<a href="' + link + '" target="_blank">' + text
								+ '</a>');
			}
			notify("edit");
			hideEdits(over);
		};

		if (!demo) {
			$("#cellEvent").click(function(event) {
				event.stopPropagation();
			});
			$("#cellEvent").blur(function(event) {
				event.stopPropagation();
				if ($("#cellEvent").is(':visible')) {
					doChange();
				}
			});
			$("#cellEvent").keyup(returnCompleteEscAborts(doChange));
			$("#cellEvent").focus();
		}
	};

	Dashboard.prototype.editEstimation = function(element) {
		var tdo = getTdo(element);
		// content-editing controls
		var text = $(element).text().replace(/"/g, '&quot;');
		var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:150px;" type="text" value="0d 0h" maxlength="30" /></div>');
		$(d).appendTo(tdo);

		var doChange = function() {
			var estimation = $("#cellEvent").val().replace(/^[\s\xA0]+/, "")
					.replace(/[\s\xA0]+$/, "").replace(/</, "&lt;").replace(
							/>/, "&gt;");

			hideEdits(over);
		};

		if (!demo) {
			$("#cellEvent").click(function(event) {
				event.stopPropagation();
			});
			$("#cellEvent").blur(function(event) {
				event.stopPropagation();
				if ($("#cellEvent").is(':visible')) {
					doChange();
				}
			});
			$("#cellEvent").keyup(returnCompleteEscAborts(doChange));
			$("#cellEvent").focus();
		}
	};

	Dashboard.prototype.editColor = function(element) {
		var tdo = getTdo(element);
		// content-editing controls
		Dashboard.prototype.updateEditColors();
		var s = '<table style="padding:0px;margin:0px;" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%" ><tr><td colspan="'
				+ editColors.length
				+ '" height="'
				+ (32)
				+ '" style="border-radius:0px;-moz-border-radius:0px;"><img id="color" src="graphics/colors.png" aligneditCol="middle" title="<i18n key="tab2"><en>color</en><de>Farbe</de><fr>Couleur</fr><es>Color</es></i18n>" style="margin:0px;padding:0px;width:100%;height:100%;cursor:crosshair;" /></td></tr><tr>';
		for ( var i = 0; i < editColors.length; i++) {
			s += '<td height="16" class="usedColor" style="background-image:none;background-color:'
					+ editColors[i]
					+ ';border-radius:0px;-moz-border-radius:0px;padding:0px;cursor:crosshair;"><img src="graphics/void.png" style="width:100%;height:100%;" /></td>';
		}
		s += '</tr></table>';
		var d = $('<div id="cellColor" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;top:0px;left:0px;width:192px;height:64px;margin-top:-14px;">'
				+ s + '</div>');
		$(d).appendTo(tdo);
		$("#color").click(function(evt) {
			evt.stopPropagation();
			var img = $("#color").get(0);
			var pos = $("#color").offset();
			var x = evt.pageX - pos.left;
			var y = evt.pageY - pos.top;
			var w = $("#color").outerWidth(true);
			var h = $("#color").outerHeight(true);
			notify("color", img.src, x / w, y / h, function(color) {
				$(tdo).css("background-color", color);
				$("#cellColor").detach();
				notify("edit");
			});
		});
		$(".usedColor").click(function(evt) {
			evt.stopPropagation();
			var color = $(this).css("background-color");
			$(tdo).css("background-color", color);
			$("#cellColor").detach();
			notify("edit");
		});
	};

	Dashboard.prototype.showEdits = function(element) {
		var tdo = getTdo(element);
		if (over != tdo) {
			hideEdits(over);
			// cutting controls
			var ec = {
				w : 52,
				h : 43
			}; // eventColor
			var et = {
				w : 39,
				h : 31
			}; // eventText
			var el = {
				w : 44,
				h : 21
			}; // eventLink
			var sw = {
				w : 28,
				h : 31
			}; // stopwatch
			var vs = {
				w : 28,
				h : 49
			}; // verticalSplit
			var hs = {
				w : 50,
				h : 28
			}; // horizontalSplit
			var vg = {
				w : 40,
				h : 47
			}; // verticalGlue
			var hg = {
				w : 42,
				h : 37
			}; // horizontalGlue
			$(
					editControl(
							"graphics/eventColor.png",
							"left:0px;top:0px;width:" + ec.w + "px;height:"
									+ ec.h + "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.editColor(tdo);
							},
							"<i18n key='tab57'><en>select field color</en><de>Feldfarbe auswählen</de><fr>Choisissez une couleur pour le champ</fr><es>Elija un color para el campo</es></i18n>"))
					.appendTo(tdo);
			$(
					editControl(
							"graphics/eventText.png",
							"right:0px;margin-left:" + (-et.w / 2)
									+ "px;top:0px;width:" + et.w + "px;height:"
									+ et.h + "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.editText(tdo);
							},
							"<i18n key='tab62'><en>adjust field name</en><de>Feldbeschreibung anpassen</de><fr>Changer le nom du champ</fr><es>Cambie el nombre del campo</es></i18n>"))
					.appendTo(tdo);
			$(
					editControl(
							"graphics/eventLink.png",
							"right:0px;bottom:0px;width:" + el.w + "px;height:"
									+ el.h + "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.editLink(tdo);
							},
							"<i18n key='tab60'><en>set field link</en><de>Verknüpfung des Feldes setzen</de><fr>Couplage de l'ensemble champ</fr><es>Vinculación del campo de juego</es></i18n>"))
					.appendTo(tdo);
			$(
					editControl(
							"graphics/stopwatch.png",
							"left:0px;bottom:0px;width:" + sw.w + "px;height:"
									+ sw.h + "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.editEstimation(tdo);
							},
							"<i18n key='tab63'><en>set planned time for activity</en><de>Plan-Zeit für Tätigkeit setzen</de><fr>régler l'heure prévue pour l'activité</fr><es>ajustar la hora prevista para la actividad</es></i18n>"))
					.appendTo(tdo);
			$(
					editControl(
							"graphics/horizontalSplit.png",
							"left:" + (-hs.w / 2 - 8)
									+ "px;top:50%;margin-top:" + (-hs.h / 2)
									+ "px;width:" + hs.w + "px;height:" + hs.h
									+ "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.horizontalSplit(tdo);
							},
							"<i18n key='tab3'><en>split horizontally</en><de>horizontal schneiden</de><fr>fissure horizontale</fr><es>división horizontal</es></i18n>"))
					.appendTo(tdo);
			$(
					editControl(
							"graphics/verticalSplit.png",
							"left:50%;margin-left:" + (-vs.w / 2) + "px;top:"
									+ (-vs.h / 2 - 8) + "px;width:" + vs.w
									+ "px;height:" + vs.h + "px;",
							function(event) {
								event.stopPropagation();
								Dashboard.prototype.verticalSplit(tdo);
							},
							"<i18n key='tab4'><en>split vertically</en><de>vertikal schneiden</de><fr>fissure verticale</fr><es>división vertical</es></i18n>"))
					.appendTo(tdo);
			if (canDoHorizontalGlue(tdo)) {
				$(
						editControl(
								"graphics/horizontalGlue.png",
								"left:50%;margin-left:" + (-8) + "px;bottom:"
										+ (-hg.h / 2 - 4) + "px;width:" + hg.w
										+ "px;height:" + hg.h + "px;",
								function(event) {
									event.stopPropagation();
									Dashboard.prototype.horizontalMerge(tdo);
								},
								"<i18n key='tab5'><en>glue horizontally</en><de>horizontal kleben</de><fr>colle horizontale</fr><es>cola horizontal</es></i18n>"))
						.appendTo(tdo);
			}
			if (canDoVerticalGlue(tdo)) {
				$(
						editControl(
								"graphics/verticalGlue.png",
								"right:" + (-vg.w / 2)
										+ "px;top:50%;margin-top:" + (-8)
										+ "px;width:" + vg.w + "px;height:"
										+ vg.h + "px;",
								function(event) {
									event.stopPropagation();
									Dashboard.prototype.verticalMerge(tdo);
								},
								"<i18n key='tab6'><en>glue vertically</en><de>vertikal kleben</de><fr>colle verticale</fr><es>cola vertical</es></i18n>"))
						.appendTo(tdo);
			}
			$('.edit').stop().delay(500).fadeIn();
			over = tdo;
		}
	};

	function hideEdits(element) {
		if ((element == undefined) || (element == null)) {
			return;
		}
		var tdo = getTdo(element);
		$(tdo).css("color", '');
		$("body").find(".edit").detach();
		over = undefined;
	}

	Dashboard.prototype.updateEdits = function() {
		hideEdits(over);
		Dashboard.prototype.showEdits(over);
		watch();
	};

	Dashboard.prototype.showActions = function() {
		if (record) {
			if (debug != undefined) {
				debug(actions.join("\n"));
			} else {
				alert(actions.join("\n"));
			}
		} else {
			record = true;
		}
	};

	Dashboard.prototype.showGrid = function() {
		var s = "";
		var grid = getGrid();
		for ( var y = 0; y < grid.length; y++) {
			for ( var x = 0; x < grid[y].length; x++) {
				if (grid[y][x] != null) {
					s += "[" + grid[y][x].logGrid_y + ","
							+ grid[y][x].cellIndex + "]";
				} else {
					s += "[n]";
				}
			}
			s += "\n";
		}
		if (debug != undefined) {
			debug("actions:\n" + actions.join("\n") + "\n\ngrid:\n" + s);
		} else {
			alert("grid:\n" + s);
		}
	};

	Dashboard.prototype.getEntry = function(element) {
		if ((element != undefined) && (element != null)) {
			return $(element).text().replace(/^[\s\xA0]+/, "").replace(
					/[\s\xA0]+$/, "");
		} else {
			return null;
		}
	};

	Dashboard.prototype.getColor = function(element) {
		var td = getTdo(element);
		var color = $(td).css("background-color");
		if (color.indexOf("rgb") != -1) {
			var parts = color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
			var s = "#";
			for ( var i = 1; i <= 3; ++i) {
				var p = parseInt(parts[i]).toString(16);
				if (p.length == 1) {
					s += "0";
				}
				s += p;
			}
			color = s;
		}
		return color;
	};

	Dashboard.prototype.updateEditColors = function() {
		for ( var y = 0; y < table.rows.length; y++) {
			for ( var a = 0; a < table.rows[y].cells.length; a++) {
				var td = table.rows[y].cells[a];
				var color = $(td).css("background-color");
				if (color.indexOf("rgb") != -1) {
					var parts = color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
					var s = "#";
					for ( var i = 1; i <= 3; ++i) {
						var p = parseInt(parts[i]).toString(16);
						if (p.length == 1) {
							s += "0";
						}
						s += p;
					}
					color = s;
				}
				if ($.inArray(color, editColors) == -1) {
					editColors.push(color);
				}
			}
		}
	};

	Dashboard.prototype.getTableHtml = function() {
		hideEdits(over);
		var s = $(table).html();
		var offset = s.toLowerCase().lastIndexOf("</th>");
		if (offset < 0) {
			offset = 0;
		}
		offset = s.toLowerCase().indexOf("<tr>", offset);
		var end = s.toLowerCase().lastIndexOf("</tr>") + 5;
		return s.substring(offset, end).replace(/ colspan="1"/gi, "").replace(
				/ rowspan="1"/gi, "").replace(/<tr><\/tr>/gi, "");
	};

	Dashboard.prototype.setTableHtml = function(tbody) {
		// invalidateGrid
		grid = undefined;
		$(table).html(tbody);
		updateGrid();
		watch();
	};
};
