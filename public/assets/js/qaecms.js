var QAECMS;

QAECMS = {
    playnext: function () {
        let nowhref = window.location.href;
        if(nowhref.indexOf("?") != -1){
            nowhref = nowhref.split("?")[0];
        }
        let now = document.getElementsByTagName('iframe')[0].attributes['now'].nodeValue;
        let url = nowhref+"?vid="+now+"&playaction=next"
        window.location = url;
    },
    playprev: function () {
        let nowhref = window.location.href;
        if(nowhref.indexOf("?") != -1){
            nowhref = nowhref.split("?")[0];
        }
        let now = document.getElementsByTagName('iframe')[0].attributes['now'].nodeValue;
        let url = nowhref+"?vid="+now+"&playaction=prev"
        window.location = url;
    }
}
