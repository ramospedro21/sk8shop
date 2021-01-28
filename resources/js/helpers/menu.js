$('.custom-dropdown').on('mouseover', function() {

    var target = $(this).data('target');
    $('.dropdown-menu').hide()
    $(target).show()

})

$('.dropdown-menu').on('mouseout', function() {

    $('.dropdown-menu').hide()

})

$('#header').on('mouseout', function() {

    $('.dropdown-menu').hide()

})
