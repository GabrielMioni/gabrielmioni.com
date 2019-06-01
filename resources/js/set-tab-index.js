module.exports = {
  setTabIndex: (expanded) => {
    console.log('expanded: ', expanded);
    return expanded === true ? '0' : '-1';
  }
};
