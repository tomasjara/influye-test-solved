<template>
    <div style="padding: 15px 0 30px 0; display:flex; gap:10px; justify-content: space-between; align-items: center;">
        <div>Total Items: {{ itemsTotal }}</div>
        <div class="red">Items con bajo stock: {{ itemsWarning }}</div>
    </div>
</template>

<script>
export default {
    props: {
        items: {
            type: Array,
            default: () => [],
        },
        onWarning: {
            type: Function,
            default: () => { }
        }
    },
    computed: {
        itemsTotal() {
            return this.items.length;
        },
        itemsWarning() {
            if (!Array.isArray(this.items)) return
            // console.log('items: ', this.items);
            return this.items.filter(item => Number(item.quantity) < 0).length
        }
    },
    watch: {
        itemsWarning(newValue) {
            if (newValue > 0) {
                this.$emit('onWarning', newValue);
            }
        }
    }
}
</script>

<style></style>