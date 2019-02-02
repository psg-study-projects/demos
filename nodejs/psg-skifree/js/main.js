$(document).ready(function() {

    'use strict';

    // global 'modules':
    //      game
    //      skier
    //      obstacleCollection
    //      assetManager

    // Agree on the following direction names ('enums'):
    //   left
    //   left-down
    //   down
    //   right-down
    //   right
    //   up

    //var loadedAssets = {};

    var gameDims = {
        w: window.innerWidth,
        h: window.innerHeight,
    };

    var canvas = $('<canvas></canvas>')
        .attr('width', gameDims.w * window.devicePixelRatio)
        .attr('height', gameDims.h * window.devicePixelRatio)
        .css({
            position: 'absolute',
            top: 0,
            left: 0,
            'z-index': 10,
            width: gameDims.w + 'px',
            height: gameDims.h + 'px'
        });
    $('body').append(canvas);

    var setupKeyhandler = function() {
        $(window).keydown(function(event) {
            event.preventDefault();
            game.handleKey(event.which, 'keydown');
        });
        $(window).keyup(function(event) {
            event.preventDefault();
            game.handleKey(event.which, 'keyup');
        });
    };

    // Initialization

    assetManager.init();

    assetManager.loadAssets().then(function() {

        var loadedAssets = assetManager.getLoadedAssets();

        skier.init(loadedAssets, {
            'gameDims': gameDims
        });
        obstacleCollection.init(loadedAssets,{
            'gameDims': gameDims
        });
        game.init({
            'canvas': canvas, 
            'skier': skier, 
            'obstacleCollection': obstacleCollection,
            'gameDims': gameDims,
            'devicePixelRatio': window.devicePixelRatio
        });
    
        setupKeyhandler();
        obstacleCollection.placeInitial();

        requestAnimationFrame(game.loop); // start thee game

    });

});
