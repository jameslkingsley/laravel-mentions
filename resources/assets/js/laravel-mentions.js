class Mentions {
    constructor(options) {
        this.options = options;
        this.collections = [];

        this.input = this.findNode(this.options.input, '.has-mentions');
        this.output = this.findNode(this.options.output, '#mentions');

        this.collect()
            .attach()
            .listen();
    }

    findNode(selector, defaultSelector) {
        return document.querySelector(
            selector || defaultSelector
        );
    }

    template(pool) {
        return item => {
            return '<span class="mention-node" data-object="'
                + pool.pool + ':' + item.original[pool.reference] + '">'
                + (pool.trigger || '@')
                + item.original[pool.display] + '</span>';
        }
    }

    values(pool) {
        return (text, callback) => {
            if (text.length <= 1) return;

            let xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    callback(JSON.parse(this.responseText));
                }
            };

            xhttp.open('get', '/api/mentions/?p=' + pool.pool + '&q=' + text, true);
            xhttp.send();
        }
    }

    collect() {
        for (let i = 0; i < this.options.pools.length; i++) {
            let pool = this.options.pools[i];
            this.collections.push({
                trigger: pool.trigger || '@',
                lookup: pool.display,
                allowSpaces: pool.allowSpaces || true,
                selectTemplate: this.template(pool),
                values: this.values(pool)
            });
        }

        return this;
    }

    attach() {
        this.tribute = new Tribute({
            collection: this.collections
        });

        this.tribute.attach(this.input);

        return this;
    }

    listen() {
        var instance = this;

        this.input.addEventListener('keyup', event => {
            let input = event.target;
            let mentions = instance.output;
            var nodeList = [];

            let nodes = input.getElementsByClassName('mention-node');

            for (let i = 0; i < nodes.length; i++) {
                nodeList.push(nodes[i].getAttribute('data-object'));
            }

            mentions.value = nodeList.join();

            if (input.hasAttribute('for') && ! (instance.options.ignoreFor || false)) {
                document.querySelector(input.getAttribute('for')).value = input.innerHTML;
            }
        });
    }
}

export default Mentions
