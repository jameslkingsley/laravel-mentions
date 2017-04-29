function Mentions(options) {
    var node = document.querySelector(options.input);
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

            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    callback(JSON.parse(this.responseText));
                }
            };

            xhttp.open('post', '/api/mentions?p=' + pool.pool + '&q=' + text, true);
            xhttp.send();
        }
    }

    for (var i = 0; i < options.pools.length; i++) {
        var pool = options.pools[i];

        collections.push({
            trigger: pool.trigger || '@',
            lookup: pool.display,
            allowSpaces: true,
            selectTemplate: selectTemplate(pool),
            values: values(pool)
        });
    }

    var tribute = new Tribute({
        collection: collections
    });

    tribute.attach(node);

    node.addEventListener('keyup', function(event) {
        var input = event.target;
        var mentions = document.querySelector(options.mentions);
        var objects = [];

        var nodes = input.getElementsByClassName('mention-node');
        for (var i = 0; i < nodes.length; i++) {
            objects.push(nodes[i].getAttribute('data-object'));
        }

        mentions.value = objects.join();

        if (input.hasAttribute('for')) {
            document.querySelector(input.getAttribute('for')).value = input.innerHTML;
        }
    });
}
