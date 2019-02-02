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

