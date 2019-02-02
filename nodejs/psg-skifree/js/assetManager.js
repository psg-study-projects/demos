var assetManager = (function() {
    'use strict';

    var _assets = {
        'skierCrash' : 'img/skier_crash.png',
        'skierLeft' : 'img/skier_left.png',
        'skierLeftDown' : 'img/skier_left_down.png',
        'skierDown' : 'img/skier_down.png',
        'skierRightDown' : 'img/skier_right_down.png',
        'skierRight' : 'img/skier_right.png',
        'skierJump1' : 'img/skier_jump_1.png',
        'skierJump2' : 'img/skier_jump_2.png',
        'skierJump3' : 'img/skier_jump_3.png',
        'tree' : 'img/tree_1.png',
        'treeCluster' : 'img/tree_cluster.png',
        'rock1' : 'img/rock_1.png',
        'rock2' : 'img/rock_2.png'
    };
    var _loadedAssets = {};

    var init = function() {
    };

    var getLoadedAssets = function() {
        return _loadedAssets;
    };

    var loadAssets = function() {
        var assetPromises = [];

        _.each(_assets, function(asset, assetName) { // assetName acts as the 'hash key'
            var assetImage = new Image();
            var assetDeferred = new $.Deferred();

            assetImage.onload = function() {
                assetImage.width /= 2;
                assetImage.height /= 2;

                _loadedAssets[assetName] = assetImage;
                assetDeferred.resolve();
            };
            assetImage.src = asset;

            assetPromises.push(assetDeferred.promise());
        });

        return $.when.apply($, assetPromises);
    };

    // --- Return Object ---

    return {
        loadAssets: loadAssets,
        getLoadedAssets: getLoadedAssets,
        init: init
    }
})();
