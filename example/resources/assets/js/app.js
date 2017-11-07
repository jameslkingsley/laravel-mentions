require('./bootstrap');

new Mentions({
    // Input element selector
    // Defaults to .has-mentions
    input: '.has-mentions',

    // Output form field selector
    // Defaults to #mentions
    output: '#mentions',

    // Pools
    pools: [{
        // Trigger the popup on the @ symbol
        // Defaults to @
        trigger: '@',

        // Pool name from the mentions config
        pool: 'users',

        // Same value as the pool's 'column' value
        display: 'name',

        // The model's primary key field name
        reference: 'id'
    }]
});
