/**
 * @class Avatar class for displaying and animating the user pawn.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Avatar = {
  id : null,
  init : function() {
    this.id = "avatar";
  },
  jumpTo : function(element) {
    var r = {
      x : $(element).offset().left,
      y : $(element).offset().top,
      w : $(element).width(),
      h : $(element).height()
    };
    var avatar = $("#avatar").get(0);
    var w = $("#user").width();
    var h = $("#user").height();
    var hx = Math.max(h / 3, h - w / 3);
    var ow = h * 0.5;
    var g = 3;
    avatar.step = 0;
    avatar.from = $("#avatar").offset();
    avatar.to = {
      left : Math.floor(r.x + r.w / 2 - w / 2),
      top : Math.min(r.y + r.h - h, Math.floor(r.y + r.h / 2 - 14 - hx))
    };
    if (avatar.from.left == avatar.to.left && avatar.from.top == avatar.to.top) {
      return;
    }
    $("#avatar").stop();
    $("#avatar").css({
      width : w + "px",
      height : h + "px"
    });
    $("#shadow").show();
    $("#avatar").show();
    $("#avatar").animate(
        {
          "step" : 100
        },
        {
          duration : "slow",
          step : function(step) {
            var p = step / 100.0;
            var _p = (100 - step) / 100.0;
            var elevate = (2500 - (step - 50) * (step - 50)) / 2500;
            var ax = Math.floor(avatar.from.left * _p + avatar.to.left * p);
            var ay = Math.floor(avatar.from.top * _p + avatar.to.top * p
                - elevate * 30);
            $("#avatar").css({
              left : ax + "px",
              top : ay + "px"
            });
            $("#shadow").css({
              left : Math.round(ax - ow - g - elevate * 15) + "px",
              top : Math.round(ay - g + elevate * 15) + "px"
            });
          }
        });
  },
  showIn : function(element) {
    var r = {
      x : $(element).offset().left,
      y : $(element).offset().top,
      w : $(element).width(),
      h : $(element).height()
    };
    var avatar = $("#avatar").get(0);
    var w = $("#user").width();
    var h = $("#user").height();
    var hx = Math.max(h / 3, h - w / 3);
    var ow = h * 0.5;
    var g = 3;
    avatar.step = 0;
    avatar.from = $("#avatar").offset();
    avatar.to = {
      left : Math.floor(r.x + r.w / 2 - w / 2),
      top : Math.min(r.y + r.h - h, Math.floor(r.y + r.h / 2 - 14 - hx))
    };
    if (avatar.from.left == avatar.to.left && avatar.from.top == avatar.to.top) {
      return;
    }
    $("#avatar").stop();
    $("#avatar").css({
      width : w + "px",
      height : h + "px"
    });
    $("#shadow").show();
    $("#avatar").show();

    $("#avatar").css({
      left : avatar.to.left + "px",
      top : avatar.to.top + "px"
    });
    $("#shadow").css({
      left : (avatar.to.left - ow - g) + "px",
      top : (avatar.to.top - g) + "px"
    });
  },
  hide : function() {
    $("#avatar").hide();
    $("#shadow").hide();
  }
}