
import {handleChunk} from './helpers';
test('should return data array chunk', async () => {
  const arr = []
  const size = 10
  const data  = await handleChunk(arr, size);
  expect(data).toEqual([1]);
});