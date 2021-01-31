export const handleChunk = (data, size) => {
  return new Promise((resolve, reject) => {
    const sesionesChunk = () => {
      Array.from( { length: Math.ceil(data.length / size) }, (v, i) =>
          data.slice(i * size, i * size + size)
      )
    }
    const data2 = sesionesChunk();
    resolve(data2)
  })
} 
