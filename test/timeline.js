testPage('/', function() {
  module("Timeline");

  var t = testFrame.Timeline;

  test('Access', function() {
    t.addEvent(0, "haha");
    t.addEvent(10, "ho");
    t.addEvent(20, "hö");
    t.addEvent(30, null);
    t.addEvent(40, "hui");

    equal(t.getEventAt(0), "haha", "timeline getEventAt");
    equal(t.getEventAt(9), "haha", "timeline getEventAt");
    equal(t.getEventAt(10), "ho", "timeline getEventAt");
    equal(t.getEventAt(15), "ho", "timeline getEventAt");
    equal(t.getEventAt(21), "hö", "timeline getEventAt");
    equal(t.getEventAt(29), "hö", "timeline getEventAt");
    equal(t.getEventAt(30), null, "timeline getEventAt");
    equal(t.getEventAt(39), null, "timeline getEventAt");
    equal(t.getEventAt(40), "hui", "timeline getEventAt");
    equal(t.getEventAt(444), "hui", "timeline getEventAt");

    t.addEvent(35, "hi");
    equal(t.getEventAt(35), "hi", "timeline getEventAt");
  });

  test('Timeout Cleanup', function() {
    t.clear();
    t.addEvent(0, "a", 1000);
    t.addEvent(10, "b", 1010);
    t.addEvent(20, "c", 1062);
    t.addEvent(30, "d", 1013);
    t.addEvent(40, "e", 1046);

    t.doTimeoutCleanup(1030);
    equal(t.shortDescription(), "c, e", "timeline cleaned up");
  });
});
