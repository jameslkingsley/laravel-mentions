(function($) {
    $.fn.mentions = function(pools, options) {
        var node = $(this);
        var collections = [];

        var selectTemplate = function(pool) {
            return function(item) {
                return '<span class="mention-node" data-object="'
                    + pool.pool + ':' + item.original[pool.reference] + '">'
                    + (pool.trigger || '@')
                    + item.original[pool.display] + '</span>';
            }
        }

        var values = function(pool) {
            return function(text, callback) {
                if (text.length <= 1) return;

                $.post('/api/mentions', {
                    p: [pool.pool],
                    q: text
                }, function(data) {
                    callback(data);
                }, 'json');
            }
        }

        for (var i = 0; i < pools.length; i++) {
            var pool = pools[i];
            var options = $.extend({
                trigger: '@',
                lookup: pool.display,
                allowSpaces: true,
                selectTemplate: selectTemplate(pool),
                values: values(pool)
            }, pool);

            collections.push(options);
        }

        var tribute = new Tribute({
            collection: collections
        });

        tribute.attach(node);

        node.keyup(function(event) {
            var input = $(this);
            var mentions = input.parents('form').find('input[name="mentions"]');
            var objects = [];

            input.find('.mention-node').each(function() {
                objects.push($(this).data('object'));
            });

            mentions.val(objects.join());

            if (input.attr('for')) {
                input.parents('form')
                    .find('*[name="' + input.attr('for') + '"]')
                    .val(input.html());
            }
        });
    }
})(jQuery);
