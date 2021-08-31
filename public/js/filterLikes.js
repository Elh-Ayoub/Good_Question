$('.filter').on('click', function(){
    filterBy = $(this).find('.filterBy').data('id');
    $('.filter').each(function(i, obj) {
        if($(obj).find('.filterBy').data('id') !== filterBy){
            $(obj).removeClass('btn-info');
            $(obj).addClass('btn-secondary');
            $(obj).find('.icon').removeClass('fa fa-arrow-down')
        }else{
            $(obj).removeClass('btn-secondary');
            $(obj).addClass('btn-info');
            $(obj).find('.icon').addClass('fa fa-arrow-down');
        }
    });
    showAll();
    if(filterBy == 'both'){
        showAll();
    }else{
        $('.likes-box').each(function(i, obj) {
            if($(obj).data('type').trim() !== filterBy){
                obj.setAttribute('style', 'display: none;');
            }
        });
    }
})

function showAll(){
    $('.likes-box').each(function(i, obj) {
        obj.setAttribute('style', 'display: block;');    
    });
}