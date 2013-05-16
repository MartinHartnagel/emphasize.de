var step = 0;
var steps = 10;

function setName() {
  setCookieParam("name", document.login.name.value);
}

function setCookieParam(param, value) {
  var n = param + "=" + value + ";";
  document.cookie = n;
}

function getCookieParam(param) {
  if (document.cookie) {
    var chocolates = document.cookie.split(";");
    for ( var i = 0; i < chocolates.length; i++) {
      if (chocolates[i].match("^ *" + param + "=")) {
        return chocolates[i].substr(chocolates[i].indexOf('=') + 1);
      }
    }
  }
  return "";
}

function initLogin() {
  $('.docu').hide();
  if (typeof (window["confirmed_name"]) != "undefined") {
    document.login.name.value = confirmed_name;
    setName();
  } else if (document.login.name.value == "") {
    document.login.name.value = getCookieParam("name");
  }
  if (document.login.name.value.length > 0) {
    document.login.password.focus();
  }
  init();
}

var currentContent = "";

function init() {
  if ($('#fields').length == 0) {
    return;
  }
  var div=$('#fields').get(0);
  initTimeline();
  Dashboard.demo=true;
  Dashboard.init(div);
  Metaballs.demo=true;
  Metaballs.init(div);
  Metaballs.addBox();
  Metaballs.addBall();
  Metaballs.addBall();
  Metaballs.addBall();
  Metaballs.addBall();
  window.setTimeout("animate()", 2000);

  (function(window,undefined){
    var History = window.History; // Note: We are using a capital H instead of a lower h
    if ( !History.enabled ) {
        return false;
    }

    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ 
        var State = History.getState();
        History.log(State.data, State.title, State.url);
    });

    // Change our States
    /*History.pushState({state:1}, "State 1", "?state=1"); // logs {state:1}, "State 1", "?state=1"
    History.pushState({state:2}, "State 2", "?state=2"); // logs {state:2}, "State 2", "?state=2"
    History.replaceState({state:3}, "State 3", "?state=3"); // logs {state:3}, "State 3", "?state=3"
    History.pushState(null, null, "?state=4"); // logs {}, '', "?state=4"
    History.back(); // logs {state:3}, "State 3", "?state=3"
    History.back(); // logs {state:1}, "State 1", "?state=1"
    History.back(); // logs {}, "Home Page", "?"
    History.go(2); // logs {state:3}, "State 3", "?state=3"*/

})(window);
 /* 
  $(window).hashchange(function() {
    var hash = location.hash;
    if (hash.length > 1) {
      var url = hash.substr(1) + ".php";
      if (currentContent != url) {
        $.post(domain + url, {
          "ajax" : "true",
          "grep" : "bContent"
        }, function(data) {
          $("#bContent").replaceWith(data);
          currentContent = url;
        });
      }
    } else if (currentContent != "") {
      $.post(domain, {
        "ajax" : "true",
        "grep" : "bContent",
        "lang" : lang
      }, function(data) {
        $("#bContent").replaceWith(data);
        currentContent = "";
      });
    }
  });

  $(window).hashchange(); */
}

function animate() {
  if (step % steps == 0)
    Avatar.jumpTo(Metaballs.balls.i2.element);
  if (step % steps == 1)
    Avatar.jumpTo(Metaballs.balls.i1.element);
  if (step % steps == 2) {
    Dashboard.showEdits(Metaballs.balls.i2.element);
  }
  if (step % steps == 3) {
    Metaballs.balls.i2.setText('{i18n ref="con10" />');
    Metaballs.balls.i2.setColor("#ecfe32");
  }
  if (step % steps == 4) {
    Dashboard.hideEdits(Metaballs.balls.i2.element);
  }
  if (step % steps == 5)
    Avatar.jumpTo(Metaballs.balls.i2.element);
  if (step % steps == 6)
    Avatar.jumpTo(Metaballs.balls.i0.element);
  if (step % steps == 7)
    Dashboard.showEdits(Metaballs.balls.i2.element);
  if (step % steps == 8) {
    Metaballs.balls.i2.setText('{i18n ref="con11" />');
    Metaballs.balls.i2.setColor("#fffe32");
  }
  if (step % steps == 9)
    Dashboard.hideEdits(Metaballs.balls.i2.element);

  step++;
  window.setTimeout("animate()", 2000);
}

$('a.blog').on('click', function() {
  var url = $(this).attr("href");
  $.post(domain + url, {
    "ajax" : "true",
    "grep" : "bContent"
  }, function(data) {
    $("#bContent").replaceWith(data);
    window.location.hash = '#' + url.replace('.php', '');
  });
  return false;
});

$(document).ready(
    function() {
      var flashvars = false;
      var params = {
        transparent : "true",
        allowFullScreen : "true",
        allowscriptaccess : "always",
        wmode : "transparent"
      };
      var attributes = {
        style : "z-index:1"
      };

      var part;
      switch ($.trim("{lang/}")) {
      case "de":
        part = "F2A34232616B5BF1?hl=de_DE";
        break;
      case "fr":
        part = "8D1C1809D9BAB718?hl=fr_FR";
        break;
      case "es":
        part = "8D1C1809D9BAB718?hl=es_ES";
        break;
      default:
        part = "8D1C1809D9BAB718?hl=en_US";
      }

      swfobject.embedSWF('http://www.youtube.com/p/' + part + '&fs=1',
          "emphasizeTube", "420", "261", "9.0.0", null, flashvars, params,
          attributes);
    });

$(document)
    .ready(
        function() {
          var flashvars = true;
          var params = {
            menu : "false",
            scale : "noScale",
            allowscriptaccess : "always",
            wmode : "opaque",
            bgcolor : "#FFFFFF",
            allowfullscreen : "true",
            flashvars : "dataUrl={domain/}/util/delegate.php&hueMin=0&hueMax=40&satMin=0.5&satMax=0.9&lgtMin=0.97&lgtMax=0.44&iconOffset=0&defaultMetric=nb_visits&txtLoading=........loading...&txtLoadingData=loading%20data...&txtToggleFullscreen=Fullscreen&txtExportImage=Export"
          };
          var attributes = {
            style : "z-index:1"
          };

          swfobject.embedSWF(domain + '/util/worldmap.php', "worldmap", "420",
              "238", "9.0.0", null, flashvars, params, attributes);
        });