var skier = (function() {
    'use strict';

    var _score; // score corresponds to vertical distance whle 'moving', minus deductions for crashes
    var _speed; // const
    var _jumpDistance; // const
    var _jumpY;
    var _mapX, _mapY; // skier coordinates
    var _state; // crashed, moving, standing, jumping
    var _loadedAssets;
    var _gameDims;

    var init = function(loadedAssets, config) {
        _score = 0;
        _mapX = 0;
        _mapY = 0;
        _speed = 8;
        _state = 'standing';
        _jumpY = 0; // progress of current jump, measured only in vertical direction
        _jumpDistance = 150; // duration/length of a jump
        _directionObj.init('right');
        _gameDims = config.gameDims; // {.w,.h}
        _loadedAssets = loadedAssets;

    };

    var draw = function(ctx) {
        var skierImage = _getAsset();
        var x = (_gameDims.w - skierImage.width) / 2;
        var y = (_gameDims.h - skierImage.height) / 2;

        ctx.drawImage(skierImage, x, y, skierImage.width, skierImage.height);
    };

    // sets state
    var move = function() {

        var delta;

        switch (_state) {
            case 'moving':
            case 'jumping':
                if ( _directionObj.isPointingDownward() ) {
                    switch( _directionObj.val() ) {
                        case 'left-down':
                            delta = Math.round(_speed / 1.4142);
                            _mapX -= delta;
                            _mapY += delta;
                            break;
                        case 'down':
                            delta = _speed;
                            _mapY += delta; // no 'x' translation
                            break;
                        case 'right-down':
                            delta = _speed / 1.4142;
                            _mapX += delta;
                            _mapY += delta;
                            break;
                    }
                    if (_state==='jumping') {
                        _jumpY += delta;
                        if (_jumpY > _jumpDistance) {
                            _state = 'moving'; // finish the jump
                            _jumpY = 0;
                        }
                    }
                    _score += delta; 
                }
                break;
            case 'standing':
                break;
            case 'crashed':
                break;
        }
    };

    // performs a translation
    var walk = function(walkDir) {

        if (_state==='standing') {
            switch(walkDir) {
                case 'left':
                    _mapX -= _speed; // "walk" left
                    break;
                case 'right':
                    _mapX += _speed; // "walk" right
                    break;
                case 'up':
                    _mapY -= _speed; // "walk" up
                    break;
            };
        }
    };

    // sets state
    var jump = function() {
        if (_state==='moving') {
            // start jump
            _jumpY = 0;
            _state = 'jumping';
        }
    };

    // sets state
    var crash = function() {
        // Only crash while moving (skier can walk through/over obstacles, and can jump them)
        if (_state==='moving') {
            _directionObj.setUp();
            _state = 'crashed'; // was: skierDirection = 0;
            _score -= 500; // ouch!
        }
    };

    // sets state
    // turnDir param is the turn direction input (enum) via keyboard or equiv. UI
    // _directionObj wraps the current direction of the skier ('state')
    var turn = function(turnDir) {
        if (_state==='crashed') {
            // only way to 'get up' (uncrash) the skier is to turn up or left
            if (turnDir==='up' || turnDir=='left') {
                _state = 'standing'; // 'un-crash' the skier, next turn event will invoke else clause below...
                walk(turnDir);
            }
        } else if (_state==='jumping') {
            // do nothing, essentially disallowing turning while jumping
        } else {
            switch(turnDir) {
                case 'up':
                    if(_state==='standing') {
                        walk('up');
                    }
                    break;
                case 'left':
                    if(_directionObj.val() === 'left') {
                        walk('left'); // already facing left so take a step in that direction
                    } else {
                        _directionObj.setLeftward();
                        _state = _directionObj.isPointingDownward() ? 'moving' : 'standing';
                    }
                    break;
                case 'right':
                    if(_directionObj.val() === 'right') {
                        walk('right'); // already facing right so take a step in that direction
                    } else {
                        _directionObj.setRightward();
                        _state = _directionObj.isPointingDownward() ? 'moving' : 'standing';
                    }
                    break;
                case 'down':
                    _directionObj.setDown();
                    _state = 'moving';
                    break;
            }
        }
    };

    var getRect = function() {
        var skierImage = _getAsset();

        var skierRect = {
            l: _mapX + _gameDims.w / 2,
            r: _mapX + skierImage.width + _gameDims.w / 2,
            t: _mapY + skierImage.height - 5 + _gameDims.h / 2,
            b: _mapY + skierImage.height + _gameDims.h / 2
        };
        return skierRect;
    };

    // private helper object for managing skier direction
    var _directionObj = {

        _direction: null,

        // To avoid boundary errors, explicitly map direction changes. If we wanted finer-grained direction intervals, this would
        // obviously become unmanageable. An alternative would be to represent the direciton in degrees or radians, and use
        // addition/subtraction to implement turning, with appropriate boundary handlers.
        setLeftward: function() {
            switch (this._direction) {
                case 'left': // no action
                    break;
                case 'left-down':
                    this._direction = 'left';
                    break;
                case 'down':
                    this._direction = 'left-down';
                    break;
                case 'right-down':
                    this._direction = 'down';
                    break;
                case 'right':
                    this._direction = 'right-down';
                    break;
                case 'up':
                    this._direction = 'left';
                    break;
            }
        },
        setRightward: function() {
            switch (this._direction) {
                case 'left':
                    this._direction = 'left-down';
                    break;
                case 'left-down':
                    this._direction = 'down';
                    break;
                case 'down':
                    this._direction = 'right-down';
                    break;
                case 'right-down':
                    this._direction = 'right';
                    break;
                case 'right': // no action
                    break;
                case 'up':
                    this._direction = 'right';
                    break;
            }
        },
        setUp: function() {
            this._direction = 'up';
        },
        setDown: function() {
            this._direction = 'down';
        },
        val: function() {
            return this._direction;
        },
        isPointingDownward: function() {
            return (this._direction==='left-down') || (this._direction==='down') || (this._direction==='right-down');
        },
        init: function(dir) {
            this._direction = dir;
        }
    };


    // skier image depends on state and direction of skier
    var _getAsset = function() {

        var aName; // asset name
        var dir = _directionObj.val();

        if (_state==='crashed') {
            aName = 'skierCrash';
        } else if (_state==='jumping') {
            aName = function() {
                // animate the jump...
                var jumpProgress = 100*(_jumpY/_jumpDistance);
                if (jumpProgress < 33) { 
                    return 'skierJump3';
                } else if (jumpProgress < 67) {
                    return 'skierJump2';
                } else {
                    return 'skierJump1';
                }
            }();
        } else {
            switch(dir) {
                case 'left':
                    aName = 'skierLeft';
                    break;
                case 'left-down':
                    aName = 'skierLeftDown';
                    break;
                case 'down':
                    aName = 'skierDown';
                    break;
                case 'right-down':
                    aName = 'skierRightDown';
                    break;
                case 'right':
                    aName = 'skierRight';
                    break;
                case 'up':
                    aName = 'skierLeft';
                    break;
            }
        }
        return _loadedAssets[aName]; // lookup & return the image (asset)
    };

    // --- Return Object ---

    return {
        draw: draw,
        move: move,
        jump: jump,
        walk: walk,
        turn: turn,
        crash: crash,
        getRect: getRect,
        isPointingDownward: function() { return _directionObj.isPointingDownward(); },
        isMoving: function() { return _state === 'moving'; },
        getDirection: function() { return _directionObj.val(); },
        getCoord: function() { return { x: _mapX, y: _mapY }; },
        getScore: function() { return Math.round(_score); },
        debug: function() {
            return {
                _speed: _speed,
                _state: _state,
                _direction: _directionObj.val(),
                _gameDims: _gameDims,
                _coord: {
                    _mapX: _mapX, 
                    _mapY: _mapY
                }
            }
        },
        init: init
    }
})();

if (typeof module !== 'undefined') {
    module.exports = skier;
}
