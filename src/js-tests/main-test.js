function sut() {
  return jQuery("#systemUnderTest").get(0).contentWindow;
}
function $() {
  return sut().jQuery.apply(null, arguments);
}


// turn off animations so they don't break tests
jQuery.fx.off = true;


module("", {
    setup: function () {
        
    }
});

test("", function () {
    ok(true);
});
