$(function () {
    $('.tree-item').each(function(index) {
        var $treeItem = $(this);
        var $tree = $(this).parent().parent();
        if ($tree.find('.tree-left .tree').length) {
            $treeItem.append($('<div id="arrow-left-' + index + '" class="arrow arrow-left">'));
        }

        if ($tree.find('.tree-right .tree').length) {
            $treeItem.append($('<div id="arrow-right-' + index + '" class="arrow arrow-right">'));
        }
    })
});
