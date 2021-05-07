var qaecms = {
    'search': {//搜索
        'submit': function(){
            $("#qae-search button").on("click", function(){
                $action = $(this).attr('data-action');
                if($action){
                    $("#qae-search").attr('action', $action);
                }
            });
            $("#qae-search").on("submit", function(){
                $action = $(this).attr('action');
                if(!$action){
                    $action = '/' ;
                }
                $wd = $('#qae-search #qae-wd').val();
                if($wd){
                    location.href = $action.replace('FFWD',encodeURIComponent($wd));
                }else{
                    $("#qae-wd").focus();
                    $("#qae-wd").attr('data-toggle','tooltip').attr('data-placement','bottom').attr('title','请输入关键字').tooltip('show');
                }
                return false;
            });
        },
        'keydown': function(){//回车
            $("#qae-search input").keyup(function(event){
                if(event.keyCode == 13){
                    location.href = '/search/video/'+encodeURIComponent($('#qae-search #qae-wd').val())+'.html';
                }
            });
        },
    'autocomplete': function(){
        $.ajaxSetup({
            cache: true
        });
        $.getScript("http://cdn.bootcss.com/jquery.devbridge-autocomplete/1.2.26/jquery.autocomplete.min.js", function(response, status) {
            $('#qae-wd').autocomplete({
                serviceUrl : '/searchcomplete/video',
                params: {'limit': 10},
                paramName: 'keyword',
                maxHeight: 400,
                transformResult: function(response) {
                    var obj = $.parseJSON(response);
                    return {
                        suggestions: $.map( obj.suglist, function(dataItem) {
                            return { value: dataItem, data: dataItem };
                        })
                    };
                },
                onSelect: function (suggestion) {
                    location.href = '/search/video/'+suggestion.data+'.html';
                }
            });
        });
    }
},
    'history':function () {
        $('.qaecms_history').hover(function () {
            $('#history').show();
        })
        $('#history').hover(function () {
            $(this).show();
        },function () {
            $(this).hide();
        })
        $('.qaehistory').click(function () {
             location.href = $(this).attr('data-href')
        })
    }
}
$(document).ready(function () {
    qaecms.search.autocomplete();
    qaecms.search.submit();
    qaecms.search.keydown();
    qaecms.history()
});
