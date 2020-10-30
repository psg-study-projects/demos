const data = require('./data');
//const grid = data.gridMed; // test
//const grid = data.gridSmall; // test
const grid = data.grid;

const triangle = grid2Triangle(grid);
let bottom;

let iter = 1;
const maxIter = triangle.length;

do {
  console.log(`iter: ${iter} / ${maxIter}`);
  const popped = triangle.pop();
  if (!triangle.length) { // at 'root'
    break; // done!
  }

  // For each element in the current bottom (triangle) row, compare its 2 children...the larger
  // is 'selected': its value is added to the current element's value, and its selected path
  // is appended based on the selection (Left Child => Move Down, Right Child => Move Right)
  const lastIdx = triangle.length - 1;
  bottom = triangle[lastIdx]; // reference to new bottom row in triangle
  for ( let i = 0 ; i < bottom.length ; i++ ) {
    // Select a 'winner'...
    if ( popped[i+0].val >= popped[i+1].val ) {
      bottom[i].val += popped[i+0].val;
      bottom[i].meta.selected = 'D'+popped[i+0].meta.selected;
    } else {
      bottom[i].val += popped[i+1].val;
      bottom[i].meta.selected = 'R'+popped[i+1].meta.selected;
    }
  }
  iter += 1;
} while (1);

const result = bottom.length ? { val: bottom[0].val, path: bottom[0].meta.selected } : null;
console.log('RESULT', { result } );

// -------

function grid2Triangle(grid)
{
  const height = grid.length;
  const width  = grid[0].length;
  const maxDiagonals = width + height - 1;
  const triangle = [];

  let gridRowIdx = 0;
  while ( gridRowIdx < maxDiagonals ) {
    let x = 0, y = gridRowIdx;
    const triRow = [];
    while (y >= 0) { 
      const val = (x<width && y<height) ? grid[y][x] : 0; // zero pad if 'out-of-bounds'
      const meta = { selected: ''}; // the selected 'path'
      triRow.push({ val, meta });
      y -= 1; // translate diagonally 'up' and 'to the right'
      x += 1;
    }
    triangle.push(triRow);
    gridRowIdx += 1;
  }
  return triangle;
}

