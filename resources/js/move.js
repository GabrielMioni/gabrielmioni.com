module.exports = {
    move: function (items, oldIndex, newIndex) {
        const itemRemovedArray = [
            ...items.slice(0, oldIndex),
            ...items.slice(oldIndex + 1, items.length)
        ];

        let new_sorted = [
            ...itemRemovedArray.slice(0, newIndex),
            items[oldIndex],
            ...itemRemovedArray.slice(newIndex, itemRemovedArray.length)
        ];

        new_sorted.forEach((sorted, index)=> {
            sorted['order_column'] = index+1;
        });

        return new_sorted;
    },
};
