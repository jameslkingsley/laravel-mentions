;(function($) {
    $.fn.mentions = function(pools, options) {
        var node = $(this);

        node.atwho($.extend(true, {
            at: '@',
            limit: 5,
            insertTpl: '${atwho-at}${name}',
            callbacks: {
                remoteFilter: function(query, callback) {
                    if (query.length <= 1) return;

                    $.getJSON('/api/mentions/', {
                        p: pools,
                        q: query
                    }, function(data) {
                        callback(data);
                    });
                }
            }
        }, options));

        node.on('inserted.atwho', function(jevent, li, bevent) {
            var name = li.text().trim();
            var field = node.parents('form').find('#mentions-list');
            var list = field.val().split(',');
            if (list.includes(name)) return;
            list.push(name);
            field.val(list.toString());
        });
    }
})(jQuery);
