module.exports = {
  callAxios: (url, callback) => {
    axios.get(url)
      .then((response) => {
        if (typeof callback === 'function') {
          callback(response)
        }
      })
      .catch((error)=>{
        callback(error);
      })
  },
};
