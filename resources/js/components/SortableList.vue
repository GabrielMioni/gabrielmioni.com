<script>
    import { Sortable } from "@shopify/draggable"

    function move(items, oldIndex, newIndex) {
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
    }

    export default {
        props: {
            value: {
                required: true
            },
            itemClass: {
                default: "sortable-list-item"
            },
            handleClass: {
                default: "sortable-list-handle"
            }
        },
        provide() {
            return {
                sortableListItemClass: this.itemClass,
                sortableListHandleClass: this.handleClass
            }
        },
        mounted() {
            const sortable = new Sortable(this.$el, {

                draggable: `.${this.itemClass}`,
                handle: `.${this.handleClass}`,
                mirror: {
                    constrainDimensions: true
                }
            }).on("sortable:stop", ({ oldIndex, newIndex }) => {
                this.$emit("input", move(this.value, oldIndex, newIndex))
            })
        },
        render() {
            return this.$scopedSlots.default({
                items: this.value
            })
        }
    }
</script>
