require('./bootstrap');

new Mentions({
    input: '.has-mentions',
    output: '#mentions',
    pools: [{
        trigger: '@',
        pool: 'users',
        display: 'name',
        reference: 'id'
    }]
});

new Mentions({
    input: '.has-mentions-update',
    output: '#mentions_update',
    pools: [{
        trigger: '@',
        pool: 'users',
        display: 'name',
        reference: 'id'
    }]
});
