var game = (function() {
    'use strict';

    var _ctx;
    var _gameDims;
    var _skier;
    var _obstacleCollection;
    var _gameDims;
    var _devicePixelRatio;

    var init = function(config) {
        _ctx = config.canvas[0].getContext('2d'); // %TODO: move to caller, just pass in 'ctx'
        _skier = config.skier;
        _gameDims = config.gameDims; // {.w,.h}
        _devicePixelRatio = config.devicePixelRatio;
        _obstacleCollection = config.obstacleCollection;
    };


    var loop = function() { // %WAS: gameLoop

        _ctx.save();

        // Retina support
        _ctx.scale(_devicePixelRatio, _devicePixelRatio);

        _clearCanvas();

        _skier.move();

        if ( _skier.isPointingDownward() && _skier.isMoving() ) {
            _obstacleCollection.placeOneNew(_skier.getDirection(), _edges());
        }

        // Check for collision...
        if ( _obstacleCollection.isIntersect(_skier.getRect()) ) {
            _skier.crash(); 
        }

        // Perform drawing of skier and obstacles...
        _skier.draw(_ctx);
        _obstacleCollection.draw(_ctx, _skier.getCoord());

        _ctx.restore();

        // manage score
        _maxScore.update(_skier.getScore());
        _maxScore.render();

        requestAnimationFrame(loop);
    };

    var _allowJump = true; // Used to dis-allow the skier from continuous jumping by holding down the space bar

    var handleKey = function(whichKey,upOrDown) {

        var origSkierDir = _skier.getDirection();

        if (upOrDown==='keyup') { // key pressed up event
            switch(whichKey) {
                case 32: // space-bar -> jump
                    _allowJump = true;
                    console.log('detected jump (keyup)...');
                    break;
            }
        } else {
            switch(whichKey) { // key pressed down event
                case 32: // space-bar -> jump
                    if (_allowJump) {
                        console.log('detected jump (keydown)...');
                        _skier.jump();
                        _allowJump = false; // only one jump per space bar down press, reset on up event
                    }
                    break;
                case 37: // left
                    _skier.turn('left');
                    if (origSkierDir==='left') {
                        _obstacleCollection.placeOneNew('left', _edges());
                    }
                    break;
                case 39: // right
                    _skier.turn('right');
                    if (origSkierDir==='right') {
                        _obstacleCollection.placeOneNew('right', _edges());
                    }
                    break;
                case 38: // up
                    _skier.turn('up'); // %NOTE, will only have effect if skier is left or right
                    if ( (origSkierDir==='right') || (origSkierDir==='left') ) {
                        _obstacleCollection.placeOneNew('up', _edges());
                    }
                    break;
                case 40: // down
                    _skier.turn('down');
                    break;
            }
        }

    };

    var _maxScore = {
        isMine: false,
        get: function() {
            return localStorage.maxScore;
        },
        reset: function() {
            localStorage.maxScore = 0;
        },
        update: function(userScore) {
            if (localStorage.maxScore) {
                if (userScore >= localStorage.maxScore) {
                    localStorage.maxScore = userScore;
                    this.isMine = true;
                }
            } else {
                localStorage.maxScore = 0; // init
            }
        },
        render: function() {
            _ctx.font = "30px Arial";
            _ctx.fillStyle = "black";
            _ctx.fillText("Peter's SkiFree! ", 20, 50); 
            _ctx.fillText("score: "+_skier.getScore(), 20, 100); 
            if (this.isMine) {
                _ctx.fillStyle = "red";
            }
            _ctx.fillText("highest ever: "+this.get(), 20, 150); 
        }
    };

    var _clearCanvas = function() {
        _ctx.clearRect(0, 0, _gameDims.w, _gameDims.h);
    };


    // Compute game edges based on current position of skier
    var _edges = function() {
        return {
            t: _skier.getCoord().y,
            b: _skier.getCoord().y + _gameDims.h,
            l: _skier.getCoord().x,
            r: _skier.getCoord().x + _gameDims.w
        };
    }

    // --- Return Object ---

    return {
        loop: loop,
        handleKey: handleKey,
        resetMaxScore: function() { _maxScore.reset(); }, // can be done from console, must be done at start of game
        init: init
    }

})();

if (typeof module !== 'undefined') {
    module.exports = game;
}
