$(function() {
    APP.schemaView.init();
});

var APP = APP || {};

APP.schemaView = {
    init: function() {
        this.draggable();
    },

    draggable: function() {
        $('#draggable').draggable({
            containment :[10, $('.db_container').offset().top],
            drag: function() {
                var offset = $(this).offset();
                var xPos = offset.left;
                var yPos = offset.top;
                $('#posX').text('x: ' + xPos);
                $('#posY').text('y: ' + yPos);
                var newPosition = {};
                newPosition.left = offset.left;
                newPosition.top = offset.top;

                if (newPosition.left > islandBBox.left && newPosition.left < islandBBox.right &&
                      newPosition.top > islandBBox.top && newPosition.top < islandBBox.bottom) {
                    if (this.left <= islandBBox.left) {
                      newPosition.left = islandBBox.left;
                    } else if (this.left >= islandBBox.right) {
                      newPosition.left = islandBBox.right;
                    }
                    if (this.top <= islandBBox.top) {
                      newPosition.top = islandBBox.top;
                    } else if (this.top >= islandBBox.bottom) {
                      newPosition.top = islandBBox.bottom;
                    }
                }
                line.position();
            }
        });

        var startElement = document.getElementById('abc'),
        line = new LeaderLine(startElement, document.getElementById('draggable'), {path: 'grid'}),
        islandBBox = document.getElementById('draggable').getBoundingClientRect();

        islandBBox = {
            left: islandBBox.left + window.pageXOffset ,
            top: islandBBox.top + window.pageYOffset ,
            right: islandBBox.right + window.pageXOffset,
            bottom: islandBBox.bottom + window.pageYOffset
        };

        $('.card').width($('.schema_table').width()+10);

        var lastScrollTop = 0;
        $('.db_container').scroll(function() {
            line.position();

            //detect vertical scroll to increase height
            var scrollTop = $(this).scrollTop();
            if (lastScrollTop != scrollTop) {
                $(this).height($(window).height());
            }

            lastScrollTop = scrollTop;
        });
    },

};
