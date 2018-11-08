

$(document).ready(function(){


    function crC(e, t, s) {
        if (s) {
            var n = new Date;
            n.setTime(n.getTime() + 60 * s * 1e3);
            var i = "; expires=" + n.toUTCString()
        } else i = "";
        document.cookie = e + "=" + t + i + "; path=/"
    }

    function rdC(e) {
        for (var t = e + "=", s = document.cookie.split(";"), n = 0; n < s.length; n++) {
            for (var i = s[n];
                 " " == i.charAt(0);) i = i.substring(1, i.length);
            if (0 == i.indexOf(t)) return i.substring(t.length, i.length)
        }
        return null
    }

    function eSC(e) {
        crC(e, "", -1)
    }

    function stTM(e, t, s) {
        var n, i, d;

        function a() {
            n = t - ((Date.now() - e) / 1e3 | 0), d = n % 60 | 0, i = (i = n / 60 | 0) < 10 ? "0" + i : i, d = d < 10 ? "0" + d : d, s.textContent = i + ":" + d, n <= 0 && (clearInterval(c), document.getElementById("ct836").innerHTML = "Order reservation ended.", e = Date.now() + 1e3)
        }
        a();
        var c = setInterval(a, 1e3)
    }

            var e = 600,
                t = Date.now(),
                s = rdC("pRtC");
            s ? t < s ? e = (s - t) / 1e3 : (eSC("pRtC"), crC("pRtC", Date.now() + 1e3 * e, e + 1)) : crC("pRtC", Date.now() + 1e3 * e, e + 1), display = document.querySelector("#time"), stTM(t, e, display)

});
