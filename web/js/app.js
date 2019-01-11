/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = {
    changeStatus: function (url, trigger, id)
    {
        var status = 0;
        if ($('#' + trigger.id).is(":checked")) {
            status = 1;
        }
        $('.global-loader').show();
        $.ajax({
            type: "GET",
            url: baseUrl + url,
            data: {
                "id": id
            },
        })
        .done(function (res) {
            $(".global-loader").hide();
        })
        .fail(function (jqXHR, textStatus, errorThrown)
        {
            $(".global-loader").hide();
            console.log(jqXHR.responseText);
        })
        .always(function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            console.log(textStatus);
        })
    },
    showHideLink:function(v)
    {
        if($.trim(v)!=""){
            if(v=='L'){
                $("#link-section").show();
            }else{
                $("#link-section").hide();
                $("#banners-url").val("");
            }
        }else{
            $("#link-section").hide();
            $("#banners-url").val("");
        }
    }
}

