var currentPlace = undefined;
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
var lang = "<lang/>";
var domain = "<domain/>";
var email = null;
var user = null;
var token = null;

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
  edit : function(content, handlers) {
    $.ajax({
      url : domain + "/util/ajax.php",
      type : "POST",
      async : true,
      dataType : "html",
      data : ({
        "do" : "updateContent",
        "token" : token,
        "content" : content
      }),
      success : handlers.success,
      error : handlers.error
    });
  },
  color : function(fullSrc, x, y, setColor) {
    var src = fullSrc.substr(domain.length + 1);
    $.get(domain + "/util/rgb.php", {
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
    Model
        .edit(
            View.dashboard.metaballs.save(),
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
                            + ", <i18n key='mph0'><en>Storing partitioning of fields failed</en><de>Speichern der Feldaufteilung fehlgeschlagen</de><fr>Sauvegarder les partitions a échoué</fr><es>Guardar las particiones no</es></i18n>");
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
    Timeline.init();
    if ($("#tabs").is(':visible')) {
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
                panelTemplate : '<div id="fields" class="help"></div>'
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
      $("#tabs span.ui-icon-disk").on("click", function() {
        var tabTitle = $(this).parent().parent().find("a").text();
        exportTempl(tabTitle, View.dashboard.metaballs.save());
      });
      $("#tabs span.ui-icon-trash").on("click", function() {
        var index = $("li", tabs).index($(this).parent().parent());
        tabs.tabs("remove", index);
      });
    }
  },
  addPlaceHandler : function(handler) {
    this.dashboard.setOnPlaced(handler);
  },
  addEditHandler : function(handler) {
    this.dashboard.setOnEdited(handler);
  },
  sizing : function() {
    if ($("#tabs").is(':visible')) {
      var wh = $(window).height();
      var h = $(".ui-tabs-panel:visible").offset().top
          + $(".ui-tabs-panel:visible").outerHeight(true)
          - $(".ui-tabs-panel:visible").height() + 10;
      $(".ui-tabs-panel").height(Math.floor(wh - h));
    }
    View.dashboard.updateEdits();
  }
};

$(document).ready(function() {
  user = $("#user").val();
  email = $("#email").val();
  token = $("#token").val();
  View.dashboard = new Dashboard();
  Presenter.init();
  initView();
});

function dereferLink(url) {
  $('iframe#derefer').detach();
  var derefer = $('<iframe id="derefer" src="'
      + domain
      + '/util/derefer.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');
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
  $(window).resize(function() {
    View.sizing();
    if (currentPlace != undefined) {
      updateTitle(currentPlace);
      Avatar.showIn(currentPlace);
    }
    // updateTimelineWidth
    Timeline.setCursor(Timeline.cursor);
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
      var entry = {
        "type" : 1,
        "info" : $("#info").val(),
        "datetime" : Timeline.getDateTime()
      };
      $("#info").val("");
      queue.push(entry);
      processQueue();

      e.preventDefault();
      $("#info").blur();
    }
  });

  $("#shadow").bind('click', function(e) {
    clickThrough({x: e.pageX, y: e.pageY});
  });
  $("#avatar").bind('click', function(e) {
    clickThrough({x: e.pageX, y: e.pageY});
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
  $('a').on('click', function() {
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
  // disable timeline selection
  $('.timeline').find('*').attr('unselectable', 'on').css('MozUserSelect',
      'none');
}

function initReport() {
  $('.docu').hide();
  $('a').on('click', function() {
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
  var element = View.dashboard.metaballs.find(event);
  if (element != null) {
    currentPlace = element;
    updateTitle(element);
    Avatar.showIn(element);
  } else if (event.length > 0) {
    $("#blind").html(
        '<div class="border" style="padding:15pt 0pt 0pt 0pt;height:35pt;">'
            + event + '</div>');
    $("#blind").css({
      "background" : "url(" + domain + "/graphics/blind.png)",
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

function placeUser(element) {
  if (blindPlace) {
    $("#blind").hide();
    blindPlace = false;
  }

  if ((element != currentPlace) || (timePlaceUpdates > 0)) {
    var ball = View.dashboard.metaballs.get(element);
    if (ball != null) {
      var entry = {
        "type" : 0,
        "event" : ball.text,
        "color" : ball.color,
        "datetime" : Timeline.getDateTime(),
        "link" : "http://martin.emphasize.de"
      };
      queue.push(entry);
      var time;
      if (Timeline.cursor != null) {
        time = Timeline.cursor;
      } else {
        time = new Date().getTime();
      }
      Timeline.events.add(time, entry);
      processQueue();
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
            url : domain + "/util/ajax.php",
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
                      "<i18n key='mph2'><en>Starting time registration for</en><de>Beginn der Zeiterfassung für</de><fr>À partir de l'enregistrement du temps pour</fr><es>A partir de registro de tiempo para</es></i18n> \""
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
                          + ", <i18n key='mph3'><en>retrying later</en><de>später erneuter Versuch</de><fr>réessayer plus tard</fr><es>volver a intentar más tarde</es></i18n>");
              queue.push(entry);
            }
          });
    } else if (entry.type == 1) { // an info
      $
          .ajax({
            url : domain + "/util/ajax.php",
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
                      "<i18n key='mph4'><en>Info added</en><de>Info hinzugefügt</de><fr>Info ajoutée</fr><es>Información agregó</es></i18n> \""
                          + entry.info + "\"");
              updateTimeline();
            },
            error : function(req, status, error) {
              Progress.showStatus(true, error + " " + status
                  + ", <i18n ref='mph3'></i18n>");
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
          + " - <app_name/> (" + user + ")";
    } else {
      document.title = "<app_name/> (" + user + ")";
    }
  }
}

function rightTrimmed(digits, text) {
  var s = digits + text;
  return s.substr(s.length - digits.length, digits.length);
}

function debug(text) {
  $.ajax({
    url : domain + "/util/ajax.php",
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
        url : domain + "/util/ajax.php",
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
  $("#reportTime").val(Timeline.getDateTime(new Date()));
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

function initTimeline() {
  updateLoop();
  Timeline.setCursor(null);
}

function updateLoop() {
  if (isLoggedIn()) {
    updateTimeline();
  } else {
    // updateTimelineWidth
    Timeline.setCursor(Timeline.cursor);
  }
  window.setTimeout("updateLoop()", 3 * 60 * 1000);
}

function updateTimeline() {
  var now = new Date();
  var before = new Date();
  before.setTime(now.getTime() - 3 * 60000);

  if (isLoggedIn()) {
    $.ajax({
      url : domain + "/util/ajax.php",
      type : "POST",
      async : true,
      dataType : "json",
      data : ({
        "do" : "getTimelineHistory",
        "token" : token,
        "now" : Timeline.getDateTime(now),
        "before" : Timeline.getDateTime(before)
      }),
      success : function(data) {
        var events = data[0];
        for ( var i = 0; i < events.length; i++) {
          var entry = events[i];
          var time = Timeline.parseDateTime(entry[0]).getTime();
          var event = {
            "type" : 0,
            "event" : entry[1],
            "color" : entry[2],
            "datetime" : entry[0],
            "link" : "http://martin.emphasize.de"
          };
          Timeline.events.add(time, event);
        }
        var infos = data[1];
        for ( var i = 0; i < infos.length; i++) {
          var entry = infos[i];
          var time = Timeline.parseDateTime(entry[0]).getTime();
          var info = entry[1];
          Timeline.infos.add(time, info);
        }
        Timeline.render(before.getTime(), now.getTime());
        // updateTimelineWidth
        Timeline.setCursor(Timeline.cursor);
      },
      error : function(req, status, error) {
        Progress.showStatus(true, error + " " + status);
      }
    });
  }

  // are there failed entries to resend?
  processQueue();
}

function timePlaceUser() {
  timePlaceUpdates++;
  var currentPlaceEventVersion = userPlaceEventVersion;
  $.ajax({
    url : domain + "/util/ajax.php",
    type : "POST",
    async : true,
    dataType : "html",
    data : ({
      "do" : "getPlace",
      "token" : token,
      "time" : Timeline.getDateTime()
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
        url : domain + "/util/feedback.php",
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
    var p = Timeline.parseDateTime(str);

    if (p != null) {
      if (p.getTime() >= new Date().getTime()) {
        Timeline.setCursor(null);
      } else {
        Timeline.setCursor(p.getTime());
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
          url : domain + "/util/ajax.php",
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
                        + ", <i18n key='mph8'><en>Setting language failed</en><de>Setzen der Sprache fehlgeschlagen</de><fr>Réglage de la langue n'a pas succédé</fr><es>Configuración de idioma no</es></i18n>");
          }
        });
  } else {
    location.replace(domain + "?lang=" + lang);
  }
}

function toggleShowHelp() {
  if ($("#showHelp").attr("src").match("help.png$") == "help.png") {
    swapAltTitle("#showHelp");
    $("#showHelp").attr("src", domain + "/graphics/helping.png");
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
    $("#showHelp").attr("src", domain + "/graphics/help.png");
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

  $("#toggleHelp_" + id).attr("src", domain + "/graphics/helping.png");
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
  $("#toggleHelp_" + id).attr("src", domain + "/graphics/help.png");
  $("#toggleHelp_" + id).css("z-index", z);
  $("#help_" + id).hide();
}

function setAvatar(avatar) {
  $
      .ajax({
        url : domain + "/util/pawn.php",
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
                      + ", <i18n key='mph9'><en>setting pawn failed</en><de>austausch der Spielfigur fehlgeschlagen</de><fr>remplacement du caractère échoué</fr><es>la sustitución del carácter no</es></i18n>");
        }
      });
}

function deleteAvatar(avatar) {
  $
      .ajax({
        url : domain + "/util/pawn.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "deleteAvatar",
          "token" : token,
          "avatar" : avatar
        }),
        success : function(msg) {
          $('#avatars').load(domain + "/util/avatars.php");
        },
        error : function(req, status, error) {
          Progress
              .showStatus(
                  true,
                  error
                      + " "
                      + status
                      + ", <i18n key='mph10'><en>deleting personal pawn failed</en><de>Löschen der Spielfigur fehlgeschlagen</de><fr>Supprimer du caractère échoué</fr><es>Eliminar del carácter no</es></i18n>");
        }
      });
}

function isLoggedIn() {
  return token != null;
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
  var json = View.dashboard.save();
  $
      .ajax({
        url : domain + "/util/templates.php",
        type : "POST",
        async : true,
        dataType : "html",
        data : ({
          "do" : "createTemplate",
          "token" : token,
          "name" : desc,
          "content" : json
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
                      + ", <i18n key='mph12'><en>template creation failed</en><de>Vorlage anlegen fehlgeschlagen</de><fr>Modèle omis de créer</fr><es>Plantilla creada no</es></i18n>");
        }
      });
  return false;
}

function loadTempl() {

  var key = $("#templateSelect").get(0).value;
  if ((key == "reset") && (editReset != undefined)) {
    View.dashboard.metaballs.load(editReset);
  } else {
    $
        .ajax({
          url : domain + "/util/templates.php",
          type : "POST",
          async : true,
          dataType : "html",
          data : ({
            "do" : "loadTemplate",
            "token" : token,
            "key" : key
          }),
          success : function(msg) {
            View.dashboard.metaballs.load(msg);
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
                        + ", <i18n key='mph14'><en>template loading failed</en><de>Vorlage laden fehlgeschlagen</de><fr>Modèle omis de charger</fr><es>cargar Plantilla no</es></i18n>");
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
        url : domain + "/util/templates.php",
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
                      + ", <i18n key='mph16'><en>removing template failed</en><de>Vorlage entfernen fehlgeschlagen</de><fr>Modèle omis d'enlever</fr><es>Plantilla fallo al borrar</es></i18n>");
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

function clickThrough(pos) {
  var elements = View.dashboard.metaballs.detect(pos);
  if (elements.length > 0) {
    var next = 0;
    var previous = elements.indexOf(currentPlace);
    if (previous != -1) {
      next = (previous + 1) % elements.length;
    }
    placeUser(element[next]);
  }
}

function exportTempl(key, content) {
  $('iframe#exporter').detach();
  var exporter = $('<iframe id="exporter" src="'
      + domain
      + 'util/export.php" width="1" height="1" scrolling="none" marginheight="0" marginwidth="0" frameborder="0"/>');
  $(exporter).appendTo($("body"));
  $('iframe#exporter').load(function() {
    var context = this.contentWindow.document;
    $("#exporterToken", context).val(token);
    $("#exporterKey", context).val(key);
    $("#exporterContent", context).val(content);
    $("#exporterSubmit", context).click();
    window.setTimeout("$('iframe#exporter').detach();", 5000);
  });
}
