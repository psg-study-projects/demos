var obstacleCollection = (function() {
    'use strict';

    var _obstacles = [];
    var _gameDims;
    var _loadedAssets;

    var _obstacleTypes = [ 'tree', 'treeCluster', 'rock1', 'rock2' ];

    var init = function(loadedAssets, config) {
        _gameDims = config.gameDims; // {.w,.h}
        _loadedAssets = loadedAssets;
    };

    var draw = function(ctx, skierCoord) {
        var newObstacles = [];

        _.each(_obstacles, function(obstacle) {
            var obstacleImage = _loadedAssets[obstacle.type];
            var x = obstacle.x - skierCoord.x - obstacleImage.width / 2;
            var y = obstacle.y - skierCoord.y - obstacleImage.height / 2;

            if(x < -100 || x > _gameDims.w + 50 || y < -100 || y > _gameDims.h + 50) {
                return;
            }

            ctx.drawImage(obstacleImage, x, y, obstacleImage.width, obstacleImage.height);

            newObstacles.push(obstacle);
        });

        _obstacles = newObstacles;
    };

    var isIntersect = function(targetRect) {

        var is = _.find(_obstacles, function(obstacle) {
            var obstacleImage = _loadedAssets[obstacle.type];
            var obstacleRect = {
                l: obstacle.x,
                r: obstacle.x + obstacleImage.width,
                t: obstacle.y + obstacleImage.height - 5,
                b: obstacle.y + obstacleImage.height
            };

            return _intersectRect(targetRect, obstacleRect);
        });

        return is;
    }

    var _intersectRect = function(r1, r2) {
        return !(r2.l > r1.r || r2.r < r1.l || r2.t > r1.b || r2.b < r1.t);
    };


    var placeInitial = function() {
        var numberObstacles = Math.ceil(_.random(5, 7) * (_gameDims.w / 800) * (_gameDims.h / 500));

        var minX = -50;
        var maxX = _gameDims.w + 50;
        var minY = _gameDims.h / 2 + 100;
        var maxY = _gameDims.h + 50;

        for(var i = 0; i < numberObstacles; i++) {
            _placeOneRandom(minX, maxX, minY, maxY);
        }

        _obstacles = _.sortBy(_obstacles, function(obstacle) {
            var obstacleImage = _loadedAssets[obstacle.type];
            return obstacle.y + obstacleImage.height;
        });
    };


    var placeOneNew = function(direction, gameEdges) {
        var shouldPlaceObstacle = _.random(1, 8);
        if(shouldPlaceObstacle !== 8) {
            return;
        }

        switch(direction) {
            case 'left':
                _placeOneRandom(gameEdges.l - 50, gameEdges.l, gameEdges.t, gameEdges.b);
                break;
            case 'left-down':
                _placeOneRandom(gameEdges.l - 50, gameEdges.l, gameEdges.t, gameEdges.b);
                _placeOneRandom(gameEdges.l, gameEdges.r, gameEdges.b, gameEdges.b + 50);
                break;
            case 'down':
                _placeOneRandom(gameEdges.l, gameEdges.r, gameEdges.b, gameEdges.b + 50);
                break;
            case 'right-down':
                _placeOneRandom(gameEdges.r, gameEdges.r + 50, gameEdges.t, gameEdges.b);
                _placeOneRandom(gameEdges.l, gameEdges.r, gameEdges.b, gameEdges.b + 50);
                break;
            case 'right':
                _placeOneRandom(gameEdges.r, gameEdges.r + 50, gameEdges.t, gameEdges.b);
                break;
            case 'up':
                _placeOneRandom(gameEdges.l, gameEdges.r, gameEdges.t - 50, gameEdges.t);
                break;
        }
    };

    // --- Private ---

    var _placeOneRandom = function(minX, maxX, minY, maxY) {
        var index = _.random(0, _obstacleTypes.length - 1);
        var position = _calculateOpenPosition(minX, maxX, minY, maxY);
        _obstacles.push({
            type : _obstacleTypes[index],
            x : position.x,
            y : position.y
        })
    };

    var _calculateOpenPosition = function(minX, maxX, minY, maxY) {
        var x = _.random(minX, maxX);
        var y = _.random(minY, maxY);

        var foundCollision = _.find(_obstacles, function(obstacle) {
            return x > (obstacle.x - 50) && x < (obstacle.x + 50) && y > (obstacle.y - 50) && y < (obstacle.y + 50);
        });

        if(foundCollision) {
            return _calculateOpenPosition(minX, maxX, minY, maxY);
        } else {
            return { x: x, y: y }
        }
    };

    // --- Return Object ---

    return {
        placeInitial: placeInitial,
        placeOneNew: placeOneNew,
        isIntersect: isIntersect,
        draw: draw,
        init: init
    }
})();

if (typeof module !== 'undefined') {
    module.exports = obstacleCollection;
}
