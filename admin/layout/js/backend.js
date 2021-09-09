$(function () {
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.card-body').slideToggle(350);
        if($(this).hasClass('selected')){
            $(this).html("<i class='fa fa-minus fa-lg'></i>");
        }else{
            $(this).html("<i class='fa fa-plus fa-lg'></i>");
        }
    })

    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('select').selectBoxIt({
        autoWidth:false
    })
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('[placeholder]').focus(function () {
        $(this).attr('data-test',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-test'))
    })
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('input').each(function(){
        if($(this).attr('required')){
            $(this).after("<span class='astirisk'>*</span>");
        }
    });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
     var passField = $('.password');
     $('.show-pass').hover(function(){
        passField.attr('type','text')
     },function(){
        passField.attr('type','password')

     })
     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.confirm').click(function(){
        return confirm("Are you sure that you Will delete this?")
    })
     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.cat h3').click(function(){
        $(this).next('.full-view').slideToggle(200);
    })
     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.option span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view')=='full'){
            $('.cat .full-view').fadeIn(200);
        }else{
            $('.cat .full-view').fadeOut(200);
        }
    })
     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $('.child-link').hover(function(){
        $(this).find('.show-delete').fadeIn(100)
    },function(){
        $(this).find('.show-delete').fadeOut(100)
    })
     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $("[name='tags']").tagsInput();
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    
});