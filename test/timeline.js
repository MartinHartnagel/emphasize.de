testPage('/', function() {
  module("Timeline");

  var t = testFrame.Timeline;

  test('Access', function() {
    t.addEvent(0, "haha");
    t.addEvent(10, "ho");
    t.addEvent(20, "hö");
    t.addEvent(30, null);
    t.addEvent(40, "hui");

    equal(t.getEventAt(0).event, "haha", "timeline getEventAt");
    equal(t.getEventAt(9).event, "haha", "timeline getEventAt");
    equal(t.getEventAt(10).event, "ho", "timeline getEventAt");
    equal(t.getEventAt(15).event, "ho", "timeline getEventAt");
    equal(t.getEventAt(21).event, "hö", "timeline getEventAt");
    equal(t.getEventAt(29).event, "hö", "timeline getEventAt");
    equal(t.getEventAt(30).event, null, "timeline getEventAt");
    equal(t.getEventAt(39).event, null, "timeline getEventAt");
    equal(t.getEventAt(40).event, "hui", "timeline getEventAt");
    equal(t.getEventAt(444).event, "hui", "timeline getEventAt");

    t.addEvent(35, "hi");
    equal(t.getEventAt(35).event, "hi", "timeline getEventAt");
  });

  test('Adding', function() {
    t.clear();
    t.addEvent(5, "a");
    t.addEvent(10, "b");
    t.addEvent(20, "c");
    t.addEvent(30, null);
    t.addEvent(40, "e");
    equal(t.shortDescription(), "a, b, c, null, e", "timeline as expected");

    t.addEvent(40, "d");
    equal(t.shortDescription(), "a, b, c, null, d", "timeline as expected");

    t.addEvent(41, "g");
    equal(t.shortDescription(), "a, b, c, null, d, g", "timeline as expected");

    t.addEvent(30, "h");
    equal(t.shortDescription(), "a, b, c, h, d, g", "timeline as expected");

    t.addEvent(15, "f");
    equal(t.shortDescription(), "a, b, f, c, h, d, g", "timeline as expected");

    t.addEvent(15, "i");
    equal(t.shortDescription(), "a, b, i, c, h, d, g", "timeline as expected");

    t.addEvent(0, "x");
    equal(t.shortDescription(), "x, a, b, i, c, h, d, g",
        "timeline as expected");

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

  test('getEventAt', function() {
    t.clear();
    t.addEvent(5, "a", 1000);
    t.addEvent(10, "b", 1010);
    t.addEvent(20, "c", 1062);
    t.addEvent(30, "d", 1013);
    t.addEvent(40, "e", 1046);

    equal(t.getEventAt(3), null, "before first entry");
    equal(t.getEventAt(5).event, "a", "at first entry");
    equal(t.getEventAt(7).event, "a", "after first entry");
    equal(t.getEventAt(10).event, "b", "at second entry");
    equal(t.getEventAt(12).event, "b", "after second entry");
  });

  test('getEventAfter', function() {
    t.clear();
    t.addEvent(5, "a", 1000);
    t.addEvent(10, "b", 1010);
    t.addEvent(20, "c", 1062);
    t.addEvent(30, null, 1013);
    t.addEvent(40, "e", 1046);

    equal(t.getEventAfter(3).event, "a", "before first entry");
    equal(t.getEventAfter(5).event, "b", "at first entry");
    equal(t.getEventAfter(7).event, "b", "after first entry");
    equal(t.getEventAfter(10).event, "c", "at second entry");
    equal(t.getEventAfter(12).event, "c", "after second entry");
    equal(t.getEventAfter(29).event, null, "after third entry");
    equal(t.getEventAfter(30).event, "e", "at fourth entry");
    equal(t.getEventAfter(35).event, "e", "after fourth entry");
    equal(t.getEventAfter(40), null, "at fifth entry");
    equal(t.getEventAfter(45), null, "after fifth entry");
  });
});
