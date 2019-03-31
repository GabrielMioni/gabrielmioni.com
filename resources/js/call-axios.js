module.exports = {
  callAxios: (url, callback) => {
    axios.get(url)
      .then((data) => {
        const data_obj = data.data;
        if (typeof callback === 'function') {
          callback(data_obj)
        }
      });
  },
};
