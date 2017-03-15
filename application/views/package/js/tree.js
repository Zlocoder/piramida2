/*
 $(function () {
    $('.tree').each(function(index) {
        var $tree = $(this);
        if ($tree.find('.tree-left .tree').length) {
            $tree.append($('<div id="arrow-left-' + index + '" class="arrow arrow-left">'));

            var svg = SVG('arrow-left-' + index).size('100%','100%');
            var ellipse = svg.ellipse('200%', '210%');

            ellipse.attr({
                cx: -1,
                cy: -6,
                stroke: 'rgba(0,0,0,1)',
                'stroke-width' : '2px',
                fill : 'rgba(0,255,0,0)'}
            );
        }

        if ($tree.find('.tree-right .tree').length) {
            $tree.append($('<div id="arrow-right-' + index + '" class="arrow arrow-right">'));

            SVG('arrow-right-' + index).size(100,100)
                .ellipse(100, -5).radius(105, 105).attr({stroke: 'rgba(0,0,0,1)', fill: 'rgba(0,0,0,0)'});
        }
    })
});
*/