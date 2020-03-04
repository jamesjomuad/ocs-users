$(document).on('ready',function(){
    $('[class]').each(function(){
        var newClass = $(this).attr('class').replace(/  +/g, ' ');
        $(this).attr('class',newClass)
    });

    $('.permissioneditor table .section').nextUntil('.last-section-row+tr.section').hide();
    $('.permissioneditor table .section').addClass('closed')
});

$(document).on('click','.permissioneditor table .section',function(){
    var $perms = $(this).nextUntil('.last-section-row+tr.section')
    if($(this).is('.closed')){
        $perms.show();
    }else{
        $perms.hide();
    }
    $(this).toggleClass('closed');
})