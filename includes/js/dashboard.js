/**
 * @class Dashboard static class controls modifications Avatar and Metaballs
 *        containing the events.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 */
var Dashboard = {
  /**
   * The element the (one and only shown) edit-controls are currently displayed
   * over, or undefined.
   */
  over : undefined,
  /** Flag set in demo mode to avoid user interaction with the animated demo. */
  demo : false,
  /** Initial maximum character length for a field name. */
  entryMaxLength : 108,
  /**
   * Listeners notified for change of "place" of the avatar, "edit" or "color"
   * of an event.
   */
  listeners : new Object(),
  /**
   * Initializes the dashboard to work on the given div.
   * 
   * @param div
   *          in which the dashboard works.
   */
  init : function(div) {
    if (Dashboard.demo) {
      return;
    }
    $(div).on('mousemove', '.ball', function(event) {
      Dashboard.showEdits(this);
    });
    $(div).on('click', '.ball', function(event) {
      Dashboard.hideEdits(Dashboard.over);
      Dashboard.placeUser(this);
    });
  },
  /**
   * Registers a listener to receive change-notifications.
   * 
   * @param type
   *          textual shortcut for an event.
   * @param listener
   *          to call on the event.
   */
  addListener : function(type, listener) {
    if (this.listeners[type] == undefined) {
      this.listeners[type] = new Array();
    }
    this.listeners[type].push(listener);
  },

  /**
   * Notifies added listeners of a type of a change.
   * 
   * @param type
   *          of event.
   * @param further
   *          variable arguments passed to the listener-call.
   */
  notify : function(type) {
    var args = new Array();
    if (notify.arguments.length > 1) {
      for ( var i = 1; i < notify.arguments.length; i++) {
        args.push(notify.arguments[i]);
      }
    }

    if (this.listeners[type] == undefined) {
      return;
    }
    var array = this.listeners[type];
    for ( var i = 0; i < array.length; i++) {
      array[i].apply(this, args);
    }
  },
  /**
   * Place the avatar on the given element and event to start tracking time for.
   * 
   * @param element
   *          to place the avatar on and event to start tracking time for.
   */
  placeUser : function(element) {
    $('.edit').stop();
    notify("place", element);
  },
  /**
   * TODO
   * 
   * @param complete
   * @returns {Function}
   */
  returnCompleteEscAborts : function(complete) {
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
          this.hideEdits(this.over);
        }
      }
    };
  },
  /**
   * Displays a <code>cellEdit</code> component and allows editing of the
   * event text.
   * 
   * @param element
   *          to edit.
   */
  editText : function(element) {
    var ball = metaballs.get(element);
    // content-editing controls
    var text = $(element).text().replace(/"/g, '&quot;');
    $(element).css("color", "transparent");
    var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;top:50%;left:0px;width:100%;height:100%;margin-top:-14px;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:90%;" type="text" value="'
        + text + '" maxlength="' + entryMaxLength + '" /></div>');
    $(d).appendTo(element);

    var doChange = function() {
      ball.setText($("#cellEvent").val());
      notify("edit");
      this.hideEdits(this.over);
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
  },
  /**
   * Display a dialog to set the hyperlink for the given element.
   * 
   * @param element
   *          to set the hyperlink for.
   */
  editLink : function(element) {
    var ball = metaballs(element);
    // content-editing controls
    var text = $(element).text().replace(/"/g, '&quot;');
    var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:90%;" type="text" value="http://" maxlength="'
        + entryMaxLength + '" /></div>');
    $(d).appendTo(element);

    var doChange = function() {
      ball.setLink($("#cellEvent").val());
      notify("edit");
      this.hideEdits(this.over);
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
  },
  /**
   * Display a dialog to set the time-estimation for the given element.
   * 
   * @param element
   *          to set the time-estimation for.
   */
  editEstimation : function(element) {
    var ball = metaballs.get(element);
    // content-editing controls
    var text = $('#' + ball).text().replace(/"/g, '&quot;');
    var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:150px;" type="text" value="0d 0h" maxlength="30" /></div>');
    $(d).appendTo(element);

    var doChange = function() {
      var estimation = $("#cellEvent").val().replace(/^[\s\xA0]+/, "").replace(
          /[\s\xA0]+$/, "").replace(/</, "&lt;").replace(/>/, "&gt;");

      this.hideEdits(this.over);
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
  },
  /**
   * Display a dialog to set the color for the given element.
   * 
   * @param element
   *          to set the color for.
   */
  editColor : function(element) {
    var ball = metaballs.get(element);
    // content-editing controls
    var editColors = metaballs.getBallColors();
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
    $(d).appendTo(ball);
    $("#color").click(function(evt) {
      evt.stopPropagation();
      var img = $("#color").get(0);
      var pos = $("#color").offset();
      var x = evt.pageX - pos.left;
      var y = evt.pageY - pos.top;
      var w = $("#color").outerWidth(true);
      var h = $("#color").outerHeight(true);
      notify("color", img.src, x / w, y / h, function(color) {
        $(element).css("background-color", color);
        $("#cellColor").detach();
        notify("edit");
      });
    });
    $(".usedColor").click(function(evt) {
      evt.stopPropagation();
      var color = $(this).css("background-color");
      $(element).css("background-color", color);
      $("#cellColor").detach();
      notify("edit");
    });
  },
  /**
   * Adds edit-controls to the given element.
   * 
   * @param element
   *          to show the edit-controls on.
   */
  showEdits : function(element) {
    if (this.over != element) {
      this.hideEdits(this.over);
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

      var editControl = function(src, css, click, title) {
        var img = $('<img src="'
            + src
            + '" title="'
            + title
            + '" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;display:none;'
            + css + '" class="edit"/>');
        $(img).click(click);
        return img;
      };

      $(
          editControl(
              "graphics/eventColor.png",
              "left:0px;top:0px;width:" + ec.w + "px;height:" + ec.h + "px;",
              function(event) {
                event.stopPropagation();
                this.editColor(element);
              },
              "<i18n key='tab57'><en>select field color</en><de>Feldfarbe auswählen</de><fr>Choisissez une couleur pour le champ</fr><es>Elija un color para el campo</es></i18n>"))
          .appendTo(element);
      $(
          editControl(
              "graphics/eventText.png",
              "right:0px;margin-left:" + (-et.w / 2) + "px;top:0px;width:"
                  + et.w + "px;height:" + et.h + "px;",
              function(event) {
                event.stopPropagation();
                this.editText(element);
              },
              "<i18n key='tab62'><en>adjust field name</en><de>Feldbeschreibung anpassen</de><fr>Changer le nom du champ</fr><es>Cambie el nombre del campo</es></i18n>"))
          .appendTo(element);
      $(
          editControl(
              "graphics/eventLink.png",
              "right:0px;bottom:0px;width:" + el.w + "px;height:" + el.h
                  + "px;",
              function(event) {
                event.stopPropagation();
                this.editLink(element);
              },
              "<i18n key='tab60'><en>set field link</en><de>Verknüpfung des Feldes setzen</de><fr>Couplage de l'ensemble champ</fr><es>Vinculación del campo de juego</es></i18n>"))
          .appendTo(element);
      $(
          editControl(
              "graphics/stopwatch.png",
              "left:0px;bottom:0px;width:" + sw.w + "px;height:" + sw.h + "px;",
              function(event) {
                event.stopPropagation();
                this.editEstimation(element);
              },
              "<i18n key='tab63'><en>set planned time for activity</en><de>Plan-Zeit für Tätigkeit setzen</de><fr>régler l'heure prévue pour l'activité</fr><es>ajustar la hora prevista para la actividad</es></i18n>"))
          .appendTo(element);
      $('.edit').stop().delay(500).fadeIn();
      this.over = element;
    }
  },
  /**
   * Detaches the edit-controls possibly showing on the given element.
   * 
   * @param element
   *          on which edit-controls are showing.
   */
  hideEdits : function(element) {
    if ((element == undefined) || (element == null)) {
      return;
    }
    $(element).css("color", '');
    $("body").find(".edit").detach();
    this.over = undefined;
  },
  /**
   * Updates the positions of the edit-controls by hiding & showing.
   */
  updateEdits : function() {
    this.hideEdits(this.over);
    this.showEdits(this.over);
    watch();
  }
};
