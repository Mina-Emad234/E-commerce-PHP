$(function () {
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn();
    })
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('select').selectBoxIt({
        autoWidth:false
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('[placeholder]').focus(function () {
        $(this).attr('data-store',$(this).attr('placeholder'));
        $(this).attr('placeholder',"");
    }).blur(function () {
        $(this).attr('placeholder',$('input').attr('data-store'));
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('input').each(function(){
        if($(this).attr('required')){
            $(this).after("<span class='astirisk'>*</span>");
        }
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.confirm').click(function(){
        return confirm("Are you sure that you Will delete this?")
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.live').keyup(function(){
        $($(this).data('class')).text($(this).val());
        console.log($($(this).data('class')).text());
        console.log($(this).val());
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $("[name='tags']").tagsInput();
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

});