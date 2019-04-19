module.exports = {
  setImageUrl: (folder, imgData, ext) => {
    if (typeof imgData === 'object') {
      return imgData['fileUrl'];
    }
    if (typeof imgData === 'string') {
      imgData = imgData.trim();
      if (imgData === '') {
        return '';
      }
      return `${folder}/${imgData}.${ext}`;
    }
  },
};
