/**
 * @author captain-redbeard
 * @since 13/01/17
 */

function reload(m, p, a, c) {
    var x;
    window.XMLHttpRequest ? x = new XMLHttpRequest() : x = new ActiveXObject("Microsoft.XMLHTTP");
    x.onreadystatechange=function(){ if(x.readyState == 4 && x.status == 200) document.getElementById(c).innerHTML = x.responseText; }
    x.open(m ? "POST" : "GET", p, true);
    x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    x.send(a);
}

function gameAction(el) {
    if ($(el).data("function") != null) {
        reload(false, "home/index/" + $(el).data("function") + "/" + $(el).data("action") + "/raw", null, "content");    
    }
    
    return false;
}

$(document).on("click", "[name='game-action']", function(event) {
    event.preventDefault();
    gameAction(this);
});
