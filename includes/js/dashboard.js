/**
 * @class Dashboard object controls modifications on a html table containing the
 *        fields..
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
Dashboard = function() {
  this.metaballs = Metaballs;
  var over = undefined;
  var demo = false;
  var self = "Dashboard.prototype";

  var listeners = new Object();
  /**
   * Registers a listener to receive change-notifications.
   * 
   * @param type
   *          textual shortcut for an event.
   * @param listener
   *          to call on the event.
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
   *          of event.
   * @param further
   *          variable arguments passed to the listener-call.
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
  /* TODO enable with metaballs replacement
   * $("table.dashboard td").on('mousemove', function(event) {
    if (isLoggedIn()) {
      Dashboard.prototype.showEdits(this);
    }
  });
  $("table.dashboard td").on('click', function(event) {
    if (isLoggedIn()) {
      hideEdits(over);
      Dashboard.prototype.placeUser(this);
    }
  });*/
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

  function watch() {
    if (demo) {
      return;
    }

    if (over != undefined) {
      Dashboard.prototype.showEdits(over);
    }
  }

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
        eval("tag." + event + "=function() { " + action.replace("this", "tag")
            + ";}");
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
   *          to edit.
   */
  Dashboard.prototype.editText = function(element) {
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
    var ball = metaballs(element);
    // content-editing controls
    var text = $(element).text().replace(/"/g, '&quot;');
    var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:90%;" type="text" value="http://" maxlength="'
        + entryMaxLength + '" /></div>');
    $(d).appendTo(element);

    var doChange = function() {
      ball.setLink($("#cellEvent").val());
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
    var ball = metaballs.get(element);
    // content-editing controls
    var text = $('#'+ball).text().replace(/"/g, '&quot;');
    var d = $('<div id="cellEdit" class="edit" style="cursor:pointer;position:absolute;overflow:hidden;z-index:50;bottom:20px;left:0px;width:100%;"><input id="cellEvent" style="text-align:center;vertical-align:middle;line-height:1em;font-size:14px;width:150px;" type="text" value="0d 0h" maxlength="30" /></div>');
    $(d).appendTo(element);

    var doChange = function() {
      var estimation = $("#cellEvent").val().replace(/^[\s\xA0]+/, "").replace(
          /[\s\xA0]+$/, "").replace(/</, "&lt;").replace(/>/, "&gt;");

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
  };

  Dashboard.prototype.showEdits = function(element) {
    if (over != element) {
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
              "left:0px;top:0px;width:" + ec.w + "px;height:" + ec.h + "px;",
              function(event) {
                event.stopPropagation();
                Dashboard.prototype.editColor(element);
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
                Dashboard.prototype.editText(element);
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
                Dashboard.prototype.editLink(element);
              },
              "<i18n key='tab60'><en>set field link</en><de>Verknüpfung des Feldes setzen</de><fr>Couplage de l'ensemble champ</fr><es>Vinculación del campo de juego</es></i18n>"))
          .appendTo(element);
      $(
          editControl(
              "graphics/stopwatch.png",
              "left:0px;bottom:0px;width:" + sw.w + "px;height:" + sw.h + "px;",
              function(event) {
                event.stopPropagation();
                Dashboard.prototype.editEstimation(element);
              },
              "<i18n key='tab63'><en>set planned time for activity</en><de>Plan-Zeit für Tätigkeit setzen</de><fr>régler l'heure prévue pour l'activité</fr><es>ajustar la hora prevista para la actividad</es></i18n>"))
          .appendTo(element);
      $('.edit').stop().delay(500).fadeIn();
      over = element;
    }
  };

  function hideEdits(element) {
    if ((element == undefined) || (element == null)) {
      return;
    }
    $(element).css("color", '');
    $("body").find(".edit").detach();
    over = undefined;
  }

  Dashboard.prototype.updateEdits = function() {
    hideEdits(over);
    Dashboard.prototype.showEdits(over);
    watch();
  };
};
