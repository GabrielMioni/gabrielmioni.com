module.exports = {
  callAxios: (url, callback) => {
    axios.get(url)
      .then((data) => {
        console.log('data', data);
        const data_obj = data.data;
        if (typeof callback === 'function') {
          callback(data_obj)
        }
      })
      .catch((error)=>{
        console.log('error', error);
        callback(error);
      })
  },
};
