export default function renderItemsTable() {
    var t = this
    $('.items-row').fadeOut(100,function() {
        var that = $(this)
        that.empty()

        t.table.forEach(function(row){
            that.append($(row))
        })

        $('#ttltxt>span').text(t.pricer(t.state.total))
    }).fadeIn(100)
}