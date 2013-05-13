testPage('/', function() {
  module("Timeline");

  var t = testFrame.Timeline;

  test('Access', function() {
    t.events.clear();
    t.events.add(0, "haha");
    t.events.add(10, "ho");
    t.events.add(20, "hö");
    t.events.add(30, null);
    t.events.add(40, "hui");

    equal(t.events.getAt(0).object, "haha", "timeline getEventAt");
    equal(t.events.getAt(9).object, "haha", "timeline getEventAt");
    equal(t.events.getAt(10).object, "ho", "timeline getEventAt");
    equal(t.events.getAt(15).object, "ho", "timeline getEventAt");
    equal(t.events.getAt(21).object, "hö", "timeline getEventAt");
    equal(t.events.getAt(29).object, "hö", "timeline getEventAt");
    equal(t.events.getAt(30).object, null, "timeline getEventAt");
    equal(t.events.getAt(39).object, null, "timeline getEventAt");
    equal(t.events.getAt(40).object, "hui", "timeline getEventAt");
    equal(t.events.getAt(444).object, "hui", "timeline getEventAt");

    t.events.add(35, "hi");
    equal(t.events.getAt(35).object, "hi", "timeline getEventAt");
  });

  test('Adding', function() {
    t.events.clear();
    t.events.add(5, "a");
    t.events.add(10, "b");
    t.events.add(20, "c");
    t.events.add(30, null);
    t.events.add(40, "e");
    equal(t.events.shortDescription(), "a, b, c, null, e",
        "timeline as expected");

    t.events.add(40, "d");
    equal(t.events.shortDescription(), "a, b, c, null, d",
        "timeline as expected");

    t.events.add(41, "g");
    equal(t.events.shortDescription(), "a, b, c, null, d, g",
        "timeline as expected");

    t.events.add(30, "h");
    equal(t.events.shortDescription(), "a, b, c, h, d, g",
        "timeline as expected");

    t.events.add(15, "f");
    equal(t.events.shortDescription(), "a, b, f, c, h, d, g",
        "timeline as expected");

    t.events.add(15, "i");
    equal(t.events.shortDescription(), "a, b, i, c, h, d, g",
        "timeline as expected");

    t.events.add(0, "x");
    equal(t.events.shortDescription(), "x, a, b, i, c, h, d, g",
        "timeline as expected");

  });

  test('Timeout Cleanup', function() {
    t.events.clear();
    t.events.add(0, "a", 1000);
    t.events.add(10, "b", 1010);
    t.events.add(20, "c", 1062);
    t.events.add(30, "d", 1013);
    t.events.add(40, "e", 1046);

    t.events.doTimeoutCleanup(1030);
    equal(t.events.shortDescription(), "c, e", "timeline cleaned up");
  });

  test('getEventAt', function() {
    t.events.clear();
    t.events.add(5, "a", 1000);
    t.events.add(10, "b", 1010);
    t.events.add(20, "c", 1062);
    t.events.add(30, "d", 1013);
    t.events.add(40, "e", 1046);

    equal(t.events.getAt(3), null, "before first entry");
    equal(t.events.getAt(5).object, "a", "at first entry");
    equal(t.events.getAt(7).object, "a", "after first entry");
    equal(t.events.getAt(10).object, "b", "at second entry");
    equal(t.events.getAt(12).object, "b", "after second entry");
  });

  test('getEventAfter', function() {
    t.events.clear();
    t.events.add(5, "a", 1000);
    t.events.add(10, "b", 1010);
    t.events.add(20, "c", 1062);
    t.events.add(30, null, 1013);
    t.events.add(40, "e", 1046);

    equal(t.events.getAfter(3).object, "a", "before first entry");
    equal(t.events.getAfter(5).object, "b", "at first entry");
    equal(t.events.getAfter(7).object, "b", "after first entry");
    equal(t.events.getAfter(10).object, "c", "at second entry");
    equal(t.events.getAfter(12).object, "c", "after second entry");
    equal(t.events.getAfter(29).object, null, "after third entry");
    equal(t.events.getAfter(30).object, "e", "at fourth entry");
    equal(t.events.getAfter(35).object, "e", "after fourth entry");
    equal(t.events.getAfter(40), null, "at fifth entry");
    equal(t.events.getAfter(45), null, "after fifth entry");

  });
});
