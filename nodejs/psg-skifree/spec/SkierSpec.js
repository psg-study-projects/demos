//_ = require('lodash');

describe("Skier", function() {

    var skier = require('../js/skier.js');
    var gameDims = { w: 1000, h: 1000 };
    
    beforeEach(function() {
        skier.init({}, {
            'gameDims': gameDims
        });
        //console.log(skier.debug());
    });

    it("should not be moving initially", function() {
        expect(skier.isMoving()).toBe(false);
    });

    it("should be facing right initially", function() {
        expect(skier.getDirection()).toEqual('right');
    });

    it("should be moving after down arrow pressed", function() {
        skier.turn('down');
        expect(skier.isMoving()).toBe(true);
    });

    it("should be moving after left arrow pressed (from init, facing right)", function() {
        skier.turn('left');
        expect(skier.isMoving()).toBe(true);
    });

    it("should not be moving if crashed", function() {
        skier.turn('down');
        skier.crash();
        expect(skier.isMoving()).toBe(false);
    });

    it("should not crash program after attempting to move left after a crash", function() {
        skier.turn('down');
        skier.crash();

        skier.turn('right');
        skier.turn('down');
        expect(skier.isMoving()).toBe(false);

        skier.turn('left');
        skier.turn('down');
        expect(skier.isMoving()).toBe(true);
    });

});
