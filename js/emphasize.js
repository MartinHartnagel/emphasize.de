var currentPlace = undefined;
var timelineDateTime = getDateTime(new Date());
var timelineWidth = 600;
var timelineHour = 120;
var timelineMax = 1440 * 3;
var timelineLastEdit = 0;
var blindPlace = false;
var queue = new Array();
var aboves = new Object();
var editReset = undefined;
var currentHelpId = undefined;
var addInfoText = undefined;
var currentInfoText = undefined;
var nowText = "";
var userPlaceEventVersion = 0;
var timePlaceUpdates = 0;

/**
 * @class Config holds defaults and settings.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var config = {
  timelineWidth : 600,
  timelineHour : 120,
  timelineMax : 1440 * 3,
  timelineLastEdit : 0
};

/**
 * @class Model manages server calls.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Model = {
  place : function(element, handlers) {
    placeUser(element);
  },
  edit : function(tbody, handlers) {
    $.ajax({
      url : domain + "util/ajax.php",
      type : "POST",
      async : true,
      dataType : "html",
      data : ({
        "do" : "updateTbody",
        "token" : token,
        "tbody" : tbody
      }),
      success : handlers.success,
      error : handlers.error
    });
  },
  color : function(fullSrc, x, y, setColor) {
    var src = fullSrc.substr(domain.length);
    $.get(domain + "util/rgb.php", {
      "src" : src,
      "x" : x,
      "y" : y
    }, function(data) {
      setColor(data);
    });
  }
};

/**
 * @class Presenter is aware of the workflow, handling the Model and View.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Presenter = {
  init : function() {
    View.dashboard.setSelf("View.dashboard");
    View.dashboard.addListener("place", this.place);
    View.dashboard.addListener("edit", this.edit);
    View.dashboard.addListener("color", this.color);
    View.init();
    Progress.init();
  },
  place : function(element) {
    placeUser(element);
    Model.place(element, {
      success : function(data) {

      },
      error : function(data) {

      }
    });
  },
  edit : function() {
    if (!View.dashboard.isValid()) {
      Progress
          .showStatus(
              true,
              "<i18n key='mph1'><en>Storing invalid partitioning of fields denied</en><de>Speichern ungültiger Feldaufteilung abgewiesen</de><fr>Stockage de partitionnement invalide de domaines privés</fr><es>Almacenamiento de partición no válida de los campos negado</es></i18n>");
    }
    Model
        .edit(
            View.dashboard.getTableHtml(),
            {
              success : function(data) {
                Progress
                    .showStatus(
                        false,
                        "<i18n key='mph18'><en>Stored partitioning of fields</en><de>Feldaufteilung gespeichert</de><fr>Partitions stockées</fr><es>Particiones almacenadas</es></i18n>");
              },
              error : function(data) {
                Progress
                    .showStatus(
                        true,
                        error
                            + " "
                            + status
                            + ",&nbsp;<i18n key='mph0'><en>Storing partitioning of fields failed</en><de>Speichern der Feldaufteilung fehlgeschlagen</de><fr>Sauvegarder les partitions a échoué</fr><es>Guardar las particiones no</es></i18n>");
              }
            });
  },
  color : function(fullSrc, x, y, setColor) {
    Model.color(fullSrc, x, y, setColor);
  }
};

/**
 * @class View installs listeners and shows changes in widgets.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var View = {
  dashboard : undefined,
  init : function() {
    var tabs = $("#tabs")
        .tabs(
            {
              show : function(e, ui) {
                View.sizing();
                return true;
              },
              add : function(e, ui) {
                $(this).tabs("select", ui.index);
                View.sizing();
              },
              ajaxOptions : {
                error : function(xhr, status, index, anchor) {
                  $(anchor.hash).html(
                      "Couldn't load this tab. We'll try to fix this as soon as possible. "
                          + "If this wouldn't be a demo.");
                }
              },
              fx : {
                opacity : 'toggle'
              },
              spinner : "...",
              tabTemplate : '<li><a href="#{href}">#{label}</a><span class="tabicons"><span class="ui-icon ui-icon-pencil"><i18n ref="dbd1" /></span><span class="ui-icon ui-icon-folder-open"><i18n ref="dbd2" /></span><span class="ui-icon ui-icon-disk"><i18n ref="dbd3" /></span><span class="ui-icon ui-icon-trash"><i18n ref="dbd4" /></span></span></li>',
              panelTemplate : '<div><table id="table" class="dashboard" width="100%" height="100%" border="0" cellspacing="2" cellpadding="0"><tbody><tr><td style="background-color:#f99123"><i18n ref="dbd5" /></td></tr></tbody></table></div>'
            });
    $("#tabs span.ui-icon-document")
        .click(
            function() {
              $(
                  '<div id="dialog-form" title="<i18n ref="dbd5" />">'
                      + '<form id="tabNameForm"><fieldset>'
                      + '<label for="tabName"><i18n ref="dbd5" /></label>'
                      + '<input type="text" name="tabName" id="tabName" class="text ui-widget-content ui-corner-all" />'
                      + '</fieldset></form></div>').dialog(
                  {
                    autoOpen : false,
                    modal : false,
                    buttons : {
                      Add : function() {
                        tabs.tabs("add", "#tabs-5", $("#tabName").val(), tabs
                            .tabs("length") - 1);
                        $(this).dialog("close");
                      },
                      Cancel : function() {
                        $(this).dialog("close");
                      }
                    },
                    open : function() {
                      $('#tabName').focus();
                    }
                  }).dialog("open");
            });
    $("#tabs span.ui-icon-disk").live("click", function() {
      var tabTitle = $(this).parent().parent().find("a").text();
      exportTempl(tabTitle, View.dashboard.getTableHtml());
    });
    $("#tabs span.ui-icon-trash").live("click", function() {
      var index = $("li", tabs).index($(this).parent().parent());
      tabs.tabs("remove", index);
    });
  },
  addPlaceHandler : function(handler) {
    this.dashboard.setOnPlaced(handler);
  },
  addEditHandler : function(handler) {
    this.dashboard.setOnEdited(handler);
  },
  sizing : function() {
    var wh = $(window).height();
    var h = $(".ui-tabs-panel:visible").offset().top
        + $(".ui-tabs-panel:visible").outerHeight(true)
        - $(".ui-tabs-panel:visible").height() + 10;
    $(".ui-tabs-panel").height(Math.floor(wh - h));
    View.dashboard.updateEdits();
  }
};

$(document).ready(function() {
  View.dashboard = new Dashboard($("#table").get(0));
  Presenter.init();
  initView();
});

function updateTimelineMax(days) {
  timelineMax = 1440 * days;
  $('.tDiv').css("width", Math.floor(timelineMax + timelineMax) + "px");
  $('#now').css("left", Math.floor(timelineMax + timelineMax) + "px");
  updateTimeline();
}

function dereferLink(url) {
  $('iframe#derefer').detach();
  var derefer = $('<iframe id="derefer" src="'
      + domain
      + 'util/derefer.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');
  $(derefer).appendTo($("body"));
  $('iframe#derefer').load(function() {
    var context = this.contentWindow.document;
    $("#dereferUrl", context).val(url);
    $("#dereferSubmit", context).click();
    window.setTimeout("$('iframe#derefer').detach();", 5000);
  });
}

function initEmphasize() {
  // deprecated
}

function initView() {
  $.datepicker.setDefaults($.datepicker.regional[lang]);
  $("#reportFrom, #reportTo")
      .datepicker(
          {
            defaultDate : "+1w",
            changeMonth : true,
            dateFormat : 'yy-mm-dd',
            showButtonPanel : true,
            showOtherMonths : true,
            selectOtherMonths : true,
            showWeek : true,
            showAnim : 'blind',
            onSelect : function(selectedDate) {
              var option = this.id == "reportFrom" ? "minDate" : "maxDate", instance = $(
                  this).data("datepicker"), date = $.datepicker.parseDate(
                  instance.settings.dateFormat
                      || $.datepicker._defaults.dateFormat, selectedDate,
                  instance.settings);
              dates.not(this).datepicker("option", option, date);
            }
          });

  $('.docu').hide();

  View.dashboard.setDebug(debug);
  View.dashboard.showActions();
  $(window).resize(function() {
    View.sizing();
    if (currentPlace != undefined) {
      updateTitle(currentPlace);
      Avatar.showIn(currentPlace);
    }
    updateTimelineWidth();
  });
  $("#time").live('click', function(e) {
    var pos = $("#time").position();
    moveTimeTo(-pos.left + e.pageX - 4);
  });
  var timeTip = function(e) {
    var pos = $("#time").position();
    var x = -pos.left + e.pageX - 4;
    var now = new Date();
    if ((x < timelineMax + 20 + now.getMinutes())
        && (x > 20 + now.getMinutes())) {
      var mins = timelineMax + 20 + now.getMinutes() - x;
      var tip = new Date();
      tip.setTime(now.getTime() - mins * 60000 - now.getSeconds() * 1000);
      var txt = rightTrimmed("00", tip.getHours()) + ":"
          + rightTrimmed("00", tip.getMinutes());
      $("#timetipText").html(txt);
      $("#timetip").css("left", (x - 37) + "px");
      $("#timetip").show();
    } else {
      $("#timetip").hide();
    }
  };
  $("#time").live("mousemove", timeTip);
  $("#time").live("hover", timeTip, function() {
    $("#timetip").hide();
  });
  addInfoText = $("#info").val();
  $("#info").focus(function(e) {
    currentColor = $("#info").css('color');
    if (currentColor == '#777777' || currentColor == 'rgb(119, 119, 119)') {
      $("#info").css('color', '#000000');
      if (currentInfoText != undefined) {
        $("#info").val(currentInfoText);
      } else {
        $("#info").val('');
      }
    }
  });
  $("#info").blur(function(e) {
    if ($("#info").val() == "") {
      $("#info").css('color', '#777777');
      if (currentInfoText != undefined) {
        $("#info").val(currentInfoText);
      } else {
        $("#info").val(addInfoText);
      }
    }
  });
  $("#info").keypress(function(e) {
    if (e.which == '13') {
      var datetime = getTimelineDateTime();
      var entry = {
        "type" : 1,
        "info" : $("#info").val(),
        "datetime" : datetime
      };
      $("#info").val("");
      queue.push(entry);
      processQueue();

      e.preventDefault();
      $("#info").blur();
    }
  });

  $("#shadow").bind('click', function(e) {
    clickThrough(e.pageX, e.pageY);
  });
  $("#avatar").bind('click', function(e) {
    clickThrough(e.pageX, e.pageY);
  });
  $("#shadow").load(function() {
    if (currentPlace != undefined) {
      updateTitle(currentPlace);
      Avatar.showIn(currentPlace);
    }
  });
  $("#user").load(function() {
    if (currentPlace != undefined) {
      updateTitle(currentPlace);
      Avatar.showIn(currentPlace);
    }
  });
  $('a').live('click', function() {
    var url = $(this).attr("href");
    if (url.indexOf(":/") != -1 && url.indexOf(domain) == -1) {
      dereferLink(url);
      return false;
    }
  });
  $(window).unload(function() {
    // TODO check config if autoPause
    return true;
  });
  nowText = $("#timeText").attr("value");
  updateTimelineMax(3);
  // disable timeline selection
  $('.timeline').find('*').attr('unselectable', 'on').css('MozUserSelect',
      'none');
}

function initReport() {
  $('.docu').hide();
  $('a').live('click', function() {
    url = $(this).attr("href");
    if (url.indexOf(":/") != -1 && url.indexOf(domain) == -1) {
      dereferLink(url);
      return false;
    }
  });
}

function initPlaceUser(event) {
  if (blindPlace) {
    $("#blind").hide();
    blindPlace = false;
  }
  var element = View.dashboard.findElement(event);
  if (element != undefined && element != null) {
    currentPlace = element;
    updateTitle(element);
    Avatar.showIn(element);
  } else if (event.length > 0) {
    $("#blind").html(
        '<div class="border" style="padding:15pt 0pt 0pt 0pt;height:35pt;">'
            + event + '</div>');
    $("#blind").css({
      "background" : "url(" + domain + "graphics/blind.png)",
      "height" : "50pt"
    });

    $("#blind").show();
    blindPlace = true;
    currentPlace = $("#blind").get(0);
    updateTitle(currentPlace);
    Avatar.showIn(currentPlace);
  } else {
    Avatar.hide();
  }
}

function getDateTime(now) {
  if (isNaN(now.getFullYear())) {
    alert("invalid now time: " + now);
  }
  return now.getFullYear() + "-" + rightTrimmed("00", (now.getMonth() + 1))
      + "-" + rightTrimmed("00", now.getDate()) + " "
      + rightTrimmed("00", now.getHours()) + ":"
      + rightTrimmed("00", now.getMinutes()) + ":"
      + rightTrimmed("00", now.getSeconds());
}

function parseDateTime(str) {
  var s = str.toLowerCase().replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, "");
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
    return new Date(now.getFullYear(), now.getMonth(), now.getDate(), s.substr(
        0, 2) * 1, s.substr(3, 2) * 1, 0);
  } else if (s.indexOf(":") == 2 && s.indexOf(":", 3) == 5 && s.length == 8) {
    return new Date(now.getFullYear(), now.getMonth(), now.getDate(), s.substr(
        0, 2) * 1, s.substr(3, 2) * 1, s.substr(6, 2) * 1);
  } else if (s.indexOf(":") == 13 && s.length == 16) {

    return new Date(s.substr(0, 4) * 1, s.substr(5, 2) * 1 - 1,
        s.substr(8, 2) * 1, s.substr(11, 2) * 1, s.substr(14, 2) * 1, 0);
  } else if (s.indexOf(":") == 13 && s.indexOf(":", 15) == 16 && s.length == 19) {
    return new Date(s.substr(0, 4) * 1, s.substr(5, 2) * 1 - 1,
        s.substr(8, 2) * 1, s.substr(11, 2) * 1, s.substr(14, 2) * 1, s.substr(
            17, 2) * 1);
  }
  return null;
}

function getTimelineDateTime() {
  if (timelineDateTime != undefined) {
    return timelineDateTime;
  } else {
    return getDateTime(new Date());
  }
}

function placeUser(element) {
  if (blindPlace) {
    $("#blind").hide();
    blindPlace = false;
  }

  if ((element != currentPlace) || (timePlaceUpdates > 0)) {
    var event = View.dashboard.getEntry(element);
    if (event != null) {
      var color = View.dashboard.getColor(element);
      if (("" + color).length != 7) {
        debug("color-failed of " + $(element).html() + " is " + color);
        alert("failed: color is " + color);
      } else {
        var datetime = getTimelineDateTime();

        var entry = {
          "type" : 0,
          "event" : event,
          "color" : color,
          "datetime" : datetime,
          "link" : "http://martin.emphasize.de"
        };
        queue.push(entry);
        var time;
        if (timelineDateTime != undefined) {
          time = parseDateTime(timelineDateTime).getTime();
        } else {
          time = new Date().getTime();
        }
        Timeline.addEvent(time, entry);
        processQueue();
      }
    }
    currentPlace = element;
  }
  updateTitle(element);
  Avatar.jumpTo(element);
}

function processQueue() {
  while (queue.length > 0) {

    var entry = queue.pop();
    if (entry.type == 0) { // a placement
      if (("" + entry.color).length != 7) {
        alert("queue-entry color failure: " + entry.color);
      }
      userPlaceEventVersion++;
      $
          .ajax({
            url : domain + "util/ajax.php",
            type : "POST",
            async : true,
            dataType : "html",
            data : ({
              "do" : "trackEvent",
              "token" : token,
              "event" : entry.event,
              "color" : entry.color,
              "time" : entry.datetime,
              "link" : entry.link
            }),
            success : function(msg) {
              Progress
                  .showStatus(
                      false,
                      "<i18n key='mph2'><en>Starting time registration for</en><de>Beginn der Zeiterfassung für</de><fr>À partir de l'enregistrement du temps pour</fr><es>A partir de registro de tiempo para</es></i18n>&nbsp;\""
                          + entry.event + "\"");
              updateTimeline();
            },
            error : function(req, status, error) {
              Progress
                  .showStatus(
                      true,
                      error
                          + " "
                          + status
                          + ",&nbsp;<i18n key='mph3'><en>retrying later</en><de>später erneuter Versuch</de><fr>réessayer plus tard</fr><es>volver a intentar más tarde</es></i18n>");
              queue.push(entry);
            }
          });
    } else if (entry.type == 1) { // an info
      $
          .ajax({
            url : domain + "util/ajax.php",
            type : "POST",
            async : true,
            dataType : "html",
            data : ({
              "do" : "addInfo",
              "token" : token,
              "info" : entry.info,
              "time" : entry.datetime
            }),
            success : function(msg) {
              Progress
                  .showStatus(
                      false,
                      "<i18n key='mph4'><en>Info added</en><de>Info hinzugefügt</de><fr>Info ajoutée</fr><es>Información agregó</es></i18n>&nbsp;\""
                          + entry.info + "\"");
              updateTimeline();
            },
            error : function(req, status, error) {
              Progress.showStatus(true, error + " " + status
                  + ",&nbsp;<i18n ref='mph3'></i18n>");
              queue.push(entry);
            }
          });
    }
  }
}

function updateTitle(element, instantly) {
  if (element == undefined || element == null) {
    alert("error: updateTitle for undefined element (" + updateTitle.caller
        + ")");
    return;
  }

  var event = $(element).text();
  if (isLoggedIn()) {
    if (event != null && event != undefined) {
      document.title = event.replace(/&lt;/, "<").replace(/&gt;/, ">").replace(
          /&amp;/g, "&")
          + " - Emphasize (" + user + ")";
    } else {
      document.title = "Emphasize (" + user + ")";
    }
  }
}

function rightTrimmed(digits, text) {
  var s = digits + text;
  return s.substr(s.length - digits.length, digits.length);
}

function debug(text) {
  $.ajax({
    url : domain + "util/ajax.php",
    type : "POST",
    async : true,
    dataType : "html",
    data : ({
      "do" : "debug",
      "txt" : text + "\n---\n" + user
    })
  });
}

function logout() {
  $
      .ajax({
        url : domain + "util/ajax.php",
        type : "POST",
        async : false,
        dataType : "html",
        data : ({
          "do" : "logout",
          "token" : token
        }),
        success : function(msg) {
          location.replace(domain);
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  "<i18n key='mph6'><en>logout failed</en><de>Abmelden ist fehlgeschlagen</de><fr>Sortir a échoué</fr><es>Cerrar sesión no</es></i18n>: "
                      + error + " " + status);
        }
      });
}

function updateReportTime() {
  $("#reportTime").val(getDateTime(new Date()));
  return true;
}

function callAboveClose(type) {
  var obj = eval("aboves." + type);
  obj.close();
}

function isAboveOpen(type) {
  if (typeof (aboves[type]) == "undefined") {
    return false;
  }
  var obj = eval("aboves." + type);
  return (obj.close != null);
}

function showAbove(type, element, url, focusElement, w, h, embedded) {
  if (isAboveOpen(type)) {
    return false;
  }

  var x = 0;
  var y = 0;
  if ((element != undefined) && (element != null)) {
    var pos = $(element).offset();
    var rect = {
      x : pos.left,
      y : pos.top,
      w : $(element).outerWidth(true),
      h : $(element).outerHeight(true)
    };
    if (w == undefined || w == null || w > 0) {
      x = rect.x + rect.w / 2 + 1;
    } else {
      x = rect.x + w - 1;
    }
    if (x < 0) {
      x = 0;
    }
    if (h == undefined || h == null || h > 0) {
      y = rect.y + rect.h / 2 + 1;
    } else {
      y = rect.y + h - 1;
    }
    if (y < 0) {
      y = 0;
    }
  } else {
    x = ($(document).width() - (w != null || w != undefined ? w : 0)) / 2;
    y = ($(document).height() - (h != null || h != undefined ? h : 0)) / 2;
  }

  var above = $('<div id="above"></div>');
  $(above).appendTo($("body"));

  aboves[type] = {
    close : function() {
      aboves[type].close = null;
      if (isAboveOpen(type)) {
        $(above).dialog('close');
      }
      $(above).hide();
      $(above).detach();
    }
  };

  var showAboveHelp = function() {
    var dw = $(document).width();
    $(above)
        .find(".help")
        .each(
            function(index) {
              if ($(this).is(':visible')) {
                var id = $(this).attr('id');
                var p = $(this).offset();
                var pos = $(this).position();
                var w = $(this).outerWidth(true);
                var h = $(this).outerHeight(true);
                var l;
                if (p.left + w / 2 < dw / 2) {
                  l = pos.left + w - 14;
                } else {
                  l = pos.left - 10;
                }
                var img = $('<img id="toggleHelp_'
                    + id
                    + '" src="graphics/help.png" width="24" height="23" onmouseover="displayHelp(\''
                    + id
                    + '\', 1900)" style="z-index:1900;position:absolute;left:'
                    + l + 'px;top:' + (pos.top + h / 2 - 11)
                    + 'px;cursor:help;"/>');
                $(img).appendTo($(this).offsetParent());
              }
            });
  };

  var completed = function() {
    $(above).find('.docu').hide();
    $(above).dialog('open');
    if ($("#showHelp").attr("src").match("help.png$") != "help.png") {
      showAboveHelp();
    }
    if ((focusElement != undefined) && (focusElement != null)) {
      window.setTimeout('$("' + focusElement + '").focus()', 100);
    }
  };

  var success = function(data) {
    var matches = data.match(/<title>(.*?)<\/title>/);
    var urlTitle;
    if (matches != null) {
      urlTitle = matches[1];
    } else {
      urlTitle = type;
    }
    $(above)
        .dialog(
            {
              position : [ x, y ],
              width : w != null || w != undefined ? Math.abs(w) : "auto",
              height : h != null || h != undefined ? Math.abs(h) : "auto",
              autoOpen : false,
              title : urlTitle,
              hide : {
                effect : 'slide',
                direction : "up"
              },
              closeText : "<i18n key='tab7'><en>close</en><de>Schließen</de><fr>finir</fr><es>final</es></i18n>",
              close : aboves[type].close
            });
    $(above).html(data);
    completed();
  };

  if ((url != undefined) && (url != null)) {
    if (isLoggedIn()) {
      $.ajax({
        "url" : url,
        dataType : "html",
        data : ({
          "token" : token
        }),
        "success" : success,
        error : function(req, status, error) {
          Progress.showStatus(true, error + " " + status);
        }
      });
    } else {
      $.ajax({
        "url" : url,
        dataType : "html",
        "success" : success,
        error : function(req, status, error) {
          Progress.showStatus(true, error + " " + status);
        }
      });
    }
  } else if ((embedded != undefined) && (embedded != null)) {
    success(embedded);
  }
  return false;
}

function getHoursHtml() {
  var now = (new Date()).getTime();
  var s = "";
  var days = Math.ceil(timelineMax / 1440);
  for ( var d = days - 1; d >= -1; d--) {
    for ( var h = 24; h > 0; h--) {
      var then = new Date();
      then.setTime(now - (d * 24 + h) * 3600000);
      var day = $.datepicker.formatDate("D", then);
      if (then.getHours() < 10) {
        s += '<span class="tHour">&nbsp;&nbsp;' + day + " " + then.getHours()
            + ':00</span>';
      } else {
        s += '<span class="tHour">' + day + " " + then.getHours()
            + ':00</span>';
      }
      if ((d == -1) && (h == 23)) { // vom aktuellen Tag nur noch die
        // aktuelle
        // Stunde+1 anzeigen
        break;
      }
    }
  }
  return s;
}

function initTimeline() {
  updateLoop();
  moveTimeTo(timelineMax + timelineHour);
}

function updateLoop() {
  if (isLoggedIn()) {
    updateTimeline();
  } else {
    updateTimelineWidth();
    $("#tHours").html(getHoursHtml());
  }
  window.setTimeout("updateLoop()", 3 * 60 * 1000);
}

function updateTimelineWidth() {
  if (!$("#time").is(':visible')) {
    return;
  }
  var pos = $("#time").position();
  var from = -pos.left + config.timelineWidth / 2;
  var to = from - config.timelineWidth / 2;
  config.timelineWidth = $("#timeline").outerWidth(true);
  if (to < 0)
    to = 0;
  if (to > config.timelineMax + timelineHour - config.timelineWidth)
    to = config.timelineMax + config.timelineHour - config.timelineWidth;
  $('#time').stop();
  $("#time").css("left", (-to) + "px");
  setTimelineDateTime(timelineDateTime);
}

function updateTimeline() {
  var now = new Date();
  var before = new Date();
  before.setTime(now.getTime() - timelineMax * 60000);
  var s = Timeline.render(before.getTime(), now.getTime(), 60);
  $("#timeline").html(s);
  updateTimelineWidth();
  /*
   * $("#tHours").html(getHoursHtml()); if (isLoggedIn()) { $.ajax({ url :
   * domain + "util/ajax.php", type : "POST", async : true, dataType : "html",
   * data : ({ "do" : "getTimelineHistory", "token" : token, "now" :
   * getDateTime(now), "before" : getDateTime(before) }), success :
   * function(msg) { if (msg == "logged-out.") { logout(); return; }
   * $("#tLine").css({ 'background-position' : (-before.getMinutes()) + 'px
   * 0px', 'left' : (before.getMinutes() - 60) + 'px' }); $("#tLine").html(msg);
   * 
   * if (timelineLastEdit < (new Date()).getTime() - 60000) {
   * moveTimeTo(timelineMax + timelineHour); $("#reportTo").val(getDateTime(new
   * Date()).substr(0, 10)); } }, error : function(req, status, error) {
   * Progress.showStatus(true, error + " " + status); } }); }
   */
  // are there failed entries to resend?
  processQueue();
}

function moveTimeTo(x) {
  if (x == undefined) {
    x = timelineMax + timelineHour;
  }

  if (!$("#time").is(':visible')) {
    return;
  }

  var newTimelineDateTime;
  var now = new Date();

  if (x > timelineMax + 20 + now.getMinutes()) {
    x = timelineMax + 20 + now.getMinutes();
    newTimelineDateTime = undefined;
    // updateTimelineMax(Math.ceil(timelineMax/1440)+1);
  } else {
    if (x < 20 + now.getMinutes()) {
      x = 20 + now.getMinutes();
    }
    var mins = timelineMax + 20 + now.getMinutes() - x;
    var before = new Date();
    before.setTime(now.getTime() - mins * 60000 - now.getSeconds() * 1000);
    newTimelineDateTime = getDateTime(before);
    // if (x > timelineMax+20+now.getMinutes()-60) {
    // updateTimelineMax(Math.ceil(timelineMax/1440)+1);
    // }
  }

  moveTimeline(x);
  setTimelineDateTime(newTimelineDateTime);
}

function moveTimeline(x) {
  if (!$("#time").is(':visible')) {
    return;
  }
  var pos = $("#time").position();
  var from = pos.left;
  if (Math.abs(x + (from - config.timelineWidth / 2)) > config.timelineWidth / 3) {
    var to = x - config.timelineWidth / 2;
    if (to < 0)
      to = 0;
    if (to > timelineMax + timelineHour - config.timelineWidth)
      to = timelineMax + timelineHour - config.timelineWidth;
    $('#time').stop();
    $("#time").animate({
      left : Math.floor(-to) + "px"
    }, {
      "queue" : "false",
      "duration" : "slow"
    });
  }
}

function setTimelineDateTime(newTimelineDateTime) {

  var change = false;
  if (timelineDateTime != newTimelineDateTime) {
    change = true;
  }

  timelineDateTime = newTimelineDateTime;
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

  if (timelineDateTime != undefined) {
    // "X" anzeigen
    $("#closeTimeEditor").fadeIn();
  } else {
    // editor normal anzeigen
    $("#closeTimeEditor").fadeOut();
  }

  if (change) {
    timePlaceUser();
  }
}

function timePlaceUser() {
  timePlaceUpdates++;
  var currentPlaceEventVersion = userPlaceEventVersion;
  $.ajax({
    url : domain + "util/ajax.php",
    type : "POST",
    async : true,
    dataType : "html",
    data : ({
      "do" : "getPlace",
      "token" : token,
      "time" : getTimelineDateTime()
    }),
    success : function(msg) {
      timePlaceUpdates--;
      if (currentPlaceEventVersion == userPlaceEventVersion) {
        initPlaceUser(msg);
      }
    },
    error : function(req, status, error) {
      timePlaceUpdates--;
      Progress.showStatus(true, error + " " + status);
    }
  });
}

function submitFeedback() {
  $
      .ajax({
        type : "POST",
        url : domain + "util/feedback.php",
        data : ({
          "type" : $('#feedbackType').get(0).value,
          "message" : $('#feedMessage').get(0).value,
          "user" : user,
          "from" : email,
          "lang" : $('#feedbackLang').get(0).value
        }),
        success : function(msg) {
          if (isAboveOpen("feedback")) {
            callAboveClose("feedback");
          }
          Progress
              .showStatus(
                  false,
                  "<i18n key='mph7'><en>Feedback transmitted</en><de>Feedback übermittelt</de><fr>Commentaires transmis</fr><es>Comentarios de transmisión</es></i18n>");
        },
        error : function(req, status, error) {
          Progress.showStatus(true, error + " " + status);
        }
      });
  return false;
}

function checkTimeText(event, str) {
  var code = 0;
  if (event != null) {
    code = event.keyCode ? event.keyCode : event.charCode;
  }
  if (event == null || code == 13) {
    var p = parseDateTime(str);

    if (p != null) {
      if (p.getTime() >= new Date().getTime()) {
        setTimelineDateTime(undefined);
      } else {
        if (p.getTime() < new Date().getTime() - timelineMax * 60000) {
          updateTimelineMax(Math
              .ceil((new Date().getTime() - p.getTime()) / 86400000));
          p.setTime(new Date().getTime() - timelineMax * 60000);
        }
        setTimelineDateTime(getDateTime(p));
      }
    }
  }
}

function checkFeedbackType() {
  if ($('#feedbackType').get(0).value == 'none') {
    $('#feedbackSubmit').get(0).disabled = true;
  } else {
    $('#feedbackSubmit').get(0).disabled = false;
  }
}

function switchLang(lang) {
  if (isLoggedIn()) {
    $
        .ajax({
          url : domain + "util/ajax.php",
          type : "POST",
          async : true,
          dataType : "html",
          data : ({
            "do" : "setLang",
            "token" : token,
            "lang" : lang
          }),
          success : function(msg) {
            location.replace(location.href);
          },
          error : function(req, status, error) {
            Progress
                .showStatus(
                    true,
                    error
                        + " "
                        + status
                        + ",&nbsp;<i18n key='mph8'><en>Setting language failed</en><de>Setzen der Sprache fehlgeschlagen</de><fr>Réglage de la langue n'a pas succédé</fr><es>Configuración de idioma no</es></i18n>");
          }
        });
  } else {
    location.replace(domain + "?lang=" + lang);
  }
}

function toggleShowHelp() {
  if ($("#showHelp").attr("src").match("help.png$") == "help.png") {
    swapAltTitle("#showHelp");
    $("#showHelp").attr("src", domain + "graphics/helping.png");
    var dw = $(document).width();
    $(".help")
        .each(
            function(index) {
              if ($(this).is(':visible')) {
                var id = $(this).attr('id');
                var p = $(this).offset();
                var pos = $(this).position();
                var w = $(this).outerWidth(true);
                var h = $(this).outerHeight(true);
                var l;
                if (p.left + w / 2 < dw / 2) {
                  l = pos.left + w - 14;
                } else {
                  l = pos.left - 10;
                }
                var img = $('<img id="toggleHelp_'
                    + id
                    + '" src="graphics/help.png" width="24" height="23" onmouseover="displayHelp(\''
                    + id
                    + '\', 900)" style="z-index:900;position:absolute;left:'
                    + l + 'px;top:' + (pos.top + h / 2 - 11)
                    + 'px;cursor:help;"/>');
                $(img).appendTo($(this).offsetParent());
              }
            });
  } else {
    if (currentHelpId != undefined) {
      hideHelp(currentHelpId, 1900);
      currentHelpId = undefined;
    }
    swapAltTitle("#showHelp");
    $("#showHelp").attr("src", domain + "graphics/help.png");
    $(".docu").hide();
    $(".help").each(function(index) {
      var id = $(this).attr('id');
      $("#toggleHelp_" + id).detach();
    });
  }
}

function displayHelp(id, z) {
  if (currentHelpId != undefined) {
    hideHelp(currentHelpId, z);
  }
  currentHelpId = id;
  var dw = $(document).width();
  var dh = $(document).height();
  var hp = $("#toggleHelp_" + id).offset();
  var dir; // 2=up,right; 3=down,right; 0=up,left; 1=down,left
  if (hp.left < dw / 2) {
    if (hp.top < dh / 2) {
      dir = 3;
    } else {
      dir = 2;
    }
  } else {
    if (hp.top < dh / 2) {
      dir = 1;
    } else {
      dir = 0;
    }
  }

  $("#toggleHelp_" + id).attr("src", domain + "graphics/helping.png");
  $("#toggleHelp_" + id).css("z-index", z + 2);
  var p = $("#toggleHelp_" + id).position();
  var w = $("#help_" + id).outerWidth(true);
  var h = $("#help_" + id).outerHeight(true);
  $("#help_" + id).removeClass("docuDir0");
  $("#help_" + id).removeClass("docuDir1");
  $("#help_" + id).removeClass("docuDir2");
  $("#help_" + id).removeClass("docuDir3");
  $("#help_" + id).addClass("docuDir" + dir);
  if (dir == 0)
    $("#help_" + id).css({
      "left" : (p.left - w + 26) + "px",
      "top" : (p.top - h + 26) + "px",
      "z-index" : z + 1
    });
  else if (dir == 1)
    $("#help_" + id).css({
      "left" : (p.left - w + 26) + "px",
      "top" : (p.top - 2) + "px",
      "z-index" : z + 1
    });
  else if (dir == 2)
    $("#help_" + id).css({
      "left" : (p.left - 2) + "px",
      "top" : (p.top - h + 26) + "px",
      "z-index" : z + 1
    });
  else if (dir == 3)
    $("#help_" + id).css({
      "left" : (p.left - 2) + "px",
      "top" : (p.top - 2) + "px",
      "z-index" : z + 1
    });
  $("#help_" + id).mouseout(function() {
    hideHelp(id, z);
  });
  $("#help_" + id).show();
}

function hideHelp(id, z) {
  $("#toggleHelp_" + id).attr("src", domain + "graphics/help.png");
  $("#toggleHelp_" + id).css("z-index", z);
  $("#help_" + id).hide();
}

function setAvatar(avatar) {
  $
      .ajax({
        url : domain + "util/pawn.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "setAvatar",
          "token" : token,
          "avatar" : avatar
        }),
        success : function(msg) {
          if (isAboveOpen("settings")) {
            callAboveClose("settings");
          }
          $("#shadow").detach();
          $("#avatar").detach();
          $(msg).appendTo($("body"));
          if (currentPlace != undefined) {
            updateTitle(currentPlace);
            Avatar.showIn(currentPlace);
          }
          $("#shadow").hide().load(function() {
            $("#shadow").fadeIn();
            if (currentPlace != undefined) {
              updateTitle(currentPlace);
              Avatar.showIn(currentPlace);
            }
          });
          $("#user").hide().load(function() {
            $("#user").fadeIn();
            if (currentPlace != undefined) {
              updateTitle(currentPlace);
              Avatar.showIn(currentPlace);
            }
          });
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ",&nbsp;<i18n key='mph9'><en>setting pawn failed</en><de>austausch der Spielfigur fehlgeschlagen</de><fr>remplacement du caractère échoué</fr><es>la sustitución del carácter no</es></i18n>");
        }
      });
}

function setBaseHref(baseHref) {
  $
      .ajax({
        url : domain + "util/ajax.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "setBaseHref",
          "token" : token,
          "baseHref" : baseHref
        }),
        success : function(msg) {
          if (isAboveOpen("settings")) {
            callAboveClose("settings");
          }
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ",&nbsp;<i18n key='mph19'><en>setting  failed</en><de>fehlgeschlagen</de><fr>échoué</fr><es>la sustitución no</es></i18n>");
        }
      });
  return false;
}

function deleteAvatar(avatar) {
  $
      .ajax({
        url : domain + "util/pawn.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "deleteAvatar",
          "token" : token,
          "avatar" : avatar
        }),
        success : function(msg) {
          $('#avatars').load(domain + "util/avatars.php");
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ",&nbsp;<i18n key='mph10'><en>deleting personal pawn failed</en><de>Löschen der Spielfigur fehlgeschlagen</de><fr>Supprimer du caractère échoué</fr><es>Eliminar del carácter no</es></i18n>");
        }
      });
}

function isLoggedIn() {
  return (typeof (window["token"]) != "undefined"
      && typeof (window["user"]) != "undefined" && user != "");
}

function swapAltTitle(el) {
  var alt = $(el).attr("alt");
  $(el).attr({
    "alt" : $(el).attr("title"),
    "title" : alt
  });
}

function createTempl() {
  var desc = $("#descTemplate").get(0).value;
  $("#createTemplate").attr("disabled", true);
  var tbody = View.dashboard.getTableHtml();
  $
      .ajax({
        url : domain + "util/templates.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "createTemplate",
          "token" : token,
          "name" : desc,
          "tbody" : tbody
        }),
        success : function(msg) {
          $("#templateSelectSpan").html(msg);
          Progress
              .showStatus(
                  false,
                  "<i18n key='mph11'><en>template created</en><de>Vorlage angelegt</de><fr>Modèle créé</fr><es>Plantilla creada</es></i18n>");
          $("#descTemplate").get(0).value = "";
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ",&nbsp;<i18n key='mph12'><en>template creation failed</en><de>Vorlage anlegen fehlgeschlagen</de><fr>Modèle omis de créer</fr><es>Plantilla creada no</es></i18n>");
        }
      });
  return false;
}

function loadTempl() {

  var key = $("#templateSelect").get(0).value;
  if ((key == "reset") && (editReset != undefined)) {
    View.dashboard.setTableHtml(editReset);
  } else {
    $
        .ajax({
          url : domain + "util/templates.php",
          type : "POST",
          async : true,
          dataType : "html",
          data : ({
            "do" : "loadTemplate",
            "token" : token,
            "key" : key
          }),
          success : function(msg) {
            View.dashboard.setTableHtml(msg);
            Progress
                .showStatus(
                    false,
                    "<i18n key='mph13'><en>template loaded</en><de>Vorlage geladen</de><fr>Modèle chargé</fr><es>Plantilla cargada</es></i18n>");
          },
          error : function(req, status, error) {
            Progress
                .showStatus(
                    true,
                    error
                        + " "
                        + status
                        + ",&nbsp;<i18n key='mph14'><en>template loading failed</en><de>Vorlage laden fehlgeschlagen</de><fr>Modèle omis de charger</fr><es>cargar Plantilla no</es></i18n>");
          }
        });
  }
  return false;
}

function removeTempl() {
  var key = $("#templateSelect").get(0).value;
  $("#removeTemplate").attr("disabled", true);
  $
      .ajax({
        url : domain + "util/templates.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "removeTemplate",
          "token" : token,
          "key" : key
        }),
        success : function(msg) {
          $("#templateSelectSpan").html(msg);
          Progress
              .showStatus(
                  false,
                  "<i18n key='mph15'><en>removed template</en><de>Vorlage entfernt</de><fr>Modèle enlevé</fr><es>Plantilla eliminado</es></i18n>");
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ",&nbsp;<i18n key='mph16'><en>removing template failed</en><de>Vorlage entfernen fehlgeschlagen</de><fr>Modèle omis d'enlever</fr><es>Plantilla fallo al borrar</es></i18n>");
        }
      });
  return false;
}

function checkTemplateName() {
  if ($("#descTemplate").get(0).value.replace(/^[\s\xA0]+/, "").replace(
      /[\s\xA0]+$/, "").length == 0) {
    $("#createTemplate").attr("disabled", true);
  } else {
    $("#createTemplate").attr("disabled", false);
  }
}

function checkRemoveTemplate(offset) {
  if ($("#templateSelect").get(0).selectedIndex < offset) {
    $("#removeTemplate").attr("disabled", true);
  } else {
    $("#removeTemplate").attr("disabled", false);
  }
}

function tubeTutorial(yt) {
  var player = '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/'
      + yt
      + '?hl=de&fs=1&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'
      + yt
      + '?hl='
      + lang
      + '&fs=1&autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>';
  if (isAboveOpen("Tutorial")) {
    callAboveClose("Tutorial");
  }
  showAbove("Tutorial", null, null, null, 640 + 40, 385 + 60, player);
}

function clickThrough(x, y) {
  var element = View.dashboard.findTdAt(x, y);
  if (element != null) {
    placeUser(element);
  }
}

function exportTempl(key, tbody) {
  $('iframe#exporter').detach();
  var exporter = $('<iframe id="exporter" src="'
      + domain
      + 'util/export.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');
  $(exporter).appendTo($("body"));
  $('iframe#exporter').load(function() {
    var context = this.contentWindow.document;
    $("#exporterToken", context).val(token);
    $("#exporterKey", context).val(key);
    $("#exporterTbody", context).val(tbody);
    $("#exporterSubmit", context).click();
    window.setTimeout("$('iframe#exporter').detach();", 5000);
  });
}
