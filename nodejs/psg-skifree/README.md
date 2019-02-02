# Peter's Edits

Installation:

$ unzip petergorgone-skifree-release.zip
$ cd release
$ npm install
$ npm test
$ node web

Open in browser: http://localhost:5000

Also deployed to AWS: http://skifree.peterg-webdeveloper.com/

What was done:

* **Reproduced & fixed the white-out bug**:  This was caused by the direction value going 'out-of-bounds'. Direction in the orig.
code was updated via increment or decrement (++, --), which is compact but would require checking of boundary conditions. Since
the game only supports a small number of directions, I replaced the implementation with one that uses 'enumerations' and mapping
logix via switch statements. As noted in the comments, this would become impractical if we were to make the directions more 
granular, in which case a better implemenation would be to use degrees and radians, with boundary degree checking.

* **Refactoring**:  The primary design pattern used was the 'revealing module'. This provided alot of 'bang for the buck', in that
it separted concerns, specifically into objects (modules) representing the skier, obstacles, and the game itself. The module pattern
also allows us to hide implementation details, and publish an interface which other modules can use to communicate with the object.

    * Post-refactoring, the module pattern made the code more maintainable and scalable, for instance implementing concepts such as 'jumping' 
      very straighforward. Lastly, it allowed the code to be unit-tested, which was not very easy to do with pre-refactored code.

    * Another 'design pattern' used was a finite-state-machine. This was already implicit in the original code (via the skier's
      direction), but I separated out the 'state' into a direction component and a  '_state' component, the latter represening
      the skier's motion, which can have values of 'crashed','moving','standing', and 'jumping' (it's occuring to me as I'm 
      writing this that there's probably a better name than '_state' for this, eg '_motion'). Decoupling these made the implemenation
      more clear and less prone to bugs such as the 'white-out'.

* **Implement Jumps**: Simple jumping with some animation was implemented. During a jump the skier will not crash.

* **Scoring**: A simple scoring system was introduced, with a point for every downhill unit covered while skiing, and points subracted
for a crash. Score is presisted in browser's local storage.

* **Unit Testing**: Some basic unit tests for the skier were implemented using Jasmine. To run: 'npm test'

* **Server Deployment**: Deployed to an EC2 host on AWS, setup Route 53 subdomain as an alias:

    * http://184.73.69.13/, or...

    * http://skifree.peterg-webdeveloper.com/

Other:

* **Self-Documenting Code**: Where possible, I like to name things in such a manner that reading the code makes it very
obvious what is being implemented. I reserve comments for filling in the gaps, usually associated with a block of code.

* **Help Window**: Added a 'help' button which when pressed will display some basic usage instructions.

What wasn't done:
* Feeding the Rhino 
* Game reset
* Game pause/resume
* Progressive difficulty


# Ceros Ski Code Challenge

Welcome to the Ceros Code Challenge - Ski Edition!

For this challenge, we have included some base code for Ceros Ski, our version of the classic Windows game SkiFree. If
you've never heard of SkiFree, Google has plenty of examples. Better yet, you can play our version here: 
http://ceros-ski.herokuapp.com/  

We understand that everyone has varying levels of free time, so take a look through the requirements below and let us 
know when you will have something for us to look at (we also get to see how well you estimate and manage your time!). 
Previous candidates have taken about 8 hours to complete our challenge. We feel this gives us a clear indicator of your
technical ability and a chance for you to show us how much you give a shit (one of Ceros's core values!) about the position
you're applying for. If anything is unclear, don't hesitate to reach out.

Requirements:
* The base game that we've sent you is not what we would consider production ready code. In fact, it's pretty far from
  it. As part of our development cycle, all code must go through a review. We would like you to perform a review
  on the base code and fix/refactor it. Is the codebase maintainable, unit-testable, and scalable? What design patterns 
  could we use? 
  
  **We will be judging you based upon how you update the code & architecture.**
* There is a bug in the game. Well, at least one bug that we know of. Use the following bug report to debug the code
  and fix it.
  * Steps to Reproduce:
    1. Load the game
    1. Crash into an obstacle
    1. Press the left arrow key
  * Expected Result: The skier gets up and is facing to the left
  * Actual Result: Giant blizzard occurs causing the screen to turn completely white (or maybe the game just crashes!)
* The game's a bit boring as it is. Add a new feature to the game to make it more enjoyable. We've included some ideas for
  you below (or you can come up with your own new feature!). You don't need to do all of them, just pick something to show 
  us you can solve a problem on your own. 
  * Implement jumps. The asset file for jumps is already included. All you gotta do is make the guy jump. We even included
      some jump trick assets if you wanted to get really fancy!
  * Add a score. How will you know that you're the best Ceros Skier if there's no score? Maybe store that score
      somewhere so that it is persisted across browser refreshes.
  * Feed the hungry Rhino. In the original Ski Free game, if you skied for too long, a yeti would chase you
      down and eat you. In Ceros Ski, we've provided assets for a Rhino to catch the skier.
* Update this README file with your comments about your work; what was done, what wasn't, features added & known bugs.
* Provide a way for us to view the completed code and run it, either locally or through a cloud provider
* Be original. Don’t copy someone else’s game implementation!

Bonus:
* Provide a way to reset the game once the game is over
* Provide a way to pause and resume the game
* Skier should get faster as the game progresses
* Deploy the game to a server so that we can play it without setting something up ourselves. We've included a 
  package.json and web.js file that will enable this to run on Heroku. Feel free to use those or use your own code to 
  deploy to a cloud service if you want.
* Write unit tests for your code

And don't think you have to stop there. If you're having fun with this and have the time, feel free to add anything else
you want and show us what you can do! 

We are looking forward to see what you come up with!

