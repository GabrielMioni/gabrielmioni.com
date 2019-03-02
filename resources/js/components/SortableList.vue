<script>
    import { Sortable } from "@shopify/draggable"
    import { move } from '../move'

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
