<template>
    <div v-bind:class="{ 'form-inline inline-input no-gutters' : isInline }">
        <div v-if="isInline" class="col-sm-2">
            <label class="justify-content-start" :for="setInputName(inputTitle)">{{ inputTitle | ucFirst }}</label>
        </div>
        <label v-else :for="setInputName(inputTitle)">{{ inputTitle | ucFirst }}</label>
        <input v-if="isTextArea === false"
               type="text" class="form-control"
               v-bind:class="{'col-sm-10 pl-3' : isInline }"
               :name="setInputName(inputTitle)"
               :value="value"
               v-on:input="updateInput"
               :tabindex="inputTabIndex()"
        >
        <textarea v-else
                  class="form-control"
                  v-bind:class="{'col-sm-10 pl-3' : isInline }"
                  :name="setInputName(inputTitle)"
                  :value="value"
                  v-on:input="updateInput"
                  :tabindex="inputTabIndex()"
        ></textarea>
    </div>
</template>

<script>
    import { setTabIndex } from '../set-tab-index';

    export default {
        name: "form-text-input",
        props: {
            inputTitle: {
                type: String,
                required: true
            },
            setInputName: {
                type: Function,
                required: true
            },
            value: {
                type: String,
                required: true
            },
            isTextArea: {
                type: Boolean,
                require: false,
                default: false,
            },
            isInline: {
                type: Boolean,
                require: false,
                default: false,
            },
            expanded: {
                type: Boolean,
                default: true,
            }
        },
        methods: {
            updateInput(e) {
                let text = e.target.value;
                this.$emit('input', text);
            },
            inputTabIndex() {
                return setTabIndex(this.expanded);
            }
            /*setTabIndex() {
                if (this.expanded === true) {
                    return "0";
                }
                return "-1";
            }*/
        },
        filters: {
            ucFirst(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }
    }
</script>
