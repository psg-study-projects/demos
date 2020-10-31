#  Getgris coding challenge for Peter S Gorgone - 20201030

To run:
```
$ node index.js

  result: {
    val: 55555,
    path: 'RRRDDDDRDRDDDRDRDRDRRRDDDDDDRRDRDDRRDDDDDRRRDRRDDRDRDDRRDRRRDRRRDRDDRRRDRRDRRDRDRRDDDDRDDRDDRDRRRR'
  }
```

## Original Problem Statement:

You are given a 2-dimensional grid of integers, like the example below. Consider the possible 
paths through the numbers, from the upper-left to the lower-right corner, that only move 
down or to the right. The value of a path is the sum of all the numbers that 
it visits. The goal is to find the path with the greatest value. If there is a tie, 
any one of such paths would do.

Ref: 
* https://www.getgrist.com/apply-20
* https://www.getgrist.com/jobs?gclid=CjwKCAjw0On8BRAgEiwAincsHFbF9FVvvPpdQjtyR_ETB9r2GW1TcbN9gs9Yz9uo_SqLPLrHG6d1bxoCS9gQAvD_BwE


## Assumptions & Known Constraints:
* Assuming a square grid N X N, where we can only move Down (D) or Right (R)...

Strategy: overlay a 'Pascal Triangle' onto the grid, with the grid values applied to each triangle 'cell'.  In other words, re-map the grid into a Pascal Triangle. In the code below this is done along with a 'meta' attribute that we can use to keep track of the max path.

