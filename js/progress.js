/**
 * @class Progress class for displaying ongoing updates.
 * 
 * @author <a href="http://martin.emphasize.de" target="_blank">Martin Hartnagel</a>
 * 
 */
var Progress = {
  progressVersion : 0,
  init : function() {
    $("#doing").progressbar({
      value : 59
    });
    $("#doing").ajaxStart(function() {
      Progress.progressStart();
    }).ajaxComplete(
        function() {
          $("#doing").progressbar("option", "value", 100);
          window.setTimeout('Progress.progressComplete('
              + Progress.progressVersion + ')', 500);
        });
  },
  progressStart : function() {
    this.progressVersion++;
    $("#doing").progressbar("option", "value", 0);
    if (!$("#doing").is(':visible')) {
      $("#doing").fadeIn();
    }
  },
  progressApproximation : function() {
    $("#doing").progressbar("option", "value", 0);
  },
  progressComplete : function(version) {
    if (version <= this.progressVersion) {
      if ($("#doing").is(':visible')) {
        $("#doing").fadeOut();
      }
    }
  },
  showStatus : function(isError, text) {
    if (isError) {
      $("#status").html(
          "<b style=\"font-color:red\">"
              + text.replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</b>");
    } else {
      $("#status").html(text.replace(/</g, "&lt;").replace(/>/g, "&gt;"));
    }
  }

}